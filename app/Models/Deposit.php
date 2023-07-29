<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Class Deposit.
 *
 * @package namespace App\Models;
 */
class Deposit extends Model
{
    use HasFactory;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function transaction(): MorphOne
    {
        return $this->morphOne(Transaction::class, 'transactable');
    }

}
