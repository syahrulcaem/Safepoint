@props(['status'])

@php
    $classes = match ($status) {
        'NEW' => 'badge bg-danger',
        'VERIFIED' => 'badge bg-warning',
        'DISPATCHED' => 'badge bg-primary',
        'ON_THE_WAY' => 'badge bg-info',
        'ON_SCENE' => 'badge bg-dark',
        'CLOSED' => 'badge bg-success',
        'CANCELLED' => 'badge bg-secondary',
        default => 'badge bg-secondary',
    };

    $labels = [
        'NEW' => 'Baru',
        'VERIFIED' => 'Terverifikasi',
        'DISPATCHED' => 'Dikirim',
        'ON_THE_WAY' => 'Dalam Perjalanan',
        'ON_SCENE' => 'Di Lokasi',
        'CLOSED' => 'Selesai',
        'CANCELLED' => 'Dibatalkan',
    ];
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $labels[$status] ?? $status }}
</span>
