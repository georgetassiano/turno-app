<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCheckRequest;
use App\Http\Requests\FilterByMonthAndYearRequest;
use App\Http\Resources\CheckResource;
use App\Services\CheckServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class CheckController extends Controller
{
    private CheckServiceInterface $checkService;

    public function __construct(CheckServiceInterface $checkService)
    {
        $this->checkService = $checkService;
    }

    /**
     * get Checks in month and year by account
     * @param FilterByMonthAndYearRequest $request
     * @return Collection
     */
    public function index(FilterByMonthAndYearRequest $request): Collection
    {
        throw_if(!auth()->user()->hasAnyPermission(['*'], 'web'), AuthorizationException::class);
        $checks = $this->checkService->index($request->date('year-month'));

        return CheckResource::collection($checks)->groupBy('status');
    }

    /**
     * store Check
     * @param CreateCheckRequest $request
     * @return JsonResponse
     */
    public function store(CreateCheckRequest $request): JsonResponse
    {
        throw_if(!auth()->user()->hasAnyPermission(['*'], 'web'), AuthorizationException::class);
        $data = $request->validated();
        $this->checkService->store($data, auth()->user()->id);

        return response()->json(['message' => 'Check created successfully'], 201);
    }

    /**
     * get dates to filter checks
     * @return JsonResponse
     */
    public function datesToFilter() : JsonResponse
    {
        throw_if(!auth()->user()->hasAnyPermission(['*'], 'web'), AuthorizationException::class);
        $dates = $this->checkService->datesToFilter(auth()->user()->id);

        return response()->json(['data' => $dates]);
    }
}
