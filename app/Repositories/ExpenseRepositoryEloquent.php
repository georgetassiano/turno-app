<?php

namespace App\Repositories;

use App\Criteria\UserAuthenticateCriteria;
use App\Models\Expense;
use Illuminate\Support\Collection;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Traits\CacheableRepository;

/**
 * Class ExpenseRepositoryEloquent.
 */
class ExpenseRepositoryEloquent extends BaseRepository implements ExpenseRepository, CacheableInterface
{
    use CacheableRepository;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Expense::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /* get expenses in month and year by user authenticate */
    public function getExpensesInMonthAndYearByUserAuthenticate(int $month, int $year): Collection
    {
        $this->pushCriteria(new UserAuthenticateCriteria());

        return $this->scopeQuery(function ($query) use ($month, $year) {
            return $query->whereMonth('created_at', $month)->whereYear('created_at', $year);
        })->all();
    }
}
