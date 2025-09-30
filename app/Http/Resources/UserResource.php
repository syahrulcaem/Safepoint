<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->role,
            'email_verified_at' => $this->email_verified_at,
            'citizen_profile' => $this->whenLoaded('citizenProfile', function () {
                return [
                    'nik' => $this->citizenProfile->nik,
                    'nomor_keluarga' => $this->citizenProfile->nomor_keluarga,
                    'hubungan' => $this->citizenProfile->hubungan,
                    'hubungan_display' => $this->citizenProfile->hubungan_display,
                    'ktp_image_url' => $this->citizenProfile->ktp_image_url,
                    'birth_date' => $this->citizenProfile->birth_date,
                    'age' => $this->citizenProfile->age,
                    'blood_type' => $this->citizenProfile->blood_type,
                    'blood_type_display' => $this->citizenProfile->blood_type_display,
                    'chronic_conditions' => $this->citizenProfile->chronic_conditions,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
