<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterByMonthAndYearRequest;
use App\Http\Resources\TransactionCollection;
use App\Services\TransactionServiceInterface;

class TransactionController extends Controller
{
    private TransactionServiceInterface $transactionService;

    public function __construct(TransactionServiceInterface $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * get transactions in month and year by account
     */
    public function index(FilterByMonthAndYearRequest $request): TransactionCollection
    {
        throw_if(!auth()->user()->hasAnyPermission(['*'], 'web'), AuthorizationException::class);
        $transactions = $this->transactionService->index($request->date('year-month'), auth()->user()->id);
        return new TransactionCollection($transactions);
    }
}
