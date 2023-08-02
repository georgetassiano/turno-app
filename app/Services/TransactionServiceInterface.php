<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;

interface TransactionServiceInterface extends BaseServiceInterface
{
    public function index(Carbon $date, int $userId): Collection;

    public function store(array $data): void;
}
