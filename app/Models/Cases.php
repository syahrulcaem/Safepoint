<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Cases extends Model
{
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'reporter_user_id',
        'device_id',
        'viewer_token_hash',
        'phone',
        'lat',
        'lon',
        'accuracy',
        'locator_text',
        'locator_provider',
        'category',
        'status',
        'assigned_unit_id',
        'contacts_snapshot',
        'verified_at',
        'dispatched_at',
        'on_scene_at',
        'closed_at',
    ];

    protected $casts = [
        'contacts_snapshot' => 'array',
        'verified_at' => 'datetime',
        'dispatched_at' => 'datetime',
        'on_scene_at' => 'datetime',
        'closed_at' => 'datetime',
        'lat' => 'decimal:7',
        'lon' => 'decimal:7',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Str::ulid();
            }
        });
    }

    // Accessor for short ID
    public function getShortIdAttribute()
    {
        return Str::substr($this->id, 0, 8);
    }

    // Relationships
    public function reporterUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_user_id');
    }

    public function assignedUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'assigned_unit_id');
    }

    public function caseEvents(): HasMany
    {
        return $this->hasMany(CaseEvent::class, 'case_id');
    }

    public function dispatches(): HasMany
    {
        return $this->hasMany(Dispatch::class, 'case_id');
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    public function scopeByCategory($query, $category)
    {
        if ($category) {
            return $query->where('category', $category);
        }
        return $query;
    }

    public function scopeByUnit($query, $unitId)
    {
        if ($unitId) {
            return $query->where('assigned_unit_id', $unitId);
        }
        return $query;
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('locator_text', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        return $query;
    }
}
