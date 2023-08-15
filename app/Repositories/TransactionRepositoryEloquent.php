<?php

namespace App\Repositories;

use App\Models\Transaction;
use Illuminate\Support\Collection;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Traits\CacheableRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class TransactionRepositoryEloquent.
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

    /** get transactions in month and year by user authenticate
     * @param  int  $month
     * @param  int  $year
     * @param  int  $accountId
     * @return Collection
    */
    public function getTransactionsInMonthAndYearByAccountWithTransactable(int $month, int $year, int $accountId): Collection
    {
        return $this->with(['transactable:id,amount,description'])->scopeQuery(function ($query) use ($month, $year) {
            return $query->whereMonth('created_at', $month)->whereYear('created_at', $year);
        })->orderBy('created_at', $direction = 'desc')->findByField('account_id', $accountId, ['created_at', 'transactable_type', 'transactable_id']);
    }

    /** get dates by month and year to filter
     * @param  int  $accountId
     * @return Collection
    */
    public function datesToFilter(int $accountId) : Collection {
        return DB::table('transactions')->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year')
        ->where('account_id', $accountId)
        ->groupBy('month', 'year')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->get();
    }
}
