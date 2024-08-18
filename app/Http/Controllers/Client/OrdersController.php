<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Services\Client\OrdersService;
use http\Env\Request;
use Illuminate\Http\JsonResponse;

class OrdersController extends Controller
{
    private OrdersService $OrderService;

    public function __construct() {
        $this->OrderService = new OrdersService();
    }

    public function createOrder(OrderRequest $request): JsonResponse {
        $product_id = $request->input('productId');
        $response   = [];
        try {
            $this->OrderService->createOrder($product_id);
        }
        catch (\Exception) {
            $response['error'] = 'Failed to create order.';

            return response()->json($response, 422);
        }
        $response['message'] = 'Order created successfully.';

        return response()->json($response);
    }

    public function cancellationOfTheOrder(\Illuminate\Http\Request $request): JsonResponse {

        $orderId    = $request->input('id');
        $statusCode = 200;
        $message    = $this->OrderService->cancellationOfTheOrder($orderId);
        if (isset($message['errors'])) {
            $response['error'] = $message['errors'];
            $statusCode        = 422;
        }
        else {
            $response['message'] = $message;
        }

        return response()->json($response, $statusCode);
    }

    public function showOrders(): JsonResponse {
        $orders = $this->OrderService->showOrders();

        return response()->json($orders);
    }
}
