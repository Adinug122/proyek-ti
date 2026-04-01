<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
public function index(Request $request)
{
    $category = $request->query('category', 'food');
    $products = Product::whereHas('category', function($query) use ($category) {
        $query->where('name_category', 'like', '%' . $category . '%');
    })->get();

    return view('menu', [
        'products' => $products,
        'category' => $category,
        'type'     => $request->type, // Dikirim sebagai 'type'
        'table'    => $request->table, // Dikirim sebagai 'table'
        // 'request' => $request, // Atau kirim utuh object request-nya
    ]);
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

