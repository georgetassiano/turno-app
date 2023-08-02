<?php

namespace App\Repositories;

use App\Criteria\UserAuthenticateCriteria;
use App\Models\Deposit;
use Illuminate\Support\Collection;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Traits\CacheableRepository;

/**
 * Class DepositRepositoryEloquent.
 */
class DepositRepositoryEloquent extends BaseRepository implements DepositRepository, CacheableInterface
{
    use CacheableRepository;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Deposit::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /* get deposits in month and year by user authenticate */
    public function getDepositsInMonthAndYearByUserAuthenticate($month, $year): Collection
    {
        $this->pushCriteria(new UserAuthenticateCriteria());

        return $this->scopeQuery(function ($query) use ($month, $year) {
            return $query->whereMonth('created_at', $month)->whereYear('created_at', $year);
        })->all();
    }

    /* get deposits pending */
    public function getDepositsPending(): Collection
    {
        return $this->with(['user:id,name,email', 'user.account:id,user_id'])->findByField('status', 'pending', ['id', 'amount', 'description', 'user_id', 'created_at']);
    }
}
