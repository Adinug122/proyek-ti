<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function print(Table $table)
{
    return view('tables.print', compact('table'));
}
}
