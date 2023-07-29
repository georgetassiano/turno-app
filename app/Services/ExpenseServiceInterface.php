<?php

namespace App\Services;
use Carbon\Carbon;
use Illuminate\Support\Collection;

interface ExpenseServiceInterface extends BaseServiceInterface {
    public function index(Carbon $date) : Collection;

    public function store(array $data) : void;
}
