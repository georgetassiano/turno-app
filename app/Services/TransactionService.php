<?php

namespace App\Services;

use App\Repositories\TransactionRepository;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class TransactionService extends BaseService implements TransactionServiceInterface
{
    private TransactionRepository $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository) {
        $this->transactionRepository = $transactionRepository;
    }

    public function index(Carbon $date) : Collection {
        $accountId = auth()->user()->account->id;
        return $this->transactionRepository->getTransactionsInMonthAndYearByAccountWithTransactable($date->month, $date->year, $accountId);
    }

    public function store(array $data) : void {
        $this->transactionRepository->create($data);
    }
}
