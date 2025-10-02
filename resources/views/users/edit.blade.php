@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit User</h1>
                <p class="text-gray-600">Ubah informasi user {{ $user->name }}</p>
            </div>
            <a href="{{ route('users.index') }}"
                class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Kembali
            </a>
        </div>

        <div class="bg-white shadow rounded-lg">
            <form action="{{ route('users.update', $user) }}" method="POST" class="px-4 py-5 sm:p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                            class="mt-1 block w-full rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm @error('name') border-red-300 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                            required
                            class="mt-1 block w-full rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm @error('email') border-red-300 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor Telepon -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                            class="mt-1 block w-full rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm @error('phone') border-red-300 @enderror">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                        <select name="role" id="role" required
                            class="mt-1 block w-full rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm @error('role') border-red-300 @enderror">
                            <option value="">Pilih Role</option>
                            @foreach ($roles as $key => $label)
                                <option value="{{ $key }}"
                                    {{ old('role', $user->role) == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Unit (for PETUGAS role only) -->
                    <div id="unit-field" style="display: {{ old('role', $user->role) === 'PETUGAS' ? 'block' : 'none' }};">
                        <label for="unit_id" class="block text-sm font-medium text-gray-700">Unit Tugas</label>
                        <select name="unit_id" id="unit_id"
                            class="mt-1 block w-full rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm @error('unit_id') border-red-300 @enderror">
                            <option value="">Pilih Unit</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}"
                                    {{ old('unit_id', $user->unit_id) == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }} ({{ $unit->type }})
                                </option>
                            @endforeach
                        </select>
                        @error('unit_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Unit diperlukan untuk role Petugas</p>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                        <input type="password" name="password" id="password"
                            class="mt-1 block w-full rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm @error('password') border-red-300 @enderror">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Kosongkan jika tidak ingin mengubah password</p>
                    </div>

                    <!-- Konfirmasi Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi
                            Password Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                    </div>
                </div>

                <!-- Status Aktif -->
                <div class="mt-6">
                    <div class="flex items-center">
                        <input id="is_active" name="is_active" type="checkbox" value="1"
                            {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                            class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                            User aktif
                        </label>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">User yang aktif dapat login ke sistem</p>
                </div>

                <!-- User Info -->
                <div class="mt-6 bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Informasi User</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                        <div>
                            <span class="font-medium">Bergabung:</span> {{ $user->created_at->format('d/m/Y H:i') }}
                        </div>
                        <div>
                            <span class="font-medium">Terakhir diupdate:</span>
                            {{ $user->updated_at->format('d/m/Y H:i') }}
                        </div>
                        @if ($user->email_verified_at)
                            <div>
                                <span class="font-medium">Email diverifikasi:</span>
                                {{ $user->email_verified_at->format('d/m/Y H:i') }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('users.index') }}"
                        class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Batal
                    </a>
                    <button type="submit"
                        class="bg-red-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            const unitField = document.getElementById('unit-field');
            const unitSelect = document.getElementById('unit_id');

            function toggleUnitField() {
                if (roleSelect.value === 'PETUGAS') {
                    unitField.style.display = 'block';
                    unitSelect.required = true;
                } else {
                    unitField.style.display = 'none';
                    unitSelect.required = false;
                    if (roleSelect.value !== 'PETUGAS') {
                        unitSelect.value = '';
                    }
                }
            }

            // Listen for role changes
            roleSelect.addEventListener('change', toggleUnitField);
        });
    </script>
@endsection
