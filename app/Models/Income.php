<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Account;
use App\Models\User;

class Income extends Model
{
    protected $fillable = [
        'account_id',
        'user_id',
        'date',
        'amount',
        'reference',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    // Belongs to an account in the chart
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    // User who created the income
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
