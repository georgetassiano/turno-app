<?php

namespace App\Repositories;

use App\Criteria\UserAuthenticateCriteria;
use App\Models\Check;
use Illuminate\Support\Collection;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Traits\CacheableRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class CheckRepositoryEloquent.
 */
class CheckRepositoryEloquent extends BaseRepository implements CheckRepository, CacheableInterface
{
    use CacheableRepository;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Check::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**  get Checks in month and year by user authenticate
     * @param  string  $month
     * @param  string  $year
     * @return Collection
    */
    public function getChecksInMonthAndYearByUserAuthenticate(string $month, string $year): Collection
    {
        $this->pushCriteria(new UserAuthenticateCriteria());

        return $this->scopeQuery(function ($query) use ($month, $year) {
            return $query->whereMonth('created_at', $month)->whereYear('created_at', $year);
        })->orderBy('created_at', $direction = 'desc')->all();
    }

    /** get Checks pending
     * @return Collection
    */
    public function getPendingChecks(): Collection
    {
        return $this->with(['user:id,name,email', 'user.account:id,user_id'])->findByField('status', 'pending', ['id', 'amount', 'description', 'user_id', 'created_at', 'file_path']);
    }

    /** get Check by id
     * @param  int  $checkId
     * @return Check
    */
    public function getPendingCheckById(int $checkId) : ?Check
    {
        return $this->with(['user:id,name,email', 'user.account:id,user_id'])
        ->findWhere([
            'id' => $checkId,
            'status' => 'pending',
        ], ['id', 'amount', 'description', 'user_id', 'created_at', 'file_path'])->first();
    }

    /** get dates by month and year to filter
     * @param  int  $userId
     * @return Collection
    */
    public function datesToFilter(int $userId) : Collection {
        return DB::table('checks')->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year')
        ->where('user_id', $userId)
        ->groupBy('month', 'year')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->get();
    }
}
