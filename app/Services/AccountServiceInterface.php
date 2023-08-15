<?php

namespace App\Services;

use App\Models\Account;

interface AccountServiceInterface extends BaseServiceInterface
{
    public function createAccountForUserId(int $userId);

    public function getBalanceByUserId(int $userId): float;

    public function debitAmount(float $amount, int $userId);

    public function creditAmount(float $amount, int $userId);

    public function getAccountByUserId(int $userId): Account;
}
