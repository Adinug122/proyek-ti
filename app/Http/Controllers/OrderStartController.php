<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderStartController extends Controller
{
     public function index(Request $request)
{
    // hanya set kalau datang dari QR
    if ($request->filled('table')) {
        session(['table_id' => $request->table]);
    }

    // ambil dari session
    $tableId = session('table_id');

    return view('order.start', compact('tableId'));
}


   public function setType(Request $request)
{
    $request->validate([
        'order_type' => 'required|in:dine_in,take_away'
    ]);

    // ambil table dari hidden input
    $tableId = $request->table_id;

    session([
        'order_type' => $request->order_type,
        'table_id'   => $tableId
    ]);

    // kalau takeaway hapus meja
    if ($request->order_type == 'take_away') {
        session()->forget('table_id');
    }

    return redirect()->route('product.index');
}

}
