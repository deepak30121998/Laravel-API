<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChartResource;
use App\Models\Order;
use Gate;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function chart()
    {
        Gate::authorize('view', 'orders');
        $orders = Order::query()
                ->join('order_items', 'orders.id', '=', 'order_items.order_id')
                ->selectRaw("DATE_FORMAT(orders.created_at, '%Y-%m-%d') as Date, SUM(order_items.quantity*order_items.price) as sum")
                ->groupBy('Date')
                ->get();

        
        return ChartResource::collection($orders);
    }
}
