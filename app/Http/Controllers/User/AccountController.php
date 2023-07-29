<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AccountServiceInterface;

class AccountController extends Controller
{
    private AccountServiceInterface $accountService;
    public function __construct(AccountServiceInterface $accountService) {
        $this->accountService = $accountService;
    }
    public function balance() {
        return $this->accountService->getBalance();
    }
}
