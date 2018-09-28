<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index ($status, Request $request)
    {
        return view('orders.index', []);
    }

    public function show (Order $order)
    {
        return view('orders.show', []);
    }

    public function create ()
    {
        return view('orders.create_and_edit');
    }

    public function store (Request $request)
    {
        // TODO ...
    }

    public function edit (Order $order)
    {
        return view('orders.create_and_edit', []);
    }

    public function update (Order $order)
    {
        // TODO ...
    }

    public function destroy (Order $order)
    {
        // TODO ...
    }
}
