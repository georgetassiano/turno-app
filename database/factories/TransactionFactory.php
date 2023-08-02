<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Deposit;
use App\Models\Expense;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'transactable_type' => $this->faker->randomElement(['deposit', 'expense']),
            'transactable_id' => function (array $attributes) {
                $userId = Account::find($attributes['account_id'])->user_id;

                return $attributes['transactable_type'] === 'deposit'
                    ? Deposit::factory()->rejectedOrApproved()->create(['user_id' => $userId])
                    : Expense::factory()->create(['user_id' => $userId]);
            },
            'account_id' => Account::factory(),
        ];
    }
}
