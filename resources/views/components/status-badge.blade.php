@props(['status'])

@php
    $classes = match ($status) {
        'NEW' => 'bg-red-100 text-red-800',
        'VERIFIED' => 'bg-red-200 text-red-900',
        'DISPATCHED' => 'bg-red-300 text-red-900',
        'ON_THE_WAY' => 'bg-red-400 text-white',
        'ON_SCENE' => 'bg-red-500 text-white',
        'CLOSED' => 'bg-red-600 text-white',
        'CANCELLED' => 'bg-red-800 text-white',
        default => 'bg-red-100 text-red-800',
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

<span
    {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium $classes"]) }}>
    {{ $labels[$status] ?? $status }}
</span>
