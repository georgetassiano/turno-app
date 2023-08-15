<?php

namespace App\Services;

use App\Repositories\ExpenseRepository;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Traits\InsertDateNowTrait;
use App\Models\Expense;

class ExpenseService extends BaseService implements ExpenseServiceInterface
{
    use InsertDateNowTrait;

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
     * @param  Carbon  $date
     */
    public function index(Carbon $date): Collection
    {
        return $this->expenseRepository->getExpensesInMonthAndYearByUserAuthenticate($date->month, $date->year);
    }

    /**
     * store expense
     * @param  array  $data
     * @param  int  $userId
     */
    public function store(array $data, int $userId)
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
     * @param  Expense  $expense
     * @param  int  $userId
     */
    public function createTransactionFromExpense(Expense $expense, int $userId)
    {
        $account = $this->accountService->getAccountByUserId($userId);
        $dataTransaction = [
            'transactable_type' => 'expenses',
            'transactable_id' => $expense->id,
            'account_id' => $account->id,
        ];
        $this->transactionService->store($dataTransaction);
    }

    /**
     * get dates to filter
     * @param  int  $userId
     * @return Collection
     */
    public function datesToFilter(int $userId): Collection
    {
        $dates = $this->expenseRepository->datesToFilter($userId);
        $formattedDates = $dates->map(function ($date) {
            return Carbon::createFromDate($date->year, $date->month)->format('Y-m');
        });
        $this->insertDateNowIfNotExist($formattedDates);
        return $formattedDates;
    }
}
