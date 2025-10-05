@extends('layouts.app')

@section('title', 'Dashboard Petugas')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Petugas</h1>
            <p class="text-sm text-gray-600 mt-1">Selamat datang, <span class="font-semibold">{{ Auth::user()->name }}</span>
            </p>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 rounded-lg p-4">
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm font-medium text-gray-500">Total Tugas</div>
                <div class="mt-2 text-3xl font-bold text-gray-900">{{ $assignedCases->count() }}</div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm font-medium text-gray-500">Tugas Aktif</div>
                <div class="mt-2 text-3xl font-bold text-red-600">{{ $activeCases->count() }}</div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm font-medium text-gray-500">Selesai</div>
                <div class="mt-2 text-3xl font-bold text-green-600">{{ $completedCases->count() }}</div>
            </div>
        </div>

        <!-- Active Cases -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Kasus Aktif</h2>
                <p class="text-sm text-gray-500 mt-1">Kasus yang sedang Anda tangani</p>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($activeCases as $dispatch)
                    <div class="px-6 py-4 hover:bg-gray-50">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <span class="font-mono text-sm font-medium text-gray-900">
                                        {{ $dispatch->case->short_id }}
                                    </span>
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full 
                                        @if ($dispatch->case->status === 'DISPATCHED') bg-yellow-100
                                        @elseif($dispatch->case->status === 'ON_THE_WAY') text-blue-800
                                        @elseif($dispatch->case->status === 'ON_SCENE') @endif">
                                        {{ str_replace('_', ' ', $dispatch->case->status) }}
                                    </span>
                                    <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded">
                                        {{ $dispatch->case->category }}
                                    </span>
                                </div>

                                <p class="text-sm text-gray-600 mb-2">
                                    <strong>Lokasi:</strong> {{ $dispatch->case->locator_text ?? 'N/A' }}
                                </p>

                                @if ($dispatch->notes)
                                    <p class="text-sm text-gray-600 mb-2">
                                        <strong>Instruksi:</strong> {{ $dispatch->notes }}
                                    </p>
                                @endif

                                <p class="text-xs text-gray-500">
                                    Ditugaskan: {{ $dispatch->assigned_at->diffForHumans() }} •
                                    Unit: {{ $dispatch->unit->name }}
                                </p>
                            </div>

                            <div class="mt-4 sm:mt-0 sm:ml-4 flex flex-col space-y-2">
                                <a href="{{ route('petugas.case.show', $dispatch->case->id) }}"
                                    class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 text-center whitespace-nowrap">
                                    Lihat Detail
                                </a>
                                @if ($dispatch->case->lat && $dispatch->case->lon)
                                    <a href="https://www.google.com/maps?q={{ $dispatch->case->lat }},{{ $dispatch->case->lon }}"
                                        target="_blank"
                                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 text-center whitespace-nowrap">
                                        Buka Maps
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="mt-2 text-sm">Tidak ada kasus aktif</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Completed Cases -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Riwayat Kasus</h2>
                <p class="text-sm text-gray-500 mt-1">Kasus yang sudah selesai</p>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($completedCases->take(10) as $dispatch)
                    <div class="px-6 py-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <span class="font-mono text-sm font-medium text-gray-900">
                                        {{ $dispatch->case->short_id }}
                                    </span>
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full 
                                        @if ($dispatch->case->status === 'CLOSED') bg-green-100 text-green-800
                                        @else @endif">
                                        {{ $dispatch->case->status }}
                                    </span>
                                </div>

                                <p class="text-sm text-gray-600 mb-2">
                                    {{ $dispatch->case->locator_text ?? 'N/A' }}
                                </p>

                                <p class="text-xs text-gray-500">
                                    Selesai:
                                    {{ $dispatch->case->closed_at ? $dispatch->case->closed_at->diffForHumans() : 'N/A' }}
                                </p>
                            </div>

                            <div class="ml-4">
                                <a href="{{ route('petugas.case.show', $dispatch->case->id) }}"
                                    class="text-sm text-red-600 hover:text-red-900">
                                    Lihat Detail →
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        <p class="text-sm">Belum ada riwayat kasus</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
