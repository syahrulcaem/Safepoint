<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'unit_id',
        'last_latitude',
        'last_longitude',
        'last_location_update',
        'duty_status',
        'duty_started_at',
        'last_activity_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_location_update' => 'datetime',
            'duty_started_at' => 'datetime',
            'last_activity_at' => 'datetime',
            'last_latitude' => 'decimal:8',
            'last_longitude' => 'decimal:8',
        ];
    }

    // Relationships
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function citizenProfile(): HasOne
    {
        return $this->hasOne(CitizenProfile::class);
    }

    public function reportedCases(): HasMany
    {
        return $this->hasMany(Cases::class, 'reporter_user_id');
    }

    public function assignedDispatches(): HasMany
    {
        return $this->hasMany(Dispatch::class, 'assigned_by');
    }

    public function caseEvents(): HasMany
    {
        return $this->hasMany(CaseEvent::class, 'actor_id');
    }

    // Helper methods
    public function hasWebRole(): bool
    {
        return in_array($this->role, ['OPERATOR', 'SUPERADMIN', 'PIMPINAN', 'PETUGAS']);
    }

    public function canManageCases(): bool
    {
        return in_array($this->role, ['OPERATOR', 'SUPERADMIN']);
    }

    public function canViewAssignedCases(): bool
    {
        return $this->role === 'PETUGAS';
    }

    public function isOnDuty(): bool
    {
        return $this->duty_status === 'ON_DUTY';
    }

    public function isOffDuty(): bool
    {
        return $this->duty_status === 'OFF_DUTY';
    }

    public function startDuty(): void
    {
        $this->update([
            'duty_status' => 'ON_DUTY',
            'duty_started_at' => now(),
            'last_activity_at' => now(),
        ]);
    }

    public function endDuty(): void
    {
        $this->update([
            'duty_status' => 'OFF_DUTY',
            'duty_started_at' => null,
            'last_activity_at' => now(),
        ]);
    }

    public function updateLocation(float $latitude, float $longitude): void
    {
        $this->update([
            'last_latitude' => $latitude,
            'last_longitude' => $longitude,
            'last_location_update' => now(),
            'last_activity_at' => now(),
        ]);
    }

    public function getLastLocationAttribute(): ?array
    {
        if ($this->last_latitude && $this->last_longitude) {
            return [
                'latitude' => (float) $this->last_latitude,
                'longitude' => (float) $this->last_longitude,
                'updated_at' => $this->last_location_update,
            ];
        }
        return null;
    }
}
