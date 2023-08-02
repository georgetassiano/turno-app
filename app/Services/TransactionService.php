<?php

namespace App\Services;

use App\Repositories\TransactionRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Services\AccountServiceInterface;

class TransactionService extends BaseService implements TransactionServiceInterface
{
    private TransactionRepository $transactionRepository;
    private AccountServiceInterface $accountService;

    public function __construct(TransactionRepository $transactionRepository, AccountServiceInterface $accountService)
    {
        $this->transactionRepository = $transactionRepository;
        $this->accountService = $accountService;
    }

    /**
     * get transactions in month and year by account
     */
    public function index(Carbon $date, int $userId): Collection
    {
        $account = $this->accountService->getAccountByUserId($userId);

        return $this->transactionRepository->getTransactionsInMonthAndYearByAccountWithTransactable($date->month, $date->year, $account->id);
    }

    /**
     * store transaction
     */
    public function store(array $data): void
    {
        $this->transactionRepository->create($data);
    }
}
