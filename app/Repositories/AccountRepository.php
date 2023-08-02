<?php

namespace App\Repositories;

use App\Models\Account;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface AccountRepository.
 */
interface AccountRepository extends RepositoryInterface
{
    public function getBalanceByAccountId($accountId): Account;

    public function getAccountByUserId(int $userId): Account;
}
