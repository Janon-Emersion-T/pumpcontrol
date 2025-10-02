<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountTransfer extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'account_transfers';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'from_account_id',
        'to_account_id',
        'amount',
        'description',
    ];

    /**
     * The user who performed the transfer.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The account from which money was transferred.
     */
    public function fromAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'from_account_id');
    }

    /**
     * The account to which money was transferred.
     */
    public function toAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'to_account_id');
    }
}
