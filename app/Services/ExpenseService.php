<?php

namespace App\Services;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use App\Repositories\ExpenseRepository;
use Illuminate\Support\Collection;
use App\Services\AccountServiceInterface;
use App\Services\TransactionServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class ExpenseService extends BaseService implements ExpenseServiceInterface
{
    private ExpenseRepository $expenseRepository;
    private AccountServiceInterface $accountService;
    private TransactionServiceInterface $transactionService;

    public function __construct(ExpenseRepository $expenseRepository, AccountServiceInterface $accountService, TransactionServiceInterface $transactionService) {
        $this->expenseRepository = $expenseRepository;
        $this->accountService = $accountService;
        $this->transactionService = $transactionService;
    }

    public function index(Carbon $date) : Collection {
        return $this->expenseRepository->getExpensesInMonthAndYearByUserAuthenticate($date->month, $date->year);
    }

    public function store(array $data) : void {
        DB::transaction(function () use ($data){
            $this->accountService->debitAmount($data['amount']);
            $dataExpense= Arr::add($data, 'user_id', auth()->user()->id);
            $expense = $this->expenseRepository->create($dataExpense);
            $dataTransaction = [
                'transactable_type' => 'expense',
                'transactable_id' => $expense->id,
                'account_id' => auth()->user()->account->id
            ];
            $this->transactionService->store($dataTransaction);
        });
    }
}
