<?php

namespace App\Services;

class DepositService extends BaseService implements DepositServiceInterface
{
    public function index(Carbon $date) : Collection {
        $accountId = auth()->user()->account->id;
        return $this->depositRepository->getDepositsInMonthAndYearByUserAuthenticate($date->month, $date->year, $accountId);
    }
}
