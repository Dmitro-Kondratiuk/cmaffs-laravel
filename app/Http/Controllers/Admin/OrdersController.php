<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Services\Admin\OrdersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    private OrdersService $OrderService;

    public function __construct() {
        $this->OrderService = new OrdersService();
    }

    public function newOrder(): JsonResponse {
        $data = $this->OrderService->newOrder();

        return response()->json($data);
    }

    public function createOrder(OrderRequest $request): JsonResponse {
        $data     = $request->all();
        $response = [];
        try {
            $this->OrderService->createOrder($data);
        }
        catch (\Exception) {
            $response['error'] = 'Order not created';

            return response()->json($response, 422);
        }
        $response['message'] = 'Order created';

        return response()->json($response);
    }

    public function updateOrder(Request $request): JsonResponse {
        $data     = $request->all();
        $response = [];
        try {
            $this->OrderService->updateOrder($data);
        }
        catch (\Exception) {
            $response['error'] = 'Order not updated';
        }
        $response['message'] = 'Order updated';

        return response()->json($response);
    }

    public function deleteOrder(Request $request): JsonResponse {
        $data     = $request->all();
        $response = [];
        try {
            $this->OrderService->deleteOrder($data['id']);
        }
        catch (\Exception) {
            $response['error'] = 'Order not deleted';
        }
        $response['message'] = 'Order deleted';

        return response()->json($response);
    }

    public function getOrders(): JsonResponse {
        $orders = $this->OrderService->getOrders();

        return response()->json($orders);
    }

    public function getOrder($id): JsonResponse {


        $order = $this->OrderService->getOrderById((int)$id);

        return response()->json($order);
    }
}
