<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;

interface DepositServiceInterface extends BaseServiceInterface
{
    public function index(Carbon $date): Collection;

    public function store(array $data, int $userId): void;

    public function getDepositsPending(): Collection;

    public function acceptOrRejectDeposit($depositId, $status): void;
}
