<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Account;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->count(100)->has(Account::factory()->hasTransactions(10))->create();
        User::factory()->has(Account::factory()->hasTransactions(10))->create([
            'email' => 'george.melo7@gmail.com'
        ]);
        DB::table('admins')->insert([
            'name' => 'admin',
            'email' => 'george.melo7@gmail.com',
            'password' => Hash::make('secret'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
