<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
        public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

    public function index()
    {
        $orders = Order::with([
            'items.product.sizes',
            'items.productSize',
            'user'
        ])
        ->where('user_id', auth()->id())
        ->latest()
        ->get();


        return view('orders.index', compact('orders'));
    }

}
