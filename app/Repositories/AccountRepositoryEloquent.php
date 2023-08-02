<?php

namespace App\Repositories;

use App\Models\Account;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Traits\CacheableRepository;

/**
 * Class AccountRepositoryEloquent.
 */
class AccountRepositoryEloquent extends BaseRepository implements AccountRepository, CacheableInterface
{
    use CacheableRepository;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Account::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /* get balance of authenticated user */
    public function getBalanceByAccountId($accountId): Account
    {
        return $this->find($accountId, ['balance']);
    }

    /* get account by user id */
    public function getAccountByUserId(int $userId): Account
    {
        return $this->findByField('user_id', $userId, ['id', 'balance'])->first();
    }
}
