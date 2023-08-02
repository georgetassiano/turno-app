<?php

namespace App\Services;

use App\Repositories\DepositRepository;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class DepositService extends BaseService implements DepositServiceInterface
{
    private DepositRepository $depositRepository;

    private TransactionServiceInterface $transactionService;

    private AccountServiceInterface $accountService;

    public function __construct(DepositRepository $depositRepository, TransactionServiceInterface $transactionService, AccountServiceInterface $accountService)
    {
        $this->depositRepository = $depositRepository;
        $this->transactionService = $transactionService;
        $this->accountService = $accountService;
    }

    /**
     * get deposits in month and year by user authenticate
     */
    public function index(Carbon $date): Collection
    {
        return $this->depositRepository->getDepositsInMonthAndYearByUserAuthenticate($date->month, $date->year);
    }

    /**
     * store deposit
     */
    public function store(array $data, int $userId): void
    {
        $path = Storage::putFile('file_deposits', $data['file']);
        $dataDeposit = array_merge(Arr::except($data, ['file']),
            [
                'user_id' => $userId,
                'file_path' => $path,
            ]);
        $this->depositRepository->create($dataDeposit);
    }

    /**
     * get deposits pending
     */
    public function getDepositsPending(): Collection
    {
        return $this->depositRepository->getDepositsPending();
    }

    /**
     * accept or reject deposit
     *
     * @param  int  $depositId
     * @param  string  $status
     */
    public function acceptOrRejectDeposit($depositId, $status): void
    {
        DB::transaction(function () use ($depositId, $status) {
            $deposit = $this->depositRepository->find($depositId);
            if ($this->checkIfDepositExistAndIsValid($deposit)) {
                $this->depositRepository->update(['status' => $status], $depositId);
                if ($status == 'approved') {
                    $this->createTransactionAndDepositForAccount($deposit);
                }
            }
        });
    }

    /**
     * create transaction and deposit for account
     *
     * @param  object  $deposit
     */
    public function createTransactionAndDepositForAccount($deposit): void
    {
        $this->createTransactionFromDeposit($deposit);
        $this->accountService->creditAmount($deposit->amount, $deposit->user_id);
    }

    /**
     * create transaction from deposit
     *
     * @param  object  $deposit
     * @param  object  $account
     * @return void
     */
    public function createTransactionFromDeposit($deposit)
    {
        $account = $this->accountService->getAccountByUserId($deposit->user_id);
        $dataTransaction = [
            'transactable_type' => 'deposit',
            'transactable_id' => $deposit->id,
            'account_id' => $account->id,
        ];
        $this->transactionService->store($dataTransaction);
    }

    /**
     * check if deposit exist and is valid
     *
     * @param  object  $deposit
     */
    public function checkIfDepositExistAndIsValid($deposit): bool
    {
        if (! isset($deposit)) {
            $this->throwExceptionIfDepositNotExist();
        } elseif ($deposit->status != 'pending') {
            $this->throwExceptionIfDepositIsNotPending();
        }

        return true;
    }

    /**
     * throw exception if deposit not exist
     */
    public function throwExceptionIfDepositNotExist(): void
    {
        throw ValidationException::withMessages([
            'deposit' => 'Deposit is not found',
        ]);
    }

    /**
     * throw exception if deposit is not pending
     */
    public function throwExceptionIfDepositIsNotPending(): void
    {
        throw ValidationException::withMessages([
            'deposit' => 'Deposit is not pending',
        ]);
    }
}
