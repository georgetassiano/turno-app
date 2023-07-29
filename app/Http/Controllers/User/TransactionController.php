<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TransactionServiceInterface;
use App\Http\Resources\TransactionCollection;
use App\Http\Requests\FilterByMonthAndYearRequest;

class TransactionController extends Controller
{
    private TransactionServiceInterface $transactionService;

    public function __construct(TransactionServiceInterface $transactionService) {
        $this->transactionService = $transactionService;
    }

    public function index(FilterByMonthAndYearRequest $request) {
        $transactions = $this->transactionService->index($request->date('year-month'));
        return new TransactionCollection($transactions);
    }
}
