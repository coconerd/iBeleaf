<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckOutController extends Controller
{
    public function showCheckOutForm()
    {
        return view('cart.checkout');
    }
}