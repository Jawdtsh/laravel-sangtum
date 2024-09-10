<?php
namespace App\Http\Controllers;

use App\Http\Requests\QuantityOrderRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Notifications\DeleteOrderNotification;
use App\Notifications\OrderCreatedNotification;
use App\Services\OrderService;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    protected OrderService $order;
    protected ProductService $product;
    public function __construct(OrderService $order, ProductService $product){
        $this->order = $order;
        $this->product = $product;
    }


    public function index(Request $request): JsonResponse
    {
        $orders =$this->order->getOrder(Auth::user(),$request);
        return response()->json($orders);
    }



    public function store(StoreOrderRequest $request): JsonResponse
    {
        $user = auth()->user()->getAuthIdentifier();

        $validatedData = $request->validated();

        DB::transaction(function () use ($validatedData, $user) {
            $product = $this->product->getProductWithCheckOnQuantity($validatedData);
            $this->order->createOrder($validatedData,$user,$product);
        });


        return response()->json(['message' => 'Order placed successfully']);
    }



    public function update(QuantityOrderRequest $request, Order $order): JsonResponse
    {
        $validatedData = $request->validated();
        DB::transaction(function () use ($validatedData, $order) {
            $this->order->updateOrders($validatedData,$order);
        });
        return response()->json(['message' => 'Order updated successfully']);
    }

    public function destroy(Order $order): JsonResponse
    {
        $user=Auth::user();
        DB::transaction(function () use ($order) {
            if ($order->status === 'pending') {
                $product = $order->product;
                $product->increment('quantity', $order->quantity);
            }
            $order->delete();
        });
        $user->notify(new DeleteOrderNotification($order));
        return response()->json(['message' => 'Order deleted successfully']);
    }
}
