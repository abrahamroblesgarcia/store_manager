<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Database\Seeders\StoreSeeder;

class ProductTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;

    /** @test */
    public function try_to_sell_product_with_invalid_store_hash()
    {
        $store_hash = "invalid_store_hash";
        $product_hash = "invalid_product_hash";
        $units_to_sell = 10;

        $response = $this->sell($store_hash, $product_hash, $units_to_sell);

        $response->assertStatus(422);
    }

    /** @test */
    public function try_to_sell_product_with_valid_product_hash_and_store_hash()
    {
        $this->seed(StoreSeeder::class);

        $selected_store = $this->get_first_store_detailed_in_db();
        $store_hash = $selected_store->getData()->store_hash;
        $product_hash = $selected_store->getData()->products[0]->product_hash;
        $stock = $selected_store->getData()->products[0]->stock;
        if ($stock > 0) {
            $units_to_sell = $stock - 1;
        } else {
            $units_to_sell = 0;
        }

        $response = $this->sell($store_hash, $product_hash, $units_to_sell);

        $response->assertStatus(200);
    }

    /** @test */
    public function try_to_sell_product_with_invalid_units_to_sell()
    {
        $this->seed(StoreSeeder::class);

        $selected_store = $this->get_first_store_detailed_in_db();
        $store_hash = $selected_store->getData()->store_hash;
        $product_hash = $selected_store->getData()->products[0]->product_hash;
        $stock = $selected_store->getData()->products[0]->stock;

        $units_to_sell = $stock + 1;

        $response = $this->sell($store_hash, $product_hash, $units_to_sell);

        $response->assertStatus(422);
    }

    private function sell($store_hash, $product_hash, $units_to_sell)
    {
        $body = ["units_to_sell" => $units_to_sell];
        return $this->json('POST', "/api/stores/{$store_hash}/{$product_hash}", $body);
    }

    private function get_all_stores()
    {
        return $this->get('/api/stores');
    }

    private function get_store($store_hash)
    {
        return $this->get("/api/stores/{$store_hash}");
    }

    private function get_first_store_detailed_in_db()
    {
        $stores = $this->get_all_stores();
        $selected_store = $stores->getData()[0];

        return $this->get_store($selected_store->store_hash);
    }
}
