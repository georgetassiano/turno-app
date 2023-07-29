<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ExpenseRepository;
use App\Models\Expense;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Traits\CacheableRepository;
use App\Criteria\UserAuthenticateCriteria;
use Illuminate\Support\Collection;
/**
 * Class ExpenseRepositoryEloquent.
 *
 * @package namespace App\Repositories;
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

    public function getExpensesInMonthAndYearByUserAuthenticate(int $month, int $year) : Collection {
        $this->pushCriteria(new UserAuthenticateCriteria());
        return $this->scopeQuery(function ($query) use ($month, $year) {
            return $query->whereMonth('created_at', $month)->whereYear('created_at', $year);
        })->all();
    }

}
