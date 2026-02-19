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
