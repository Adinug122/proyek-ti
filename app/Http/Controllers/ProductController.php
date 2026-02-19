<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request){
    //      if(!session()->has('order_customer')){
    //     return redirect('/start-order?table='.$request->table);
    // }

    $products = Product::paginate(10);

    return view('menu.index',compact('products'));
    }

    public function setType(Request $request)
{
    $request->validate([
        'order_type' => 'required|in:dine_in,take_away'
    ]);

    // SIMPAN STATUS ORDER
    session(['order_type' => $request->order_type]);

    $tableNumber = $request->query('table');

    if ($request->order_type == 'dine_in') {
        session([
            'number' => $tableNumber,
            'table_id' => $tableNumber,
            'order_customer' => true 
        ]);
    } else {
        
        session([
            'order_customer' => true 
        ]);

        session()->forget(['number', 'table_id']);
    }

    return redirect()->route('product.index');
}

}

