<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AcceptOrRejectCheckRequest;
use App\Http\Resources\AdminCheckResource;
use App\Models\Check;
use App\Services\CheckServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Auth\Access\AuthorizationException;

class CheckController extends Controller
{
    private CheckServiceInterface $checkService;

    public function __construct(CheckServiceInterface $checkService)
    {
        $this->checkService = $checkService;
    }

    /**
     * get checks pending
     * @return ResourceCollection
     */
    public function index(): ResourceCollection
    {
        throw_if(!auth()->user()->hasAnyPermission(['*'], 'admin'), AuthorizationException::class);
        $checks = $this->checkService->getPendingChecks();
        return AdminCheckResource::collection($checks);
    }

    /**
     * get check pending by id
     *
     * @param  int  $checkId
     *
     * @return AdminCheckResource
     */
    public function show($checkId): AdminCheckResource
    {
        throw_if(!auth()->user()->hasAnyPermission(['*'], 'admin'), AuthorizationException::class);
        $check = $this->checkService->getPendingCheckById($checkId);
        return new AdminCheckResource($check);
    }

    /**
     * accept or reject Check
     *
     * @param  int  $CheckId
     */
    public function acceptOrReject($checkId, AcceptOrRejectCheckRequest $request): JsonResponse
    {
        throw_if(!auth()->user()->hasAnyPermission(['*'], 'admin'), AuthorizationException::class);
        $this->checkService->acceptOrRejectCheck($checkId, $request->status);
        return response()->json([
            'message' => 'Check updated successfully',
        ]);
    }
}
