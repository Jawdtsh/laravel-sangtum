<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Notifications\OrderCreatedNotification;
use App\Notifications\OrderStatusUpdatedNotification;
use App\Traits\FileHandlerTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class OrderService
{
    use FileHandlerTrait;

    public function createOrder( $data,$user,Product $product ): void
    {
        $existingOrder = Order::where('user_id', $user)
            ->where('product_id', $data['product_id'])
            ->where('status', 'pending')
            ->first();

        if ($existingOrder) {
            $existingOrder->quantity += $data['quantity'];
            $existingOrder->save();
        } else {

            $order =Order::create([
                'user_id' => $user,
                'product_id' => $data['product_id'],
                'quantity' => $data['quantity'],
                'price' => $product->price * $data['quantity'],
                'status' => 'pending',
            ]);
            $user->notify(new OrderCreatedNotification($order));
        }
        $product->decrement('quantity', $data['quantity']);

    }

    public function getOrder(User $user , $data)
    {
        return Order::where('user_id', $user->id)
            ->when($data->query('status'), function ($query, $status) {
                return $query->where('status', $status);
            }, function ($query) {
                return $query->orderBy('status');
            })
            ->get();
    }


    public function updateOrders($data, Order $order): JsonResponse
    {
        $user=Auth::user();
        if (!$order->status === 'pending') {
            return response()->json(['message' => 'Sorry, you cannot modify the order because the status of the order is not pending'], 400);
        }
        $product = $order->product;
        if (($product->quantity + $order->quantity) < $data->quantity) {
            return response()->json(['message' => 'Not enough product quantity available'], 400);
        }
        $product->increment('quantity', $order->quantity);
        $product->decrement('quantity', $data->quantity );
        $order->update([
            'quantity' => $data->quantity,
            'price' => $product->price * $data->quantity,
        ]);
        $user->notify(new OrderStatusUpdatedNotification($order));
        return response()->json(['message' => 'Order updated successfully']);
    }



}
