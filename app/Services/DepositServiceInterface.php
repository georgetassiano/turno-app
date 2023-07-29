<?php

namespace App\Services;

interface DepositServiceInterface extends BaseServiceInterface {
    public function index(Carbon $date) : Collection;
}
