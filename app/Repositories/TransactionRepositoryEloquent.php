<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\TransactionRepository;
use App\Models\Transaction;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Traits\CacheableRepository;
use App\Criteria\UserAuthenticateCriteria;
use Illuminate\Support\Collection;

/**
 * Class TransactionRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class TransactionRepositoryEloquent extends BaseRepository implements TransactionRepository, CacheableInterface
{
    use CacheableRepository;
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Transaction::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function getTransactionsInMonthAndYearByAccountWithTransactable(int $month, int $year, int $accountId) : Collection
    {
        return $this->with(['transactable:id,amount,description'])->scopeQuery(function ($query) use ($month, $year) {
            return $query->whereMonth('created_at', $month)->whereYear('created_at', $year);
        })->findByField('account_id', $accountId, ['created_at', 'transactable_type', 'transactable_id']);
    }

}
