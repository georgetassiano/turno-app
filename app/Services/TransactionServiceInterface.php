<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Collection;
use Carbon\Carbon;

interface TransactionServiceInterface extends BaseServiceInterface {
    public function index(Carbon $date) : Collection;

    public function store(array $data) : void;
}
