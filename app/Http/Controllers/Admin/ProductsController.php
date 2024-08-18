<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\ProductsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    private ProductsService $ProductsService;

    public function __construct() {
        $this->ProductsService = new ProductsService();
    }

    public function store(Request $request): JsonResponse {
        $page     = $request->query('page', 1);
        $products = $this->ProductsService->getProducts($page);

        return response()->json($products);
    }

    public function create(Request $request): JsonResponse {
        $data     = $request->all();
        $response = [];
        try {
            $this->ProductsService->createProduct($data);
        }
        catch (\Exception) {
            $response['error'] = 'Product already exists';

            return response()->json($response, 422);
        }
        $response['message'] = 'Product created';

        return response()->json($response);
    }

    public function update(Request $request): JsonResponse {
        $data     = $request->all();
        $response = [];
        try {
            $this->ProductsService->updateProduct($data);

        }
        catch (\Exception) {
            $response['error'] = 'Product already exists';

            return response()->json($response, 422);
        }
        $response['message'] = 'Product updated';

        return response()->json($response);
    }

    public function delete(Request $request): JsonResponse {
        $response = [];
        $data     = $request->all();
        try {
            $this->ProductsService->deleteProduct($data);
        }
        catch (\Exception) {
            $response['error'] = 'Product already exists';

            return response()->json($response, 422);
        }
        $response['message'] = 'Product deleted';

        return response()->json($response);
    }

    public function show($id): JsonResponse {
        $response = [];
        try {
            $product = $this->ProductsService->getProductById($id);
        }
        catch (\Exception) {
            $response['error'] = 'Product not found';

            return response()->json($response, 404);
        }

        return response()->json($product);

    }
}
