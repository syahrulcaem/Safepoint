@extends('layouts.app')

@section('title', 'Dashboard Pimpinan')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Dashboard Pimpinan</h1>
                <p class="text-sm text-gray-600 mt-1">Unit: <span class="font-semibold">{{ $unit->name }}</span>
                    ({{ $unit->type }})</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('pimpinan.petugas') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Kelola Petugas
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm font-medium text-gray-500">Dispatch Pending</div>
                <div class="mt-2 text-3xl font-bold text-orange-600">{{ $pendingDispatches->count() }}</div>
                <p class="text-xs text-gray-500 mt-1">Menunggu penugasan petugas</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm font-medium text-gray-500">Sudah Ditugaskan</div>
                <div class="mt-2 text-3xl font-bold text-green-600">{{ $assignedDispatches->count() }}</div>
                <p class="text-xs text-gray-500 mt-1">Petugas sudah ditugaskan</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm font-medium text-gray-500">Total Petugas</div>
                <div class="mt-2 text-3xl font-bold text-blue-600">{{ $availablePetugas->count() }}</div>
                <p class="text-xs text-gray-500 mt-1">Petugas di unit Anda</p>
            </div>
        </div>

        <!-- Pending Dispatches -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Dispatch Menunggu Penugasan</h2>
                <p class="text-sm text-gray-500 mt-1">Tugaskan petugas untuk menangani kasus ini</p>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($pendingDispatches as $dispatch)
                    <div class="px-6 py-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <span class="font-mono text-sm font-medium text-gray-900">
                                        {{ $dispatch->case->short_id }}
                                    </span>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-orange-100 text-orange-800">
                                        {{ $dispatch->case->status }}
                                    </span>
                                </div>

                                <p class="text-sm text-gray-600 mb-2">
                                    <strong>Lokasi:</strong> {{ $dispatch->case->locator_text ?? 'N/A' }}
                                </p>

                                @if ($dispatch->notes)
                                    <p class="text-sm text-gray-600 mb-2">
                                        <strong>Catatan Operator:</strong> {{ $dispatch->notes }}
                                    </p>
                                @endif

                                <p class="text-xs text-gray-500">
                                    Dispatch oleh: {{ $dispatch->dispatcher->name }} •
                                    {{ $dispatch->dispatched_at->diffForHumans() }}
                                </p>
                            </div>

                            <div class="ml-4 flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                                <a href="{{ route('pimpinan.case.show', $dispatch->case->id) }}"
                                    class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 text-center whitespace-nowrap">
                                    Lihat Detail
                                </a>
                                <button onclick="showAssignModal({{ $dispatch->id }}, '{{ $dispatch->case->short_id }}')"
                                    class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 whitespace-nowrap">
                                    Tugaskan Petugas
                                </button>
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
                        <p class="mt-2 text-sm">Tidak ada dispatch yang menunggu penugasan</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Assigned Dispatches -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Riwayat Penugasan</h2>
                <p class="text-sm text-gray-500 mt-1">Petugas yang sudah ditugaskan</p>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($assignedDispatches as $dispatch)
                    <div class="px-6 py-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <span class="font-mono text-sm font-medium text-gray-900">
                                        {{ $dispatch->case->short_id }}
                                    </span>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                        {{ $dispatch->case->status }}
                                    </span>
                                </div>

                                <p class="text-sm text-gray-600 mb-1">
                                    <strong>Petugas:</strong> {{ $dispatch->assignedPetugas->name }}
                                </p>

                                <p class="text-sm text-gray-600 mb-2">
                                    <strong>Lokasi:</strong> {{ $dispatch->case->locator_text ?? 'N/A' }}
                                </p>

                                <p class="text-xs text-gray-500">
                                    Ditugaskan: {{ $dispatch->assigned_at->diffForHumans() }}
                                </p>
                            </div>

                            <div class="ml-4">
                                <a href="{{ route('pimpinan.case.show', $dispatch->case->id) }}"
                                    class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 inline-block">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                        <p class="mt-2 text-sm">Belum ada petugas yang ditugaskan</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Assign Modal -->
    <div id="assignModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Tugaskan Petugas</h3>
                <p class="text-sm text-gray-600 mt-1">
                    Case ID: <span id="modalCaseId" class="font-mono font-medium"></span>
                </p>
            </div>

            <form id="assignForm" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="assigned_petugas_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Petugas
                    </label>
                    <select name="assigned_petugas_id" id="assigned_petugas_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                        <option value="">-- Pilih Petugas --</option>
                        @foreach ($availablePetugas as $petugas)
                            <option value="{{ $petugas->id }}">{{ $petugas->name }}</option>
                        @endforeach
                    </select>
                    @if ($availablePetugas->isEmpty())
                        <p class="mt-2 text-xs text-red-600">Tidak ada petugas di unit ini. <a
                                href="{{ route('pimpinan.petugas') }}" class="underline">Tambah petugas</a></p>
                    @endif
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan untuk Petugas (Opsional)
                    </label>
                    <textarea name="notes" id="notes" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                        placeholder="Instruksi khusus untuk petugas..."></textarea>
                </div>

                <div class="flex space-x-3">
                    <button type="submit"
                        class="flex-1 bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Tugaskan
                    </button>
                    <button type="button" onclick="closeAssignModal()"
                        class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showAssignModal(dispatchId, caseId) {
            const modal = document.getElementById('assignModal');
            const form = document.getElementById('assignForm');
            const modalCaseId = document.getElementById('modalCaseId');

            // Set form action
            form.action = `/pimpinan/dispatches/${dispatchId}/assign`;

            // Set case ID display
            modalCaseId.textContent = caseId;

            // Show modal
            modal.classList.remove('hidden');
        }

        function closeAssignModal() {
            const modal = document.getElementById('assignModal');
            modal.classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('assignModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAssignModal();
            }
        });
    </script>
@endsection
