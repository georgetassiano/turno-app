<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class UserAuthenticateCriteriaCriteria.
 */
class UserAuthenticateCriteria implements CriteriaInterface
{
    /**
     * Apply criteria in query repository
     *
     * @param  string  $model
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return $model->where('user_id', auth()->user()->id);
    }
}
