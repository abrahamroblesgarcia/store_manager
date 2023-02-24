<?php

namespace App\Contexts;

use App\Models\Product as ProductModel;
use App\Contexts\Utils;

class Product
{
    public function create(string $product_name, int $stock, int $store_id)
    {
        $product = new ProductModel();
        $product->name = $product_name;
        $product->stock = $stock;
        $product->product_hash = Utils::generate_hash();
        $product->store_id = $store_id;

        $product->save();
    }

    public function update($product_name, $stock, $product_hash)
    {
        $product = ProductModel::get_by_product_hash($product_hash);
        if (!is_null($product_name)) {
            $product->name = $product_name;
        }
        if (!is_null($stock)) {
            $product->stock = $stock;
        }

        return $product->save();
    }

    public static function get_total_stock_by_store_id(int $store_id)
    {
        $products = ProductModel::get_all_by_store($store_id);

        $total_stock = 0;
        foreach ($products as $product) {
            $total_stock += $product->stock;
        }

        return $total_stock;
    }

    public function is_product_of_store($product_hash, $store_hash)
    {
        $product = ProductModel::get_by_product_hash($product_hash);
        if (!is_null($product)) {
            return $product->store()->store_hash === $store_hash;
        }

        return false;
    }

    public function sell($product_hash, $units_to_sell)
    {
        $product = ProductModel::get_by_product_hash($product_hash);
        $stock_result = $product->stock - $units_to_sell;
        if ($stock_result < 0) {
            throw new \Exception('Not enough stock for the requested product');
        } elseif ($stock_result < 5) {
            $sell_msg = 'Product successfully sold, but stock is now less than 5 units.';
        } else {
            $sell_msg = 'Product successfully sold';
        }

        $product->stock = $stock_result;
        $product->save();

        return $sell_msg;
    }
}
