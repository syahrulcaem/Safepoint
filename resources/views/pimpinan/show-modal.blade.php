<!-- Case Detail Modal Content for Pimpinan -->
<div class="row">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-1">Kasus {{ $case->short_id }}</h4>
                <p class="text-muted mb-0">{{ $case->created_at->format('d F Y, H:i') }} WIB</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <x-status-badge :status="$case->status" />
                @if ($case->lat && $case->lon)
                    <a href="https://www.google.com/maps?q={{ $case->lat }},{{ $case->lon }}" target="_blank"
                        class="btn btn-sm btn-outline-primary">
                        <i class="bx bx-map me-1"></i> Maps
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Main Content -->
    <div class="col-lg-8">
        <!-- Case Metadata -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Informasi Kasus</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">ID Kasus</label>
                            <p class="mb-0 fw-medium">{{ $case->short_id }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Status</label>
                            <div><x-status-badge :status="$case->status" /></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Kategori</label>
                            <p class="mb-0">
                                <span
                                    class="badge 
                                    @switch($case->category)
                                        @case('MEDIS') badge-soft-primary @break
                                        @case('KEBAKARAN') badge-soft-danger @break
                                        @case('KRIMINAL') badge-soft-dark @break
                                        @case('UMUM') badge-soft-secondary @break
                                        @case('BENCANA_ALAM') @case('BANJIR') @case('POHON_TUMBANG') badge-soft-warning @break
                                        @case('KECELAKAAN') @case('KEBOCORAN_GAS') badge-soft-info @break
                                        @default badge-soft-secondary
                                    @endswitch
                                ">
                                    @switch($case->category)
                                        @case('MEDIS')
                                            Medis
                                        @break

                                        @case('KEBAKARAN')
                                            Kebakaran
                                        @break

                                        @case('KRIMINAL')
                                            Kriminal
                                        @break

                                        @case('UMUM')
                                            Umum
                                        @break

                                        @case('BENCANA_ALAM')
                                            Bencana Alam
                                        @break

                                        @case('KECELAKAAN')
                                            Kecelakaan
                                        @break

                                        @case('KEBOCORAN_GAS')
                                            Kebocoran Gas
                                        @break

                                        @case('POHON_TUMBANG')
                                            Pohon Tumbang
                                        @break

                                        @case('BANJIR')
                                            Banjir
                                        @break

                                        @default
                                            {{ $case->category }}
                                    @endswitch
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Prioritas</label>
                            <p class="mb-0">
                                @switch($case->priority)
                                    @case('HIGH')
                                        <span class="badge bg-danger">Tinggi</span>
                                    @break

                                    @case('MEDIUM')
                                        <span class="badge bg-warning">Sedang</span>
                                    @break

                                    @case('LOW')
                                        <span class="badge bg-info">Rendah</span>
                                    @break

                                    @default
                                        <span class="badge bg-secondary">{{ $case->priority }}</span>
                                @endswitch
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Nomor Telepon</label>
                            <p class="mb-0">{{ $case->phone ?: '-' }}</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label text-muted">Lokasi</label>
                            <p class="mb-0">{{ $case->location ?: $case->locator_text }}</p>
                        </div>
                    </div>
                    @if ($dispatch->dispatcher)
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Operator yang Dispatch</label>
                                <p class="mb-0">{{ $dispatch->dispatcher->name }}</p>
                            </div>
                        </div>
                    @endif
                    @if ($dispatch->assignedPetugas)
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Petugas Ditugaskan</label>
                                <p class="mb-0">{{ $dispatch->assignedPetugas->name }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Dispatch Information -->
        @if ($dispatch->notes)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Catatan dari Operator</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning mb-0">
                        <i class="bx bx-info-circle me-2"></i>
                        {{ $dispatch->notes }}
                    </div>
                </div>
            </div>
        @endif

        <!-- Description -->
        @if ($case->description)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Deskripsi Kasus</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $case->description }}</p>
                </div>
            </div>
        @endif

        <!-- Timeline Events -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Timeline Event</h5>
            </div>
            <div class="card-body">
                @if ($case->caseEvents && $case->caseEvents->count() > 0)
                    <div class="timeline">
                        @foreach ($case->caseEvents as $event)
                            <div class="timeline-item">
                                <div
                                    class="timeline-marker 
                                    @switch($event->action)
                                        @case('CREATED') bg-primary @break
                                        @case('VERIFIED') bg-success @break
                                        @case('DISPATCHED') bg-warning @break
                                        @case('PETUGAS_ASSIGNED') bg-info @break
                                        @case('ON_THE_WAY') bg-info @break
                                        @case('ON_SCENE') bg-dark @break
                                        @case('CLOSED') bg-success @break
                                        @case('CANCELLED') bg-danger @break
                                        @default bg-secondary
                                    @endswitch
                                ">
                                </div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="timeline-title mb-1">
                                                @switch($event->action)
                                                    @case('CREATED')
                                                        Kasus Dibuat
                                                    @break

                                                    @case('VERIFIED')
                                                        Diverifikasi
                                                    @break

                                                    @case('DISPATCHED')
                                                        Dikirim ke Unit
                                                    @break

                                                    @case('PETUGAS_ASSIGNED')
                                                        Petugas Ditugaskan
                                                    @break

                                                    @case('ON_THE_WAY')
                                                        Dalam Perjalanan
                                                    @break

                                                    @case('ON_SCENE')
                                                        Di Lokasi
                                                    @break

                                                    @case('CLOSED')
                                                        Ditutup
                                                    @break

                                                    @case('CANCELLED')
                                                        Dibatalkan
                                                    @break

                                                    @default
                                                        {{ $event->action }}
                                                @endswitch
                                            </h6>
                                            @if ($event->notes)
                                                <p class="timeline-description mb-2">{{ $event->notes }}</p>
                                            @endif
                                            @if ($event->actor)
                                                <small class="text-muted">oleh {{ $event->actor->name }}</small>
                                            @endif
                                        </div>
                                        <small class="text-muted">{{ $event->created_at->format('d/m H:i') }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bx bx-time-five text-muted" style="font-size: 2rem;"></i>
                        <p class="text-muted mt-2 mb-0">Belum ada timeline event untuk kasus ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar Actions -->
    <div class="col-lg-4">
        <!-- Assign Petugas Card -->
        @if (!$dispatch->assigned_petugas_id)
            <div class="card mb-4">
                <div class="card-header" style="background: linear-gradient(45deg, #dc3545 0%, #c82333 100%) !important;">
                    <h5 class="card-title mb-0 text-white">
                        <i class="bx bx-user-plus me-2"></i>Tugaskan Petugas
                    </h5>
                </div>
                <div class="card-body">
                    @if ($availablePetugas->isEmpty())
                        <div class="text-center py-4">
                            <i class="bx bx-user-x text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mt-2 mb-0">
                                Tidak ada petugas di unit ini. 
                                <a href="{{ route('pimpinan.petugas') }}" class="text-danger">Tambah petugas</a>
                            </p>
                        </div>
                    @else
                        <form method="POST" action="{{ route('pimpinan.assign', $dispatch->id) }}" id="assignFormModal">
                            @csrf
                            <div class="mb-3">
                                <label for="assigned_petugas_id_modal" class="form-label">Pilih Petugas <span class="text-danger">*</span></label>
                                <select name="assigned_petugas_id" id="assigned_petugas_id_modal" required class="form-select">
                                    <option value="">-- Pilih Petugas --</option>
                                    @foreach ($availablePetugas as $petugas)
                                        <option value="{{ $petugas->id }}">
                                            {{ $petugas->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="notes_modal" class="form-label">Catatan untuk Petugas (Opsional)</label>
                                <textarea name="notes" id="notes_modal" class="form-control" rows="3" 
                                    placeholder="Instruksi khusus untuk petugas...">{{ $dispatch->notes }}</textarea>
                            </div>
                            <button type="submit" class="btn w-100" style="background: linear-gradient(45deg, #dc3545 0%, #c82333 100%) !important; color: white; border: none;">
                                <i class="bx bx-check me-1"></i> Tugaskan Petugas
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @else
            <!-- Petugas Already Assigned -->
            <div class="card mb-4">
                <div class="card-header" style="background: linear-gradient(45deg, #198754 0%, #146c43 100%) !important;">
                    <h5 class="card-title mb-0 text-white">
                        <i class="bx bx-check-circle me-2"></i>Petugas Ditugaskan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-sm me-3">
                            <div class="avatar-title rounded-circle" style="background: linear-gradient(45deg, #198754 0%, #146c43 100%) !important; color: white;">
                                <i class="bx bx-user font-size-18"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $dispatch->assignedPetugas->name }}</h6>
                            <small class="text-muted">{{ $dispatch->assignedPetugas->phone }}</small>
                        </div>
                    </div>
                    <div class="alert alert-success mb-0">
                        <small>
                            <strong>Ditugaskan:</strong><br>
                            {{ $dispatch->assigned_at->format('d F Y, H:i') }} WIB<br>
                            <span class="text-muted">{{ $dispatch->assigned_at->diffForHumans() }}</span>
                        </small>
                    </div>
                </div>
            </div>
        @endif

        <!-- Quick Info -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Informasi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Durasi:</span>
                    <span class="fw-medium">
                        @if ($case->closed_at)
                            {{ $case->created_at->diffInHours($case->closed_at) }} jam
                        @else
                            {{ $case->created_at->diffForHumans() }}
                        @endif
                    </span>
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Dispatch:</span>
                    <span class="fw-medium">{{ $dispatch->dispatched_at->format('d/m H:i') }}</span>
                </div>

                @if ($case->lat && $case->lon)
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Koordinat:</span>
                        <span class="fw-medium font-monospace" style="font-size: 0.8rem;">
                            {{ number_format($case->lat, 6) }}, {{ number_format($case->lon, 6) }}
                        </span>
                    </div>
                @endif

                @if ($case->accuracy)
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Akurasi:</span>
                        <span class="fw-medium">{{ $case->accuracy }}m</span>
                    </div>
                @endif

                <div class="d-flex justify-content-between">
                    <span class="text-muted">Provider:</span>
                    <span class="fw-medium">{{ ucfirst($case->locator_provider ?? 'N/A') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 25px;
    }

    .timeline-marker {
        position: absolute;
        left: -25px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .timeline-content {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border-left: 3px solid #dee2e6;
    }

    .timeline-title {
        color: #495057;
        font-size: 14px;
        font-weight: 600;
    }

    .timeline-description {
        color: #6c757d;
        font-size: 13px;
        line-height: 1.5;
    }
</style>
