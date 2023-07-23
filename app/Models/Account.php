<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
/**
 * Class Account.
 *
 * @package namespace App\Models;
 */
class Account extends Model
{
    use HasFactory;
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
