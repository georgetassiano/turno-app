<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface ExpenseRepository.
 */
interface ExpenseRepository extends RepositoryInterface
{
    public function getExpensesInMonthAndYearByUserAuthenticate(int $month, int $year): Collection;

    public function datesToFilter(int $userId) : Collection;
}
