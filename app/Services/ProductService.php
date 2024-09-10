<?php

namespace App\Services;

use App\Models\Product;
use App\Traits\FileHandlerTrait;
use RuntimeException;

class ProductService
{
    use FileHandlerTrait;


    public function getProductWithCheckOnQuantity($data)
    {
        $product = Product::find($data['product_id']);
        if ($product->quantity < $data['quantity']) {
            throw new RuntimeException('Not enough product quantity');
        }
        return $product;
    }



}
