<?php

namespace App\Services;

use App\Repositories\TransactionRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Services\AccountServiceInterface;
use App\Traits\InsertDateNowTrait;

class TransactionService extends BaseService implements TransactionServiceInterface
{
    use InsertDateNowTrait;
    private TransactionRepository $transactionRepository;
    private AccountServiceInterface $accountService;

    public function __construct(TransactionRepository $transactionRepository, AccountServiceInterface $accountService)
    {
        $this->transactionRepository = $transactionRepository;
        $this->accountService = $accountService;
    }

    /**
     * get transactions in month and year by account
     * @param  Carbon  $date
     * @param  int  $userId
     * @return Collection
     */
    public function index(Carbon $date, int $userId): Collection
    {
        $account = $this->accountService->getAccountByUserId($userId);

        return $this->transactionRepository->getTransactionsInMonthAndYearByAccountWithTransactable($date->month, $date->year, $account->id);
    }

    /**
     * store transaction
     * @param  array  $data
     */
    public function store(array $data)
    {
        $this->transactionRepository->create($data);
    }

    /**
     * get dates to filter
     * @param  int  $userId
     * @return Collection
     */
    public function datesToFilter(int $userId): Collection {
        $account = $this->accountService->getAccountByUserId($userId);
        $dates = $this->transactionRepository->datesToFilter($account->id);
        $formattedDates = $dates->map(function ($date) {
            return Carbon::createFromDate($date->year, $date->month)->format('Y-m');
        });
        $this->insertDateNowIfNotExist($formattedDates);
        return $formattedDates;
    }
}
