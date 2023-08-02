<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;
/**
 * Class Account.
 */
class Account extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Get the transactions for the account
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
