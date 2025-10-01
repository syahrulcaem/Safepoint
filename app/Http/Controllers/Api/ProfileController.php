<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CitizenProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user()->load('citizenProfile');

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
            ],
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:100',
            'email' => 'nullable|email|unique:users,email,' . $request->user()->id,
            'phone' => 'nullable|string|max:32|unique:users,phone,' . $request->user()->id,
            'nik' => 'nullable|string|max:32|unique:citizen_profiles,nik,' . $request->user()->id . ',user_id',
            'whatsapp_keluarga' => 'nullable|string|max:20',
            'hubungan' => 'nullable|in:KEPALA_KELUARGA,ISTRI,SUAMI,ANAK,AYAH,IBU,KAKEK,NENEK,CUCU,SAUDARA,LAINNYA',
            'birth_date' => 'nullable|date|before:today',
            'blood_type' => 'nullable|in:A,B,AB,O,UNKNOWN',
            'chronic_conditions' => 'nullable|string',
        ]);

        $user = $request->user();

        // Update user data
        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->has('phone')) {
            $user->phone = $request->phone;
        }
        $user->save();

        // Update or create citizen profile
        $profileData = array_filter([
            'nik' => $request->nik,
            'whatsapp_keluarga' => $request->whatsapp_keluarga,
            'hubungan' => $request->hubungan,
            'birth_date' => $request->birth_date,
            'blood_type' => $request->blood_type,
            'chronic_conditions' => $request->chronic_conditions,
        ]);

        if (!empty($profileData)) {
            $user->citizenProfile()->updateOrCreate(
                ['user_id' => $user->id],
                $profileData
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data' => [
                'user' => $user->fresh()->load('citizenProfile'),
            ],
        ]);
    }

    public function uploadKtp(Request $request)
    {
        $request->validate([
            'ktp_image' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
        ]);

        $user = $request->user();

        if ($request->hasFile('ktp_image')) {
            // Delete old KTP image if exists
            if ($user->citizenProfile && $user->citizenProfile->ktp_image_url) {
                $oldPath = str_replace('/storage/', '', $user->citizenProfile->ktp_image_url);
                Storage::disk('public')->delete($oldPath);
            }

            // Store new image
            $path = $request->file('ktp_image')->store('ktp-images', 'public');
            $url = '/storage/' . $path;

            // Update or create citizen profile
            $user->citizenProfile()->updateOrCreate(
                ['user_id' => $user->id],
                ['ktp_image_url' => $url]
            );

            return response()->json([
                'success' => true,
                'message' => 'Foto KTP berhasil diunggah',
                'data' => [
                    'ktp_image_url' => $url,
                ],
            ]);
        }

        throw ValidationException::withMessages([
            'ktp_image' => 'Gagal mengunggah foto KTP.',
        ]);
    }
}
