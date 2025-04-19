<?php 

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum'); 
    }

    /**
     * Foydalanuvchining barcha orderlari
     */
    public function index( $id)
    {
        $orders = Order::with('book')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return OrderResource::collection($orders);
    }

    /**
     * Order yaratish - versiya 2 (kitoblar bilan)
     */
    public function store(Request $request)
    {
        $request->validate([
            'total_price' => 'required|numeric',
            'books' => 'required|array',
            'books.*.id' => 'required|exists:books,id',
            'books.*.qty' => 'required|integer|min:1',
        ]);

        $user = auth()->user();

        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => $request->total_price,
            'status' => 'pending',
        ]);

        foreach ($request->books as $book) {
            $order->books()->attach($book['id'], ['quantity' => $book['qty']]);
        }

        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new NewOrderNotification($order));

        return response()->json([
            'message' => __('Order placed successfully.'),
            'data' => new OrderResource($order)
        ], 201);
    }

    /**
     * Orderni ko‘rsatish
     */
    public function show($id)
    {
        $order = Order::with('books')->where('user_id', auth()->id())->findOrFail($id);
        return new OrderResource($order);
    }

    /**
     * Auth bo‘lgan userning barcha orderlari
     */
    public function userOrders()
    {
        return auth()->user()->orders()->with('books')->get();
    }

    /**
     * Barcha orderlar – faqat admin uchun
     */
    public function allOrders()
    {
        $this->authorize('admin'); // yoki middleware('can:admin')
        return Order::with('user', 'books')->get();
    }
}
