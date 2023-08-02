<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->enum('transactable_type', ['deposit', 'expense']);
            $table->unsignedBigInteger('transactable_id');
            $table->foreignId('account_id')->constrained();
            $table->timestamps();
            $table->primary(['transactable_type', 'transactable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
