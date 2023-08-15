<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Models\Check;

interface CheckServiceInterface extends BaseServiceInterface
{
    public function index(Carbon $date): Collection;

    public function store(array $data, int $userId);

    public function getPendingChecks(): Collection;

    public function getPendingCheckById(int $checkId) : Check;

    public function acceptOrRejectCheck(int $checkId, string $status);

    public function datesToFilter(int $userId): Collection;
}
