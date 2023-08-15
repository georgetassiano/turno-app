<?php

namespace App\Services;

use App\Models\Account;
use App\Models\User;
use App\Repositories\AccountRepository;
use Illuminate\Validation\ValidationException;

class AccountService extends BaseService implements AccountServiceInterface
{
    private AccountRepository $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    /** create account for user
     * @param  int  $userId
     */
    public function createAccountForUserId(int $userId)
    {
        $this->accountRepository->create([
            'user_id' => $userId,
        ]);
    }

    /**
     * get account by authenticated user
     *
     * @return Account
     */
    public function getBalanceByUserId(int $userId): float
    {
        $account = $this->getAccountByUserId($userId);

        return $account->balance;
    }

    /**
     * throw exception if amount is greater than balance
     * @param  float  $amount
     * @param  float  $balance
     */
    public function throwExceptionIfAmountIsGreaterThanBalance(float $amount, float $balance)
    {
        if ($amount > $balance) {
            throw ValidationException::withMessages([
                'amount' => 'The amount is greater than the balance',
            ]);
        }
    }

    /**
     * debit amount from account
     * @param  float  $amount
     * @param  int  $userId
     */
    public function debitAmount(float $amount, int $userId)
    {
        $account = $this->getAccountByUserId($userId);
        $this->throwExceptionIfAmountIsGreaterThanBalance($amount, $account->balance);
        $this->accountRepository->update(['balance' => $account->balance - $amount], $account->id);
    }

    /**
     * credit amount from account
     * @param  float  $amount
     * @param  int  $userId
     */
    public function creditAmount(float $amount, int $userId)
    {
        $account = $this->getAccountByUserId($userId);
        $this->accountRepository->update(['balance' => $account->balance + $amount], $account->id);
    }

    /**
     * get account by user id
     * @param  int  $userId
     * @return Account
     */
    public function getAccountByUserId(int $userId): Account
    {
        return $this->accountRepository->getAccountByUserId($userId);
    }
}
