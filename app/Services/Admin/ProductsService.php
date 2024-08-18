<?php

namespace App\Services\Admin;

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

    public function createProduct($request): void {
        $newProduct              = new Product();
        $newProduct->title       = $request['title'];
        $newProduct->description = $request['description'];
        $newProduct->price       = $request['price'];
        $newProduct->save();
    }

    public function updateProduct($request): void {
        Product::where(['id'=>$request['id']])->update($request);
    }

    public function deleteProduct($id): void {
        Product::destroy($id);
    }

    public function getProductById($id): object {
        return Product::where(['id' => $id])->first();
    }

}
