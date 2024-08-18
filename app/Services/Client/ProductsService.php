<?php

namespace App\Services\Client;

use App\Models\Product;

class ProductsService
{
    const PER_PAGE = 6;

    public function getProducts($page): array {
        $products = Product::skip(($page - 1) * self::PER_PAGE)
            ->take(self::PER_PAGE)
            ->get();

        $totalProducts = Product::all()->count();


        $countPages = ceil($totalProducts / self::PER_PAGE);

        return ['products' => $products, 'countPages' => $countPages];
    }

    public function getProductById($id): object {
        return Product::where(['id' => $id])->first();
    }

}
