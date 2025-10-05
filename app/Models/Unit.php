<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Unit extends Model
{
    protected $fillable = [
        'name',
        'type',
        'is_active',
        'last_lat',
        'last_lon',
        'last_seen_at',
        'pimpinan_id',
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

    /**
     * Get the pimpinan (leader) of this unit
     */
    public function pimpinan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pimpinan_id');
    }

    /**
     * Get the case dispatches for this unit
     */
    public function caseDispatches(): HasMany
    {
        return $this->hasMany(CaseDispatch::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
