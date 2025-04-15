<?php 

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum'); // faqat login bo‘lgan foydalanuvchilar kiradi
    }

    /**
     * Foydalanuvchining barcha orderlarini chiqarish
     */
    public function index()
    {
        $orders = Order::with('book')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return OrderResource::collection($orders);
    }

    /**
     * Order yaratish
     */
    public function store(StoreOrderRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['status'] = 'pending';

        $order = Order::create($data);

        // ✅ Adminlarga notification yuborish
        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new NewOrderNotification($order));

        return response()->json([
            'message' => __('Order created successfully.'),
            'data' => new OrderResource($order)
        ], 201);
    }

    /**
     * Orderni ko‘rsatish
     */
    public function show($id)
    {
        $order = Order::with('book')->where('user_id', auth()->id())->findOrFail($id);
        return new OrderResource($order);
    }
}
