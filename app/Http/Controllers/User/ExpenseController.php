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
     * @param FilterByMonthAndYearRequest $request
     * @return ResourceCollection
     */
    public function index(FilterByMonthAndYearRequest $request): ResourceCollection
    {
        throw_if(!auth()->user()->hasAnyPermission(['*'], 'web'), AuthorizationException::class);
        $expenses = $this->expenseService->index($request->date('year-month'));

        return ExpenseResource::collection($expenses);
    }

    /**
     * store expense
     * @param CreateExpenseRequest $request
     * @return JsonResponse
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

    /**
     * get dates to filter expenses
     * @return JsonResponse
     */
    public function datesToFilter() : JsonResponse
    {
        throw_if(!auth()->user()->hasAnyPermission(['*'], 'web'), AuthorizationException::class);
        $dates = $this->expenseService->datesToFilter(auth()->user()->id);

        return response()->json(['data' => $dates]);
    }
}
