<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

class ArticlesController extends Controller
{
    public function show(Product $producto){

        return $producto ;
        //$producto= \App\Product::findOrFail($id);

}
}
