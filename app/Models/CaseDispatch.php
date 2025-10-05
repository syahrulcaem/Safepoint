<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseDispatch extends Model
{
    protected $fillable = [
        'case_id',
        'unit_id',
        'assigned_petugas_id',
        'dispatcher_id',
        'notes',
        'dispatched_at',
        'assigned_at',
    ];

    protected $casts = [
        'dispatched_at' => 'datetime',
        'assigned_at' => 'datetime',
    ];

    /**
     * Get the case that owns this dispatch
     */
    public function case(): BelongsTo
    {
        return $this->belongsTo(Cases::class, 'case_id');
    }

    /**
     * Get the unit this dispatch is assigned to
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get the petugas assigned to this dispatch
     */
    public function assignedPetugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_petugas_id');
    }

    /**
     * Get the dispatcher (operator) who created this dispatch
     */
    public function dispatcher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dispatcher_id');
    }
}
