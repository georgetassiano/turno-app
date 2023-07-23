<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\DepositRepository;
use App\Models\Deposit;

/**
 * Class DepositRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class DepositRepositoryEloquent extends BaseRepository implements DepositRepository
{
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

}
