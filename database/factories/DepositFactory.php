<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'file_path' => $this->faker->imageUrl(),
            'status' => $this->faker->randomElement(['pending', 'rejected', 'approved']),
        ];
    }

    /**
     * Indicate that the deposit is pending.
     */
    public function pending(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending',
            ];
        });
    }

    /**
     * Indicate that the deposit is canceled.
     */
    public function rejected(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'rejected',
            ];
        });
    }

    /**
     * Indicate that the deposit is approved.
     */
    public function approved(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'approved',
            ];
        });
    }

    public function rejectedOrApproved(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => $this->faker->randomElement(['rejected', 'approved']),
            ];
        });
    }
}
