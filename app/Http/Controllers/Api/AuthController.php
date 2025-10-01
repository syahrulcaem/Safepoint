<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'nullable|string|max:32|unique:users,phone',
            'password' => 'required|string|min:6',
            'nik' => 'nullable|string|max:32|unique:citizen_profiles,nik',
            'whatsapp_keluarga' => 'nullable|string|max:20',
            'hubungan' => 'nullable|in:KEPALA_KELUARGA,ISTRI,SUAMI,ANAK,AYAH,IBU,KAKEK,NENEK,CUCU,SAUDARA,LAINNYA',
        ]);

        // At least email or phone must be provided
        if (!$request->email && !$request->phone) {
            throw ValidationException::withMessages([
                'email' => 'Email atau nomor telepon harus diisi.',
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'WARGA',
            'email_verified_at' => now(),
        ]);

        // Create citizen profile if NIK provided
        if ($request->nik) {
            $user->citizenProfile()->create([
                'nik' => $request->nik,
                'whatsapp_keluarga' => $request->whatsapp_keluarga,
                'hubungan' => $request->hubungan,
            ]);
        }

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil',
            'data' => [
                'user' => $user->load('citizenProfile'),
                'token' => $token,
            ],
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'password' => 'required|string',
        ]);

        // At least email or phone must be provided
        if (!$request->email && !$request->phone) {
            throw ValidationException::withMessages([
                'email' => 'Email atau nomor telepon harus diisi.',
            ]);
        }

        $user = null;
        if ($request->email) {
            $user = User::where('email', $request->email)->first();
        } elseif ($request->phone) {
            $user = User::where('phone', $request->phone)->first();
        }

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'Email/telepon atau password tidak valid.',
            ]);
        }

        // Only allow WARGA role for mobile app
        if ($user->role !== 'WARGA') {
            throw ValidationException::withMessages([
                'email' => 'Akun ini tidak dapat digunakan di aplikasi mobile.',
            ]);
        }

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => $user->load('citizenProfile'),
                'token' => $token,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'user' => $request->user()->load('citizenProfile'),
            ],
        ]);
    }
}
