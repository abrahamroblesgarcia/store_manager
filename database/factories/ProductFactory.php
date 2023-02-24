<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Store;
use App\Contexts\Utils;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class ProductFactory extends Factory
{

    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'product_hash' => Utils::generate_hash(),
            'store_id' => Store::factory(),
            'stock' => $this->faker->numberBetween(0, 100)
        ];
    }
}
