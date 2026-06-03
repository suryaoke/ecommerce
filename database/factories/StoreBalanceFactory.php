<?php

namespace Database\Factories;

use App\Models\Store;
use App\Models\StoreBalance;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StoreBalance>
 */
class StoreBalanceFactory extends Factory
{
    protected $model = StoreBalance::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
             'id' => Str::uuid()->toString(),
            'store_id' => Store::factory(),
            'balance' => $this->faker->randomFloat(2, 0, 1000000)
            
        ];
    }
}
