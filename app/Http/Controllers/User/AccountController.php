<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\AccountServiceInterface;
use Illuminate\Http\JsonResponse;

class AccountController extends Controller
{
    private AccountServiceInterface $accountService;

    public function __construct(AccountServiceInterface $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * get balance of authenticated user
     *
     * @return float
     */
    public function balance() : JsonResponse
    {
        throw_if(!auth()->user()->hasAnyPermission(['*'], 'web'), AuthorizationException::class);
        $balance = $this->accountService->getBalanceByUserId(auth()->user()->id);
        return response()->json(['balance' => $balance]);
    }
}
