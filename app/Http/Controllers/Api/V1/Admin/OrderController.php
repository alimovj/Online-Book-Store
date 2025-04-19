<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:admin']);
    }

    /**
     * Barcha orderlarni ko‘rish (admin uchun)
     */
    public function index(Request $request)
    {
        $orders = Order::with(['book', 'user'])
            ->latest()
            ->paginate(10);

        return OrderResource::collection($orders);
    }

    /**
     * Orderni statusini o‘zgartirish (admin tomonidan)
     */
    public function updateStatus(UpdateOrderStatusRequest $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return response()->json([
            'message' => __('Order status updated successfully.'),
            'data' => new OrderResource($order)
        ]);
    }

    /**
     * Orderni ko‘rish (admin tomonidan)
     */
    public function show($id)
    {
        $order = Order::with(['book', 'user'])->findOrFail($id);
        return new OrderResource($order);
    }
}
