<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    protected $fillable = [
        'name',
        'type',
        'is_active',
        'last_lat',
        'last_lon',
        'last_seen_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_lat' => 'decimal:7',
        'last_lon' => 'decimal:7',
        'last_seen_at' => 'datetime',
    ];

    // Relationships
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function cases(): HasMany
    {
        return $this->hasMany(Cases::class, 'assigned_unit_id');
    }

    public function dispatches(): HasMany
    {
        return $this->hasMany(Dispatch::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
