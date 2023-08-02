<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface DepositRepository.
 */
interface DepositRepository extends RepositoryInterface
{
    public function getDepositsInMonthAndYearByUserAuthenticate($month, $year): Collection;

    public function getDepositsPending(): Collection;
}
