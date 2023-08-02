<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use OwenIt\Auditing\Contracts\Auditable;
/**
 * Class Deposit.
 */
class Deposit extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Get the transaction for the deposit
     */
    public function transaction(): MorphOne
    {
        return $this->morphOne(Transaction::class, 'transactable');
    }

    /**
     * Get the user that owns the Deposit
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
