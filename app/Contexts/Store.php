<?php

namespace App\Contexts;

use App\Models\Store as StoreModel;
use App\Contexts\Product as ProductContext;
use App\Contexts\Utils;
use Illuminate\Support\Facades\DB;

class Store
{
    public function get($store_hash)
    {
        $store = StoreModel::get_by_store_hash($store_hash);
        $products = $store->products()->get();

        $products_response = [];
        foreach ($products as $product) {
            $products_response[] = [
                'product_name' => $product->name,
                'product_hash' => $product->product_hash,
                'stock' => $product->stock
            ];
        }

        return [
            'store_name' => $store->name,
            'store_hash' => $store->store_hash,
            'products' => $products_response
        ];
    }

    public function get_all()
    {
        $stores = StoreModel::all();
        $stores_response = [];
        foreach ($stores as $store) {
            $stores_response[] = [
                'store_name' => $store->name,
                'store_hash' => $store->store_hash,
                'stock' => ProductContext::get_total_stock_by_store_id($store->id)
            ];
        }

        return $stores_response;
    }

    public function create(string $store_name, array $products = [])
    {
        try {
            DB::beginTransaction();

            $store_model = new StoreModel();
            $store_model->name = $store_name;
            $store_model->store_hash = Utils::generate_hash();

            $store_model->save();

            foreach ($products as $product) {
                $product_model = new ProductContext();
                $product_model->create($product['name'], $product['stock'], $store_model->id);
            }

            DB::commit();
            return $store_model->store_hash;
        } catch(\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function update(string $store_hash, $store_name, array $products = [])
    {
        try {
            DB::beginTransaction();

            $store_model = StoreModel::get_by_store_hash($store_hash);
            $store_model->name = $store_name;

            $store_model->save();

            foreach ($products as $product) {
                $product_model = new ProductContext();
                if (!array_key_exists('stock', $product)) {
                    $product['stock'] = null;
                }
                if (!array_key_exists('name', $product)) {
                    $product['name'] = null;
                }
                $product_model->update($product['name'], $product['stock'], $product['product_hash']);
            }

            DB::commit();
        } catch(\Exception $e) {
            DB::rollback();
        }
    }

    public function delete($store_hash)
    {
        $store_model = StoreModel::get_by_store_hash($store_hash);
        return $store_model->delete();
    }
}
