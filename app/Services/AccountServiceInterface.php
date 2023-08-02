<?php

namespace App\Services;

use App\Models\Account;

interface AccountServiceInterface extends BaseServiceInterface
{
    public function createAccountForUserId(int $userId): void;

    public function getBalanceByUserId(int $userId): float;

    public function debitAmount(float $amount, int $userId): void;

    public function creditAmount(float $amount, int $userId): void;

    public function getAccountByUserId(int $userId): Account;
}
