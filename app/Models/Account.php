<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    protected $fillable = [
        'code',
        'name',
        'type',
        'parent_id',
        'description',
        'current_balance',
        'is_active',
    ];

    protected $casts = [
        'current_balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Self-referencing parent account
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    // Child accounts (if this is a parent)
    public function children(): HasMany
    {
        return $this->hasMany(Account::class, 'parent_id');
    }

    // Scope: Active accounts only
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function outgoingTransfers()
    {
        return $this->hasMany(AccountTransfer::class, 'from_account_id');
    }

    public function incomingTransfers()
    {
        return $this->hasMany(AccountTransfer::class, 'to_account_id');
    }

}
