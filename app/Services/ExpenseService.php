<?php

namespace App\Services;

use App\Repositories\ExpenseRepository;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ExpenseService extends BaseService implements ExpenseServiceInterface
{
    private ExpenseRepository $expenseRepository;

    private AccountServiceInterface $accountService;

    private TransactionServiceInterface $transactionService;

    public function __construct(ExpenseRepository $expenseRepository, AccountServiceInterface $accountService, TransactionServiceInterface $transactionService)
    {
        $this->expenseRepository = $expenseRepository;
        $this->accountService = $accountService;
        $this->transactionService = $transactionService;
    }

    /**
     * get expenses in month and year by user authenticate
     */
    public function index(Carbon $date): Collection
    {
        return $this->expenseRepository->getExpensesInMonthAndYearByUserAuthenticate($date->month, $date->year);
    }

    /**
     * store expense
     * @param  array  $data
     */
    public function store(array $data, int $userId): void
    {
        DB::transaction(function () use ($data, $userId) {
            $dataExpense = Arr::add($data, 'user_id', $userId);
            $expense = $this->expenseRepository->create($dataExpense);
            $this->accountService->debitAmount($data['amount'], $userId);
            $this->createTransactionFromExpense($expense, $userId);
        });
    }

    /**
     * create transaction from expense
     */
    public function createTransactionFromExpense($expense, int $userId): void
    {
        $account = $this->accountService->getAccountByUserId($userId);
        $dataTransaction = [
            'transactable_type' => 'expense',
            'transactable_id' => $expense->id,
            'account_id' => $account->id,
        ];
        $this->transactionService->store($dataTransaction);
    }
}
