<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Support\Collection;

/**
 * Interface TransactionRepository.
 *
 * @package namespace App\Repositories;
 */
interface TransactionRepository extends RepositoryInterface
{
    public function getTransactionsInMonthAndYearByAccountWithTransactable(int $month, int $year, int $accountId) : Collection;
}
