<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Support\Collection;

/**
 * Interface ExpenseRepository.
 *
 * @package namespace App\Repositories;
 */
interface ExpenseRepository extends RepositoryInterface
{
    public function getExpensesInMonthAndYearByUserAuthenticate(int $month, int $year) : Collection;
}
