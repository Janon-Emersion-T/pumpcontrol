<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'position',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // 🔗 Relationships

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pumpRecords()
    {
        return $this->hasMany(PumpRecord::class);
    }

    // Optional: Full name accessor
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
