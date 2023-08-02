<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateExpenseRequest;
use App\Http\Requests\FilterByMonthAndYearRequest;
use App\Http\Resources\ExpenseResource;
use App\Services\ExpenseServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ExpenseController extends Controller
{
    private ExpenseServiceInterface $expenseService;

    public function __construct(ExpenseServiceInterface $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    /**
     * get expenses in month and year by account
     */
    public function index(FilterByMonthAndYearRequest $request): ResourceCollection
    {
        throw_if(!auth()->user()->hasAnyPermission(['*'], 'web'), AuthorizationException::class);
        $expenses = $this->expenseService->index($request->date('year-month'));

        return ExpenseResource::collection($expenses);
    }

    /**
     * store expense
     */
    public function store(CreateExpenseRequest $request): JsonResponse
    {
        throw_if(!auth()->user()->hasAnyPermission(['*'], 'web'), AuthorizationException::class);
        $data = $request->validated();
        $this->expenseService->store($data, auth()->user()->id);

        return response()->json([
            'message' => 'User expense created successfully',
        ], 201);
    }
}
