<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Collection;

trait InsertDateNowTrait
{
    /**
     * Insert date now if not exist in collection
     *
     * @param Collection $dates
     */
    public function insertDateNowIfNotExist(Collection $dates) {
        $dateNow = Carbon::now()->format('Y-m');
        if(!$dates->contains($dateNow)) {
            $dates->prepend($dateNow);
        }
    }
}
