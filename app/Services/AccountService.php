<?php

namespace App\Services;
use App\Repositories\AccountRepository;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AccountService extends BaseService implements AccountServiceInterface
{
    private AccountRepository $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    /* create account for user */
    public function createAccountForUser(User $user) : void
    {
        $this->accountRepository->create([
            'user_id' => $user->id,
        ]);
    }

    /* get balance of authenticated user */
    public function getBalance() : float
    {
        return $this->accountRepository->getBalance();
    }

    public function throwInvalidAmountException() : ValidationException {
        throw ValidationException::withMessages([
            'amount' => 'The amount is greater than the balance'
        ]);
    }

    public function debitAmount(float $amount) : void
    {
        $balance = $this->getBalance();
        if ($amount > $balance) {
            $this->throwInvalidAmountException();
        }
        $this->accountRepository->update(['amount' => $balance - $amount],auth()->user()->account->id);
    }
}
