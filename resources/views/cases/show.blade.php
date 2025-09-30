@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-start">
            <div>
                <div class="flex items-center space-x-3">
                    <h1 class="text-2xl font-bold text-gray-900">Kasus {{ $case->short_id }}</h1>
                    <x-status-badge :status="$case->status" />
                </div>
                <p class="text-gray-600">{{ $case->created_at->format('d F Y, H:i') }}</p>
            </div>
            <a href="{{ route('cases.index') }}" class="text-red-600 hover:text-red-500">
                ← Kembali ke daftar
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Case Details -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Kasus</h3>

                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Kategori</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $case->category }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Telepon</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $case->phone ?? '-' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Lokasi</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $case->locator_text }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Koordinat</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $case->lat }}, {{ $case->lon }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Pelapor</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $case->reporterUser?->name ?? 'Guest' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Unit Ditugaskan</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $case->assignedUnit?->name ?? '-' }}</dd>
                        </div>
                    </dl>

                    <div class="mt-4">
                        <a href="https://www.google.com/maps?q={{ $case->lat }},{{ $case->lon }}" target="_blank"
                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Buka di Maps
                        </a>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Timeline Kejadian</h3>

                    @if ($case->caseEvents->count() > 0)
                        <div class="flow-root">
                            <ul class="-mb-8">
                                @foreach ($case->caseEvents as $index => $event)
                                    <li>
                                        <div class="relative pb-8">
                                            @if (!$loop->last)
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span
                                                        class="h-8 w-8 rounded-full bg-red-500 flex items-center justify-center ring-8 ring-white">
                                                        <svg class="w-4 h-4 text-white" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-900 font-medium">{{ $event->event }}
                                                        </p>
                                                        @if ($event->actor)
                                                            <p class="text-sm text-gray-500">oleh {{ $event->actor->name }}
                                                            </p>
                                                        @endif
                                                        @if ($event->meta)
                                                            <div class="mt-1 text-sm text-gray-600">
                                                                @foreach ($event->meta as $key => $value)
                                                                    <div>{{ $key }}: {{ $value }}</div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                        {{ $event->created_at->format('d/m/Y H:i') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">Belum ada aktivitas</p>
                    @endif
                </div>
            </div>

            <!-- Actions Sidebar -->
            <div class="space-y-6">
                @if (auth()->user()->canManageCases())
                    <!-- Actions -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Aksi</h3>

                        <div class="space-y-3">
                            @if ($case->status === 'NEW')
                                <form method="POST" action="{{ route('cases.verify', $case) }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                                        Verifikasi Kasus
                                    </button>
                                </form>
                            @endif

                            @if (in_array($case->status, ['NEW', 'VERIFIED']))
                                <!-- Dispatch Form -->
                                <form method="POST" action="{{ route('cases.dispatch', $case) }}" class="space-y-3">
                                    @csrf
                                    <div>
                                        <label for="unit_id" class="block text-sm font-medium text-gray-700">Unit</label>
                                        <select name="unit_id" id="unit_id" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                                            <option value="">Pilih Unit</option>
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->name }}
                                                    ({{ $unit->type }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label for="notes"
                                            class="block text-sm font-medium text-gray-700">Catatan</label>
                                        <textarea name="notes" id="notes" rows="3"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                                            placeholder="Catatan untuk petugas..."></textarea>
                                    </div>

                                    <button type="submit"
                                        class="w-full bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                        Kirim ke Unit
                                    </button>
                                </form>
                            @endif

                            @if (in_array($case->status, ['DISPATCHED', 'ON_THE_WAY', 'ON_SCENE']))
                                <form method="POST" action="{{ route('cases.close', $case) }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                                        onclick="return confirm('Yakin ingin menutup kasus ini?')">
                                        Tutup Kasus
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Dispatch History -->
                @if ($case->dispatches->count() > 0)
                    <div class="bg-white shadow rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Riwayat Dispatch</h3>

                        <div class="space-y-3">
                            @foreach ($case->dispatches as $dispatch)
                                <div class="border border-gray-200 rounded-lg p-3">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $dispatch->unit->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $dispatch->unit->type }}</p>
                                            @if ($dispatch->notes)
                                                <p class="text-sm text-gray-600 mt-1">{{ $dispatch->notes }}</p>
                                            @endif
                                        </div>
                                        <div class="text-right text-sm text-gray-500">
                                            <div>{{ $dispatch->created_at->format('d/m H:i') }}</div>
                                            <div>oleh {{ $dispatch->assignedBy->name }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
