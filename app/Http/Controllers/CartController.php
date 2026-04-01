<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class CartController extends Controller
{
    public function index() {
        $cart = session('cart', []);
        return response()->json($this->total($cart));
    }

    public function add(Request $r) {
        $id = $r->input('id');
        if (!$id) return response()->json(['error' => 'ID tidak diterima'], 422);

        $p = Product::find($id);
        if (!$p) return response()->json(['error' => 'Produk tidak ditemukan'], 404);

        $cart = session('cart', []);
        if (isset($cart[$p->id])) {
            $cart[$p->id]['qty']++;
        } else {
            $cart[$p->id] = [
                'id' => $p->id,
                'name' => $p->name_product,
                'price' => $p->price,
                'image' => $p->image,
                'qty' => 1
            ];
        }

        session(['cart' => $cart]);
        return response()->json($this->total($cart));
    }

    public function update(Request $request)
{
    $cart = session()->get('cart', []);

    if (isset($cart[$request->id])) {
        if ($request->action === 'plus') {
            $cart[$request->id]['qty']++;
        }

        if ($request->action === 'minus') {
            $cart[$request->id]['qty']--;
            if ($cart[$request->id]['qty'] <= 0) {
                unset($cart[$request->id]);
            }
        }
        session()->put('cart', $cart);
    }

    $calc = $this->total($cart);
    $biayaLayanan = 3000;
    // Hitung total bayar terbaru
    $totalBayar = $calc['total'] > 0 ? $calc['total'] + $biayaLayanan : 0;

    return response()->json([
        'success' => true,
        'items' => $cart, 
        'subtotal' => $calc['total'],
        'total_bayar' => $totalBayar
    ]);
}

    public function checkout(Request $request) {
        $cart = session('cart', []);
        if (empty($cart)) return response()->json(['error' => 'Keranjang kosong'], 422);

        $paymentMethod = $request->input('payment_method'); 
        $calc = $this->total($cart);
        $totalAkhir = $calc['total'] + 3000;

        $existingOrderId = session('pending_order_id');
        return DB::transaction(function () use ($totalAkhir, $cart, $request, $paymentMethod, $existingOrderId) {
            $tableNumber = $request->input('table');
            $tableId = null;

            if ($request->input('type') !== 'take-away' && $tableNumber) {
                $tableData = DB::table('tables')->where('number', $tableNumber)->first();
                if ($tableData) $tableId = $tableData->id;
            }

            $order = Order::updateOrCreate(
                ['id' => $existingOrderId, 'status' => 'pending'],
                [
                'order_type'     => (str_contains($request->input('type'), 'dine')) ? 'dine_in' : 'take_away',
                'tables_id'      => $tableId,
                'customer_name'  => $request->input('customer_name'),
                'customer_phone' => $request->input('customer_phone'),
                'total_price'    => $totalAkhir,
                'status'         => 'pending',
                ]
            );

            $order->items()->delete();
            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id'    => $order->id,
                    'product_id'  => $item['id'],
                    'product_name'=> $item['name'],
                    'price'       => $item['price'],
                    'qty'         => $item['qty'],
                    'subtotal'    => $item['price'] * $item['qty']
                ]);
            }

            Payment::updateOrCreate(
                ['order_id' => $order->id,],
                [
                'invoice_number'     => 'INV-' . strtoupper(uniqid()),
                'payment_type'       => $paymentMethod, 
                'gross_amount'       => $totalAkhir,
                'transaction_status' => 'pending',
                ]
            );

            if ($paymentMethod === 'qris') {
                Config::$serverKey = env('MIDTRANS_SERVER_KEY');
                Config::$isProduction = false;
                Config::$isSanitized = true;
                Config::$is3ds = true;

                $params = [
                    'transaction_details' => [
                        'order_id' => $order->id . '-' . time(),
                        'gross_amount' => (int)$totalAkhir,
                    ],
                    'customer_details' => [
                        'first_name' => $request->input('customer_name'),
                        'phone' => $request->input('customer_phone'),
                    ],
                ];

                try {
                    $snapToken = Snap::getSnapToken($params);
                    $order->update(['snap_token' => $snapToken]); 
                    session(['pending_order_id' => $order->id]);

                    return response()->json([
                        'success' => true,
                        'payment_type' => 'qris',
                        'snap_token' => $snapToken,
                        'order_id' => $order->id
                    ]);
                } catch (\Exception $e) {
                    return response()->json(['error' => $e->getMessage()], 500);
                }
            }

            session()->forget(['cart', 'pending_order_id']);
            return response()->json([
                'success' => true,
                'payment_type' => 'tunai',
                'order_id' => $order->id
            ]);
        });
    }

    public function clearAndFinish() {
        session()->forget(['cart', 'pending_order_id']);
        return redirect('/menu')->with('success', 'Pesanan berhasil dibuat!');
    }

    public function cancelOrder(Request $request)
{
    $orderId = session('pending_order_id');

    if ($orderId) {
        return DB::transaction(function () use ($orderId) {
            $order = Order::find($orderId);
            if ($order && $order->status === 'pending') {
                // Hapus item, payment, dan order itu sendiri
                $order->items()->delete();
                Payment::where('order_id', $orderId)->delete();
                $order->delete();
                
                // Hapus jejak ID di session
                session()->forget('pending_order_id');
                
                return response()->json(['success' => true]);
            }
            return response()->json(['success' => false, 'message' => 'Order tidak ditemukan atau sudah dibayar']);
        });
    }

    return response()->json(['success' => false, 'message' => 'Tidak ada pesanan aktif']);
}
    private function total($cart) {
        $total = 0;
        $count = 0;
        foreach ($cart as $c) {
            $total += $c['price'] * $c['qty'];
            $count += $c['qty'];
        }
        return [
            'total' => $total, 
            'count' => $count, 
            'items' => $cart 
        ];
    }
}