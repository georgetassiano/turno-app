<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\AccountRepository;
use App\Models\Account;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Traits\CacheableRepository;
use App\Criteria\UserAuthenticateCriteria;

/**
 * Class AccountRepositoryEloquent.
 *
 * @package namespace App\Repositories;
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
    public function getBalance() : float
    {
        $this->pushCriteria(new UserAuthenticateCriteria());
        return $this->first(['balance'])->balance;
    }

}
