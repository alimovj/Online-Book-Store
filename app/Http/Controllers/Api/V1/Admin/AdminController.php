<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Barcha buyurtmalar ro‘yxatini ko‘rsatadi (admin uchun)
    public function index():
    {
        $orders = Order::with('user', 'books')->latest()->paginate(10);

        return response()->json([
            'status' => true,
            'message' => 'Orders list',
            'data' => $orders
        ]);
    }

    // Buyurtma ma’lumotlarini yangilash (masalan: holatini o‘zgartirish)
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,cancelled,delivered',
        ]);

        $order->status = $request->status;
        $order->save();

        return response()->json([
            'status' => true,
            'message' => 'Order updated successfully',
            'data' => $order
        ]);
    }
}
