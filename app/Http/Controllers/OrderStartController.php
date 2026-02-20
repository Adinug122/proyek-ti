<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderStartController extends Controller
{
  // OrderStartController.php
public function index(Request $request) {
    // Ambil type dan table dari QR Code (URL)
    $type = $request->query('type'); 
    $table = $request->query('table');
    
    // Kirim ke view start-order
    return view('welcome', compact('type', 'table'));
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

    return redirect()->route('menu.index');
}

}
