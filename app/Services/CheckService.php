<?php

namespace App\Services;

use App\Repositories\CheckRepository;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use App\Models\Check;
use App\Traits\InsertDateNowTrait;

class CheckService extends BaseService implements CheckServiceInterface
{
    use InsertDateNowTrait;

    private CheckRepository $checkRepository;

    private TransactionServiceInterface $transactionService;

    private AccountServiceInterface $accountService;

    public function __construct(CheckRepository $checkRepository, TransactionServiceInterface $transactionService, AccountServiceInterface $accountService)
    {
        $this->checkRepository = $checkRepository;
        $this->transactionService = $transactionService;
        $this->accountService = $accountService;
    }

    /**
     * get Checks in month and year by user authenticate
     * @param  Carbon  $date
     * @return Collection
     */
    public function index(Carbon $date): Collection
    {
        return $this->checkRepository->getChecksInMonthAndYearByUserAuthenticate($date->month, $date->year);
    }


    /**
     * get Check by id
     *
     * @param  int  $checkId
     * @return Check
     */
    public function getPendingCheckById(int $checkId) : Check
    {
        $check = $this->checkRepository->getPendingCheckById($checkId);
        $this->throwExceptionIfCheckNotExist($check);
        return $check;
    }

    /**
     * store Check
     * @param  array  $data
     * @param  int  $userId
     */
    public function store(array $data, int $userId)
    {
        $path = Storage::putFile('file_Checks', $data['file']);
        $dataCheck = array_merge(Arr::except($data, ['file']),
            [
                'user_id' => $userId,
                'file_path' => $path,
            ]);
        $this->checkRepository->create($dataCheck);
    }

    /**
     * get Checks pending
     * @return Collection
     */
    public function getPendingChecks(): Collection
    {
        return $this->checkRepository->getPendingChecks();
    }

    /**
     * accept or reject Check
     *
     * @param  int  $checkId
     * @param  string  $status
     */
    public function acceptOrRejectCheck(int $checkId, string $status)
    {
        DB::transaction(function () use ($checkId, $status) {
            $check = $this->checkRepository->find($checkId);
            $this->throwExceptionIfCheckNotExist($check);
            $this->throwExceptionIfCheckIsNotPending($check);
            $this->checkRepository->update(['status' => $status], $checkId);
            if ($status == 'approved') {
                $this->createTransactionAndCheckForAccount($check);
            }
        });
    }

    /**
     * create transaction and Check for account
     *
     * @param Check $check
     */
    public function createTransactionAndCheckForAccount(Check $check)
    {
        $this->createTransactionFromCheck($check);
        $this->accountService->creditAmount($check->amount, $check->user_id);
    }

    /**
     * create transaction from Check
     *
     * @param Check $check
     */
    public function createTransactionFromCheck(Check $check)
    {
        $account = $this->accountService->getAccountByUserId($check->user_id);
        $dataTransaction = [
            'transactable_type' => 'checks',
            'transactable_id' => $check->id,
            'account_id' => $account->id,
        ];
        $this->transactionService->store($dataTransaction);
    }

    /**
     * throw exception if Check not exist
     * @param Check $check
     */
    public function throwExceptionIfCheckNotExist(Check $check)
    {
        if (!isset($check)) {
            throw ValidationException::withMessages([
                'Check' => 'Check is not found',
            ]);
        }
    }

    /**
     * throw exception if Check is not pending
     */
    public function throwExceptionIfCheckIsNotPending()
    {
        if ($check->status != 'pending') {
            throw ValidationException::withMessages([
                'Check' => 'Check is not pending',
            ]);
        }
    }

    /**
     * get dates to filter
     * @param  int  $userId
     * @return Collection
     */
    public function datesToFilter(int $userId): Collection
    {
        $dates = $this->checkRepository->datesToFilter($userId);
        $formattedDates = $dates->map(function ($date) {
            return Carbon::createFromDate($date->year, $date->month)->format('Y-m');
        });
        $this->insertDateNowIfNotExist($formattedDates);
        return $formattedDates;
    }
}
