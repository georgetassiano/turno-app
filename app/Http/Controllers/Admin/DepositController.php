<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AcceptOrRejectDepositRequest;
use App\Http\Resources\AdminDepositResource;
use App\Models\Deposit;
use App\Services\DepositServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Auth\Access\AuthorizationException;

class DepositController extends Controller
{
    private DepositServiceInterface $depositService;

    public function __construct(DepositServiceInterface $depositService)
    {
        $this->depositService = $depositService;
    }

    /**
     * get deposits pending
     */
    public function index(): ResourceCollection
    {
        throw_if(!auth()->user()->hasAnyPermission(['*'], 'admin'), AuthorizationException::class);
        $deposits = $this->depositService->getDepositsPending();
        return AdminDepositResource::collection($deposits);
    }

    /**
     * accept or reject deposit
     *
     * @param  int  $depositId
     */
    public function acceptOrReject($depositId, AcceptOrRejectDepositRequest $request): JsonResponse
    {
        throw_if(!auth()->user()->hasAnyPermission(['*'], 'admin'), AuthorizationException::class);
        $this->depositService->acceptOrRejectDeposit($depositId, $request->status);
        return response()->json([
            'message' => 'deposit updated successfully',
        ]);
    }
}
