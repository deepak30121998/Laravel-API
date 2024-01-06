<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::paginate();
        return OrderResource::collection($orders);
    }

    public function show($id)
    {
        return new OrderResource(Order::find($id));
    }
}
