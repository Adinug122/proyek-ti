<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentCallbackController extends Controller
{
    public function callback(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        
        // 1. Buat Signature Key untuk validasi keamanan
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            // Karena di CartController kamu pakai $order->id . '-' . time()
            // Kita ambil ID aslinya saja (sebelum tanda strip)
            $orderId = explode('-', $request->order_id)[0];
            $order = Order::find($orderId);

            if (!$order) return response()->json(['message' => 'Order tidak ditemukan'], 404);

            // 2. Cek Status Transaksi dari Midtrans
            $status = $request->transaction_status;

            if ($status == 'settlement' || $status == 'capture') {
                // OTOMATIS JADI SUCCESS
                $order->update(['status' => 'paid']);
            } elseif ($status == 'pending') {
                $order->update(['status' => 'pending']);
            } elseif ($status == 'expired' || $status == 'cancelled') {
                $order->update(['status' => 'cancelled']);
            }

            Payment::where('order_id', $orderId)->update([
                'transaction_id'     => $request->transaction_id,
                'transaction_status' => $status,
                'raw_response'       => json_encode($request->all()), // Simpan log JSON lengkap
            ]);

            return response()->json(['message' => 'Callback Berhasil']);
        }

        return response()->json(['message' => 'Signature Invalid'], 403);
    }
}