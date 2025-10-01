@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Kirim WhatsApp ke Keluarga</h1>
            <p class="text-gray-600">Kirim pesan WhatsApp ke keluarga pelapor untuk memberikan update kasus</p>
        </div>

        <!-- Case Info Card -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 rounded-full bg-red-500 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="text-lg font-medium text-gray-900">Kasus {{ $case->short_id }}</div>
                        <div class="text-sm text-gray-500">{{ $case->created_at->format('d F Y, H:i') }} WIB</div>
                        <div class="mt-2 space-y-1">
                            <div class="text-sm text-gray-600">
                                <span class="font-medium">Kategori:</span>
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
                            </div>
                            <div class="text-sm text-gray-600">
                                <span class="font-medium">Status:</span>
                                <x-status-badge :status="$case->status" />
                            </div>
                            <div class="text-sm text-gray-600">
                                <span class="font-medium">Lokasi:</span> {{ $case->location ?: $case->locator_text }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reporter Info Card -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                @php
                    $contactUser = $case->reporterUser;
                    $contactPhone = $case->reporterUser?->phone ?? $case->phone;
                    $contactName = $case->reporterUser?->name ?? 'Guest Reporter';
                @endphp

                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12">
                        <div class="h-12 w-12 rounded-full bg-blue-500 flex items-center justify-center">
                            <span class="text-lg font-medium text-white">
                                {{ strtoupper(substr($contactName, 0, 1)) }}
                            </span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="text-lg font-medium text-gray-900">{{ $contactName }}</div>
                        @if ($contactUser)
                            <div class="text-sm text-gray-500">{{ $contactUser->email }}</div>
                        @endif
                        @if ($contactPhone)
                            <div class="text-sm text-gray-500">📱 {{ $contactPhone }}</div>
                        @endif
                        @if ($contactUser && $contactUser->citizenProfile)
                            <div class="mt-2 space-y-1">
                                @if ($contactUser->citizenProfile->whatsapp_keluarga)
                                    <div class="text-sm text-gray-600">
                                        <span class="font-medium">WhatsApp Keluarga:</span>
                                        <span
                                            class="text-green-600">{{ $contactUser->citizenProfile->whatsapp_keluarga }}</span>
                                    </div>
                                @endif
                                @if ($contactUser->citizenProfile->hubungan)
                                    <div class="text-sm text-gray-600">
                                        <span class="font-medium">Hubungan:</span>
                                        {{ $contactUser->citizenProfile->hubungan_display }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @unless ($contactUser && $contactUser->citizenProfile && $contactUser->citizenProfile->whatsapp_keluarga)
            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Nomor WhatsApp Keluarga Tidak Tersedia</h3>
                        <p class="mt-1 text-sm text-yellow-700">Pelapor tidak memiliki nomor WhatsApp keluarga yang terdaftar
                            dalam profil citizen.</p>
                    </div>
                </div>
            </div>
        @endunless

        @if ($contactPhone)
            <!-- WhatsApp Form -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Kirim Pesan WhatsApp</h3>

                    <form id="whatsappForm">
                        @csrf

                        <!-- Template Messages -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Template Pesan</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-3">
                                <button type="button"
                                    class="template-btn text-left p-3 border border-gray-300 rounded-md hover:bg-gray-50 text-sm"
                                    data-template="Halo, saya dari Tim Emergency SafePoint. Terkait laporan kasus {{ $case->short_id }} yang Anda buat pada {{ $case->created_at->format('d F Y, H:i') }}. Mohon konfirmasi kondisi terkini lokasi tersebut.">
                                    <div class="font-medium text-gray-900">Status Update</div>
                                    <div class="text-gray-500">Meminta update kondisi</div>
                                </button>

                                <button type="button"
                                    class="template-btn text-left p-3 border border-gray-300 rounded-md hover:bg-gray-50 text-sm"
                                    data-template="Terima kasih telah melaporkan kasus {{ $case->short_id }}. Tim emergency kami telah menerima laporan dan sedang dalam proses verifikasi. Kami akan segera menindaklanjuti.">
                                    <div class="font-medium text-gray-900">Konfirmasi Penerimaan</div>
                                    <div class="text-gray-500">Konfirmasi laporan diterima</div>
                                </button>

                                <button type="button"
                                    class="template-btn text-left p-3 border border-gray-300 rounded-md hover:bg-gray-50 text-sm"
                                    data-template="Update kasus {{ $case->short_id }}: Tim emergency telah dikirim ke lokasi. ETA sekitar 10-15 menit. Mohon tetap di lokasi yang aman dan tunggu kedatangan tim.">
                                    <div class="font-medium text-gray-900">Tim Dispatched</div>
                                    <div class="text-gray-500">Notifikasi tim dikirim</div>
                                </button>

                                <button type="button"
                                    class="template-btn text-left p-3 border border-gray-300 rounded-md hover:bg-gray-50 text-sm"
                                    data-template="Kasus {{ $case->short_id }} telah ditangani oleh tim emergency kami. Terima kasih telah melaporkan. Jika ada hal lain yang perlu disampaikan, silakan hubungi kami kembali.">
                                    <div class="font-medium text-gray-900">Kasus Selesai</div>
                                    <div class="text-gray-500">Notifikasi penyelesaian</div>
                                </button>
                            </div>
                        </div>

                        <!-- Message Input -->
                        <div class="mb-4">
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Pesan</label>
                            <textarea id="message" name="message" rows="6" required
                                class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"
                                placeholder="Tulis pesan untuk pelapor..."></textarea>
                            <div class="mt-1 text-sm text-gray-500">
                                <span id="charCount">0</span>/1000 karakter
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex space-x-3">
                            <button type="submit" id="sendBtn"
                                class="flex-1 bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488" />
                                </svg>
                                Kirim via WhatsApp
                            </button>

                            <a href="{{ route('cases.show', $case) }}"
                                class="bg-gray-200 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <!-- Success/Error Messages -->
    <div id="messageContainer" class="fixed top-4 right-4 z-50" style="display: none;">
        <div id="messageAlert" class="max-w-sm rounded-md shadow-lg">
            <div class="p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg id="messageIcon" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p id="messageText" class="text-sm font-medium"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const messageTextarea = document.getElementById('message');
            const charCount = document.getElementById('charCount');
            const templateButtons = document.querySelectorAll('.template-btn');
            const whatsappForm = document.getElementById('whatsappForm');
            const sendBtn = document.getElementById('sendBtn');

            // Character counter
            messageTextarea.addEventListener('input', function() {
                const count = this.value.length;
                charCount.textContent = count;

                if (count > 1000) {
                    charCount.className = 'text-red-500';
                    sendBtn.disabled = true;
                } else {
                    charCount.className = 'text-gray-500';
                    sendBtn.disabled = false;
                }
            });

            // Template button handlers
            templateButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const template = this.getAttribute('data-template');
                    messageTextarea.value = template;
                    messageTextarea.dispatchEvent(new Event('input'));

                    // Add visual feedback
                    templateButtons.forEach(btn => btn.classList.remove('ring-2', 'ring-red-500'));
                    this.classList.add('ring-2', 'ring-red-500');
                });
            });

            // Form submission
            whatsappForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                sendBtn.disabled = true;
                sendBtn.innerHTML =
                    '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Mengirim...';

                fetch('{{ route('cases.send-whatsapp', $case) }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showMessage(data.message, 'success');
                            // Redirect to WhatsApp after short delay
                            setTimeout(() => {
                                window.open(data.whatsapp_url, '_blank');
                            }, 1000);
                        } else {
                            showMessage(data.message || 'Terjadi kesalahan', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showMessage('Terjadi kesalahan saat mengirim pesan', 'error');
                    })
                    .finally(() => {
                        sendBtn.disabled = false;
                        sendBtn.innerHTML =
                            '<svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/></svg>Kirim via WhatsApp';
                    });
            });

            function showMessage(message, type) {
                const container = document.getElementById('messageContainer');
                const alert = document.getElementById('messageAlert');
                const icon = document.getElementById('messageIcon');
                const text = document.getElementById('messageText');

                text.textContent = message;

                if (type === 'success') {
                    alert.className = 'max-w-sm rounded-md shadow-lg bg-green-50 border border-green-200';
                    icon.className = 'h-5 w-5 text-green-400';
                    text.className = 'text-sm font-medium text-green-800';
                } else {
                    alert.className = 'max-w-sm rounded-md shadow-lg bg-red-50 border border-red-200';
                    icon.className = 'h-5 w-5 text-red-400';
                    text.className = 'text-sm font-medium text-red-800';
                }

                container.style.display = 'block';
                setTimeout(() => {
                    container.style.display = 'none';
                }, 5000);
            }
        });
    </script>
@endsection
