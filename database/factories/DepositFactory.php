<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class DepositFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'amount' => $this->faker->randomFloat(2),
            'user_id' => User::factory(),
            'description' => $this->faker->sentence(),
            'file_url' => $this->faker->imageUrl(),
            'status' => $this->faker->randomElement(['pending', 'rejected', 'approved'])
        ];
    }

    /**
     * Indicate that the deposit is pending.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function pending(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending'
            ];
        });
    }

    /**
     * Indicate that the deposit is canceled.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function rejected(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'rejected'
            ];
        });
    }

    /**
     * Indicate that the deposit is approved.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function approved(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'approved'
            ];
        });
    }

}
