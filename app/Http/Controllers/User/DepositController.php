<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateDepositRequest;
use App\Http\Requests\FilterByMonthAndYearRequest;
use App\Http\Resources\DepositResource;
use App\Services\DepositServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class DepositController extends Controller
{
    private DepositServiceInterface $depositService;

    public function __construct(DepositServiceInterface $depositService)
    {
        $this->depositService = $depositService;
    }

    /**
     * get deposits in month and year by account
     */
    public function index(FilterByMonthAndYearRequest $request): Collection
    {
        throw_if(!auth()->user()->hasAnyPermission(['*'], 'web'), AuthorizationException::class);
        $deposits = $this->depositService->index($request->date('year-month'));

        return DepositResource::collection($deposits)->groupBy('status');
    }

    /**
     * store deposit
     */
    public function store(CreateDepositRequest $request): JsonResponse
    {
        throw_if(!auth()->user()->hasAnyPermission(['*'], 'web'), AuthorizationException::class);
        $data = $request->validated();
        $this->depositService->store($data, auth()->user()->id);

        return response()->json(['message' => 'Deposit created successfully'], 201);
    }
}
