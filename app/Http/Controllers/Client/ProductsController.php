<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\Client\ProductsService;
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
