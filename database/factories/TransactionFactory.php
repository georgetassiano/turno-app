<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Account;
use App\Models\Deposit;
use App\Models\Expense;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'model_type' => $this->faker->randomElement(['deposit', 'expense']),
            'model_id' => function (array $attributes) {
                $userId = Account::find($attributes['account_id'])->user_id;
                return $attributes['model_type'] === 'deposit'
                    ? Deposit::factory()->create(['user_id' => $userId])
                    : Expense::factory()->create(['user_id' => $userId]);
            },
            'account_id' => Account::factory()
        ];
    }
}
