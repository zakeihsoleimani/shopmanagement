<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
 public function products($category)
{
    return \App\Models\Product::where('category_id', $category)
        ->select('id', 'name', 'price')
        ->get();
}
}
