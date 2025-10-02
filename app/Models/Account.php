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

    public function incomes()
    {
        return $this->hasMany(Income::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    // Scopes
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeAssets($query)
    {
        return $query->where('type', 'Asset');
    }

    public function scopeLiabilities($query)
    {
        return $query->where('type', 'Liability');
    }

    public function scopeIncomeAccounts($query)
    {
        return $query->where('type', 'Income');
    }

    public function scopeExpenseAccounts($query)
    {
        return $query->where('type', 'Expense');
    }

    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    // Helper Methods
    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function isParent(): bool
    {
        return is_null($this->parent_id);
    }

    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

    public function getFormattedBalance(): string
    {
        return 'Rs.' . number_format($this->current_balance, 2);
    }

    public function getTotalIncomesToday(): float
    {
        return $this->incomes()
            ->whereDate('date', today())
            ->sum('amount');
    }

    public function getTotalExpensesToday(): float
    {
        return $this->expenses()
            ->whereDate('date', today())
            ->sum('amount');
    }

    public function getTotalIncomesThisMonth(): float
    {
        return $this->incomes()
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');
    }

    public function getTotalExpensesThisMonth(): float
    {
        return $this->expenses()
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');
    }

    public function getBalanceColor(): string
    {
        if ($this->type === 'Asset' || $this->type === 'Income') {
            return $this->current_balance >= 0 ? 'green' : 'red';
        } elseif ($this->type === 'Expense' || $this->type === 'Liability') {
            return $this->current_balance <= 0 ? 'green' : 'red';
        }
        return 'gray';
    }

    public function getFullName(): string
    {
        if ($this->parent) {
            return $this->parent->name . ' > ' . $this->name;
        }
        return $this->name;
    }
}
