<?php

namespace App\Services\Admin;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\StatusOrders;

class OrdersService
{
    public function newOrder(): array {
        $response             = [];
        $response['users']    = User::all()->select(['id', 'name']);
        $response['products'] = Product::all()->select(['id', 'title']);

        return $response;
    }

    public function createOrder($data): void {
        $newOrder             = new Order();
        $newOrder->product_id = $data['product_id'];
        $newOrder->client_id  = $data['user_id'];
        $newOrder->status     = StatusOrders::PENDING;
        $newOrder->save();
    }

    public function updateOrder($data): void {
        $data['updated_at'] = now();
        if (isset($data['id'])) {
            Order::where('id', $data['id'])->update($data);
        }
        else {
            throw new \Exception('ID not provided');
        }
    }

    public function deleteOrder($id): void {
        Order::where('id', $id)->delete();
    }

    public function getOrders() {

        $orders = Order::with('product')
            ->get();

        // Форматуємо дані  повернення
        $orders = $orders->map(function ($order) {
            return [
                'id' => $order->id,
                'product_title' => $order->product->title ?? 'N/A',
                'status' => $order->status,
            ];
        });
        return $orders;
    }

    public function getOrderById($id): array {
        $response['statuses'] = array_values(StatusOrders::statuses());
        $order = Order::where(['id' =>$id])
            ->with('product')
            ->get();

        // Форматуємо дані для повернення
        $order = $order->map(function ($order) {
            return [
                'id' => $order->id,
                'product_title' => $order->product->title ?? 'N/A',
                'status' => $order->status,
            ];
        });

        $response['order'] = $order;
        return $response;
    }
}
