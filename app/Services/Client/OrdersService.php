<?php

namespace App\Services\Client;

use App\Models\Order;
use App\Services\StatusOrders;

class OrdersService
{
    public function createOrder( $product_id): void {
        $user = auth()->user();
        $order             = new Order();
        $order->product_id = $product_id;
        $order->client_id  = $user->id;
        $order->status     = StatusOrders::PENDING;
        $order->save();
    }

    public function cancellationOfTheOrder($id): array {
        $user     = auth()->user();
        $order    = Order::where(['id' => $id, 'client_id'=>$user->id])->first();
        $response = [];
        if ($order) {
            $order->status = StatusOrders::REFUSED;
            $order->save();
            $response['message'] = 'Successful cancellation of the order';
        }
        else {
            $response['error'] = 'order not found';

        }

        return $response;
    }

    public function showOrders() {
        $user = auth()->user();
        $orders = Order::where('client_id', $user->id)
            ->with('product')
            ->get();

        // Форматуємо дані для повернення
        $orders = $orders->map(function ($order) {
            return [
                'id' => $order->id,
                'product_title' => $order->product->title ?? 'N/A',
                'status' => $order->status,
            ];
        });
        return $orders;
    }
}
