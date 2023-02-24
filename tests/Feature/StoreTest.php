<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Database\Seeders\StoreSeeder;

class StoreTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;

    protected $body_creation_store_request = [
        "name" => "store_test",
        "products" => [
            [
                "name" => "product_test_1",
                "stock" => 5
            ],
            [
                "name" => "product_test_2",
                "stock" => 15
            ]
        ]
    ];

    /** @test */
    public function get_all_stores_test()
    {
        $this->seed(StoreSeeder::class);

        $response = $this->get_all_stores();

        $response->assertStatus(200);
    }

    /** @test */
    public function create_store_test()
    {
        $response = $this->create_store();
        $response->assertStatus(201);
    }

    /** @test */
    public function get_store_test()
    {
        $this->seed(StoreSeeder::class);
        $response = $this->get_first_store_detailed_in_db();
        $response->assertStatus(200);
    }

    /** @test */
    public function update_store_test_without_products()
    {
        $this->seed(StoreSeeder::class);

        $selected_store = $this->get_first_store_detailed_in_db();
        $store_hash = $selected_store->getData()->store_hash;

        $response_body = ["name" => "UPDATED"];
        $response =$this->update_store($store_hash, $response_body);

        $response->assertStatus(200);
    }

    /** @test */
    public function update_store_test_with_valid_product()
    {
        $this->seed(StoreSeeder::class);

        $selected_store = $this->get_first_store_detailed_in_db();
        $store_hash = $selected_store->getData()->store_hash;
        $first_product_hash = $selected_store->getData()->products[0]->product_hash;

        $response_body = [
            "name" => "UPDATED",
            "products" => [
                [
                    "name" => "product UPDATED",
                    "product_hash" => $first_product_hash
                ]
            ]
        ];
        $response =$this->update_store($store_hash, $response_body);

        $response->assertStatus(200);
    }

    /** @test */
    public function update_store_test_with_invalid_product()
    {
        $this->seed(StoreSeeder::class);

        $selected_store = $this->get_first_store_detailed_in_db();
        $store_hash = $selected_store->getData()->store_hash;
        $first_product_hash = "invalid_product_hash";

        $response_body = [
            "name" => "UPDATED",
            "products" => [
                [
                    "name" => "product UPDATED",
                    "product_hash" => $first_product_hash
                ]
            ]
        ];
        $response =$this->update_store($store_hash, $response_body);

        $response->assertStatus(422);
    }

    /** @test */
    public function update_store_test_with_invalid_store()
    {
        $this->seed(StoreSeeder::class);

        $store_hash = "invalid_store_hash";
        $first_product_hash = "invalid_product_hash";

        $response_body = [
            "name" => "UPDATED",
            "products" => [
                [
                    "name" => "product UPDATED",
                    "product_hash" => $first_product_hash
                ]
            ]
        ];
        $response =$this->update_store($store_hash, $response_body);

        $response->assertStatus(422);
    }

    /** @test */
    public function delete_store_successfully_test() 
    {
        $this->seed(StoreSeeder::class);

        $selected_store = $this->get_first_store_detailed_in_db();
        $store_hash = $selected_store->getData()->store_hash;

        $response = $this->delete_store($store_hash);

        $response->assertStatus(200);
    }

    /** @test */
    public function delete_invalid_store_test() 
    {
        $this->seed(StoreSeeder::class);

        $store_hash = "invalid_store_hash";

        $response = $this->delete_store($store_hash);

        $response->assertStatus(422);
    }

    private function create_store()
    {
        return $this->json('POST', '/api/stores', $this->body_creation_store_request);
    }

    private function get_all_stores()
    {
        return $this->get('/api/stores');
    }

    private function get_store($store_hash)
    {
        return $this->get("/api/stores/{$store_hash}");
    }

    private function update_store($store_hash, $body)
    {
        return $this->json('PUT', "/api/stores/{$store_hash}", $body);
    }

    private function delete_store($store_hash) 
    {
        return $this->delete("/api/stores/{$store_hash}");
    }

    private function get_first_store_detailed_in_db()
    {
        $stores = $this->get_all_stores();
        $selected_store = $stores->getData()[0];

        return $this->get_store($selected_store->store_hash);
    }
}
