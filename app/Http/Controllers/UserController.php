<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 'SUPERADMIN') {
                abort(403, 'Akses ditolak. Hanya Super Admin yang dapat mengelola users.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $users = User::with(['citizenProfile', 'unit'])->orderBy('created_at', 'desc')->paginate(15);

        // Count users by role
        $roleStats = [
            'total' => User::count(),
            'super_admin' => User::where('role', 'SUPERADMIN')->count(),
            'admin' => User::where('role', 'ADMIN')->count(),
            'operator' => User::where('role', 'OPERATOR')->count(),
            'pimpinan' => User::where('role', 'PIMPINAN')->count(),
            'petugas' => User::where('role', 'PETUGAS')->count(),
            'warga' => User::where('role', 'WARGA')->count(),
        ];

        return view('users.index', compact('users', 'roleStats'));
    }

    public function create()
    {
        $roles = [
            'SUPERADMIN' => 'Super Admin',
            'ADMIN' => 'Admin',
            'OPERATOR' => 'Operator',
            'PIMPINAN' => 'Pimpinan',
            'PETUGAS' => 'Petugas',
            'WARGA' => 'Warga'
        ];

        $units = Unit::active()->orderBy('name')->get();

        return view('users.create', compact('roles', 'units'));
    }

    public function store(Request $request)
    {
        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:SUPERADMIN,ADMIN,OPERATOR,PIMPINAN,PETUGAS,WARGA',
        ];

        // Unit is required for PETUGAS role
        if ($request->role === 'PETUGAS') {
            $validationRules['unit_id'] = 'required|exists:units,id';
        }

        $request->validate($validationRules, [
            'name.required' => 'Nama harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'phone.unique' => 'Nomor telepon sudah terdaftar.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Role harus dipilih.',
            'role.in' => 'Role tidak valid.',
            'unit_id.required' => 'Unit harus dipilih untuk petugas.',
            'unit_id.exists' => 'Unit tidak valid.',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'email_verified_at' => now(), // Auto verify for admin created users
        ];

        // Add unit_id for PETUGAS role
        if ($request->role === 'PETUGAS') {
            $userData['unit_id'] = $request->unit_id;
        }

        User::create($userData);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dibuat.');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = [
            'SUPERADMIN' => 'Super Admin',
            'ADMIN' => 'Admin',
            'OPERATOR' => 'Operator',
            'PIMPINAN' => 'Pimpinan',
            'PETUGAS' => 'Petugas',
            'WARGA' => 'Warga'
        ];

        $units = Unit::active()->orderBy('name')->get();

        return view('users.edit', compact('user', 'roles', 'units'));
    }

    public function update(Request $request, User $user)
    {
        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:SUPERADMIN,ADMIN,OPERATOR,PIMPINAN,PETUGAS,WARGA',
        ];

        // Unit is required for PETUGAS role
        if ($request->role === 'PETUGAS') {
            $validationRules['unit_id'] = 'required|exists:units,id';
        }

        $request->validate($validationRules, [
            'name.required' => 'Nama harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'phone.unique' => 'Nomor telepon sudah terdaftar.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Role harus dipilih.',
            'role.in' => 'Role tidak valid.',
            'unit_id.required' => 'Unit harus dipilih untuk petugas.',
            'unit_id.exists' => 'Unit tidak valid.',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
        ];

        // Handle unit assignment for PETUGAS role
        if ($request->role === 'PETUGAS') {
            $data['unit_id'] = $request->unit_id;
        } else {
            // Remove unit assignment for non-PETUGAS roles
            $data['unit_id'] = null;
        }

        // Only update password if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diupdate.');
    }

    public function destroy(User $user)
    {
        // Prevent super admin from deleting themselves
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        // Prevent deleting the last super admin
        if ($user->role === 'SUPER_ADMIN' && User::where('role', 'SUPER_ADMIN')->count() <= 1) {
            return redirect()->route('users.index')
                ->with('error', 'Tidak dapat menghapus Super Admin terakhir.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    public function toggleStatus(User $user)
    {
        // Prevent super admin from deactivating themselves
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('users.index')
            ->with('success', "User berhasil {$status}.");
    }

    public function whatsapp(User $user)
    {
        // Load citizen profile untuk mendapatkan info keluarga
        $user->load('citizenProfile');

        return view('users.whatsapp', compact('user'));
    }

    public function sendWhatsapp(Request $request, User $user)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // Format nomor telepon (hapus karakter non-digit)
        $phone = preg_replace('/[^0-9]/', '', $user->phone);

        // Jika nomor dimulai dengan 0, ganti dengan 62
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }

        // Generate WhatsApp link
        $message = urlencode($request->message);
        $whatsappUrl = "https://wa.me/{$phone}?text={$message}";

        return response()->json([
            'success' => true,
            'whatsapp_url' => $whatsappUrl,
            'message' => 'Link WhatsApp berhasil dibuat. Anda akan diarahkan ke WhatsApp.'
        ]);
    }
}
