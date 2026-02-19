<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    function index(){
        return response()->json(session('cart',[]));
    }

   function add(Request $r){

    // ambil JSON dari fetch
    $data = $r->json()->all();
    $id = $data['id'] ?? null;

    if(!$id){
        return response()->json(['error'=>'ID tidak diterima server'],422);
    }

    $p = Product::find($id);

    if(!$p){
        return response()->json(['error'=>'Produk tidak ditemukan'],404);
    }

    $cart = session('cart',[]);

    if(isset($cart[$p->id])){
        $cart[$p->id]['qty']++;
    }else{
        $cart[$p->id]=[
            'id'=>$p->id,
            'name'=>$p->name_product,
            'price'=>$p->price,
            'qty'=>1
        ];
    }

    session(['cart'=>$cart]);

    return response()->json($this->total($cart));
}



function checkout(){

    $cart=session('cart',[]);
   if(empty($cart)){
    return response()->json(['error'=>'Keranjang kosong'],422);
}
    $total=0;
    foreach($cart as $c){
        $total+=$c['price']*$c['qty'];
    }

    // buat order
    $order=Order::create([
        'invoice'=>'ORD-'.date('His').rand(10,99),
        'type'=>session('order_type'),
        'customer_name' => 'adi',
        'customer_phone' => '0181818',
        'table_number'=>session('table_id'),
        'total'=>$total,
        'status'=>'pending'
    ]);

    // simpan detail
    foreach($cart as $c){
        OrderItem::create([
            'order_id'=>$order->id,
            'product_id'=>$c['id'],
            'product_name'=>$c['name'],
            'price'=>$c['price'],
            'qty'=>$c['qty'],
            'subtotal'=>$c['price']*$c['qty']
        ]);
    }

    session()->forget('cart');

    return response()->json([
        'success'=>true,
        'order'=>$order->invoice
    ]);
}


    private function total($cart){
        $total=0; $count=0;
        foreach($cart as $c){
            $total+=$c['price']*$c['qty'];
            $count+=$c['qty'];
        }
        return ['total'=>$total,'count'=>$count,'items'=>$cart];
    }
}
