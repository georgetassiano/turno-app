<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\FilterByMonthAndYearRequest;
use App\Http\Resources\ExpenseResource;
use App\Services\ExpenseServiceInterface;
use App\Http\Requests\CreateExpenseRequest;

class ExpenseController extends Controller
{

    private ExpenseServiceInterface $expenseService;
    public function __construct(ExpenseServiceInterface $expenseService) {
        $this->expenseService = $expenseService;
    }

    public function index(FilterByMonthAndYearRequest $request) {
        $expenses = $this->expenseService->index($request->date('year-month'));
        return ExpenseResource::collection($expenses);
    }

    public function store(CreateExpenseRequest $request) {
        $data = $request->validated();
        $this->expenseService->store($data);
        return response()->json([
            'message' => 'User expense created successfully'
        ], 201);
    }
}
