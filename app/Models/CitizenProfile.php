<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CitizenProfile extends Model
{
    protected $primaryKey = 'user_id';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'nik',
        'ktp_image_url',
        'birth_date',
        'blood_type',
        'chronic_conditions',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    // Relationship
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods
    public function getAgeAttribute()
    {
        if (!$this->birth_date) {
            return null;
        }
        return $this->birth_date->age;
    }

    public function getBloodTypeDisplayAttribute()
    {
        $types = [
            'A' => 'A',
            'B' => 'B',
            'AB' => 'AB',
            'O' => 'O',
            'UNKNOWN' => 'Tidak Diketahui'
        ];

        return $types[$this->blood_type] ?? 'Tidak Diketahui';
    }
}
