<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Basket;

class ViewsController extends Controller
{
    public function index()
    {
        return view("index", ['products' => Product::all()]);
    }

    public function login()
    {
        return view("pages.login");
    }

    public function basket()
    {
        return view("pages.basket", ['products' => Basket::where('user_id', Auth::id())->get()]);
    }
}
