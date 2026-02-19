<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/menu/{category}', function ($category) {

    $menus = ['food', 'drinks', 'snack'];
    if (!in_array($category, $menus)) {
        abort(404);
    }

    return view('menu', [
        'category' => $category,
        'type'     => request('type'),  
        'table'    => request('table')
    ]);
});

Route::get('/menu/{category}', function ($category, Illuminate\Http\Request $request) {

    $menus = [
        'food' => [
            ['name' => 'Fried Rice', 'price' => '20000', 'img' => 'food1.png'],
            ['name' => 'Beef Teriyaki', 'price' => '20000', 'img' => 'food2.png'],
            ['name' => 'Crushed Chicken Rice', 'price' => '20000', 'img' => 'food3.png'],
            ['name' => 'Grilled Chicken Rice', 'price' => '20000', 'img' => 'food4.png'],
        ],
        'drinks' => [
            ['name' => 'Moccacino', 'price' => '20000', 'img' => 'drinks1.png'],
            ['name' => 'Latte Coffe', 'price' => '20000', 'img' => 'drinks2.png'],
            ['name' => 'Americano', 'price' => '20000', 'img' => 'drinks3.png'],
            ['name' => 'Matcha', 'price' => '20000', 'img' => 'drinks4.png'],
        ],
        'snack' => [
            ['name' => 'Snack Platter', 'price' => '25000', 'img' => 'snack1.png'],
            ['name' => 'French Fries', 'price' => '16000', 'img' => 'snack2.png'],
            ['name' => 'Onion Ring', 'price' => '15000', 'img' => 'snack3.png'],
            ['name' => 'Choco Lava Cake', 'price' => '20000', 'img' => 'snack4.png'],
        ]
    ];

    if (!isset($menus[$category])) {
        abort(404);
    }

    return view('menu', [
        'category' => $category,
        'menus' => $menus,
        'type' => $request->type,
        'table' => $request->table
    ]);
});


Route::get('/menu/{category}/{index}', function ($category, $index) {

    $menus = [
        'food' => [
            ['name' => 'Fried Rice', 'price' => '20000', 'img' => 'food1.png'],
            ['name' => 'Beef Teriyaki', 'price' => '20000', 'img' => 'food2.png'],
            ['name' => 'Crushed Chicken Rice', 'price' => '20000', 'img' => 'food3.png'],
            ['name' => 'Grilled Chicken Rice', 'price' => '20000', 'img' => 'food4.png'],
        ],
        'drinks' => [
            ['name' => 'Moccacino', 'price' => '20000', 'img' => 'drinks1.png'],
            ['name' => 'Latte Coffe', 'price' => '20000', 'img' => 'drinks2.png'],
            ['name' => 'Americano', 'price' => '20000', 'img' => 'drinks3.png'],
            ['name' => 'Matcha', 'price' => '20000', 'img' => 'drinks4.png'],
        ],
        'snack' => [
            ['name' => 'Snack Platter', 'price' => '25000', 'img' => 'snack1.png'],
            ['name' => 'French Fries', 'price' => '16000', 'img' => 'snack2.png'],
            ['name' => 'Onion Ring', 'price' => '15000', 'img' => 'snack3.png'],
            ['name' => 'Choco Lava Cake', 'price' => '20000', 'img' => 'snack4.png'],
        ]
    ];

    if (!isset($menus[$category]) || !isset($menus[$category][$index])) {
        abort(404);
    }

    $item = $menus[$category][$index];

    return view('detail', compact('item', 'category'));
});



use Illuminate\Http\Request;

Route::get('/menu/{category}', function ($category, Request $request) {

    $menus = config('menus.data'); // nanti kita rapikan di config

    return view('menu', [
        'category' => $category,
        'menus' => $menus,
        'type' => $request->type,
        'table' => $request->table
    ]);
});

Route::get('/menu/{category}/{id}', function ($category, $id, Request $request) {

    $menus = config('menus.data');
    $item = $menus[$category][$id];

    return view('detail', compact('item','category','id'));
});

Route::post('/add-to-cart', function (Request $request) {

    $cart = session()->get('cart', []);

    $id = uniqid();

    $cart[$id] = [
        'name' => $request->name,
        'price' => $request->price,
        'qty' => $request->qty,
        'spicy' => $request->spicy
    ];

    session()->put('cart', $cart);

    return redirect()->back();
});

Route::get('/order', function () {
    return view('order');
});


Route::get('/order', function (Illuminate\Http\Request $request) {
    return view('order', [
        'type' => $request->type,
        'table' => $request->table
    ]);
});


