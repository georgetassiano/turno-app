<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface TransactionRepository.
 */
interface TransactionRepository extends RepositoryInterface
{
    public function getTransactionsInMonthAndYearByAccountWithTransactable(int $month, int $year, int $accountId): Collection;

    public function datesToFilter(int $accountId) : Collection;
}
