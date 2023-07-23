<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ExpenseRepository;
use App\Models\Expense;

/**
 * Class ExpenseRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ExpenseRepositoryEloquent extends BaseRepository implements ExpenseRepository
{
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

}
