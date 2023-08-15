<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use Prettus\Repository\Contracts\RepositoryInterface;
use App\Models\Check;

/**
 * Interface CheckRepository.
 */
interface CheckRepository extends RepositoryInterface
{
    public function getChecksInMonthAndYearByUserAuthenticate(string $month, string $year): Collection;

    public function getPendingChecks(): Collection;

    public function getPendingCheckById(int $checkId) : ?Check;

    public function datesToFilter(int $userId) : Collection;
}
