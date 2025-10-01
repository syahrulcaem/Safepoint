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
}
