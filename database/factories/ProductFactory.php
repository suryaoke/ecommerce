<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->words(rand(2, 4), true);
        $conditions = ['new', 'second'];

        return [
            'id' => Str::uuid()->toString(),
            'user_id' => User::factory()->hasAttached(
                config('permission.models.role')::where('name', 'store')->first(),
                [],
                'roles'
            ),
            'product_category_id' => ProductCategory::inRandomOrder()->first()->id,
            'name' => $name,
            'slug' => Str::slug($name) . '-' . uniqid(),
            'description' => $this->faker->paragraphs(rand(2, 4), true),
            'condition' => $this->faker->randomElement($conditions),
            'price' => $this->faker->randomFloat(2, 10000, 10000000),
            'weight' => $this->faker->randomFloat(2, 0.1, 10),
            'stock' => $this->faker->numberBetween(1, 100),
        ];
    }
}
