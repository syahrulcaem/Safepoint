@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Kirim WhatsApp</h1>
            <p class="text-gray-600">Kirim pesan WhatsApp ke {{ $user->name }}</p>
        </div>

        <!-- User Info Card -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12">
                        <div class="h-12 w-12 rounded-full bg-red-500 flex items-center justify-center">
                            <span class="text-lg font-medium text-white">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="text-lg font-medium text-gray-900">{{ $user->name }}</div>
                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                        @if ($user->phone)
                            <div class="text-sm text-gray-500">📱 {{ $user->phone }}</div>
                        @endif
                        @if ($user->citizenProfile)
                            <div class="mt-2 space-y-1">
                                @if ($user->citizenProfile->whatsapp_keluarga)
                                    <div class="text-sm text-gray-600">
                                        <span class="font-medium">WhatsApp Keluarga:</span>
                                        <span class="text-green-600">{{ $user->citizenProfile->whatsapp_keluarga }}</span>
                                    </div>
                                @endif
                                @if ($user->citizenProfile->hubungan)
                                    <div class="text-sm text-gray-600">
                                        <span class="font-medium">Hubungan:</span>
                                        {{ $user->citizenProfile->hubungan_display }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @unless ($user->phone)
            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Nomor telepon tidak tersedia</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            User ini belum mengisi nomor telepon, sehingga tidak dapat dikirim WhatsApp.
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- WhatsApp Form -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <form id="whatsappForm" action="{{ route('users.send-whatsapp', $user) }}" method="POST">
                        @csrf

                        <!-- Template Messages -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Template Pesan</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                <button type="button"
                                    class="template-btn text-left p-3 border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    data-template="Halo {{ $user->name }}, ini adalah pesan dari SafePoint Admin. Ada informasi penting yang perlu kami sampaikan.">
                                    <div class="font-medium text-sm">Pesan Umum</div>
                                    <div class="text-xs text-gray-500">Pesan standar dari admin</div>
                                </button>

                                <button type="button"
                                    class="template-btn text-left p-3 border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    data-template="PENTING: Halo {{ $user->name }}, terdapat situasi darurat di area Anda. Harap waspada dan ikuti instruksi keamanan.">
                                    <div class="font-medium text-sm">Peringatan Darurat</div>
                                    <div class="text-xs text-gray-500">Notifikasi situasi darurat</div>
                                </button>

                                <button type="button"
                                    class="template-btn text-left p-3 border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    data-template="Halo {{ $user->name }}, terima kasih telah melaporkan kejadian melalui aplikasi SafePoint. Laporan Anda telah kami terima dan sedang ditindaklanjuti.">
                                    <div class="font-medium text-sm">Konfirmasi Laporan</div>
                                    <div class="text-xs text-gray-500">Konfirmasi penerimaan laporan</div>
                                </button>

                                <button type="button"
                                    class="template-btn text-left p-3 border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    data-template="Halo {{ $user->name }}, mohon segera update profil Anda di aplikasi SafePoint untuk melengkapi data keluarga dan informasi darurat.">
                                    <div class="font-medium text-sm">Update Profil</div>
                                    <div class="text-xs text-gray-500">Reminder update data</div>
                                </button>
                            </div>
                        </div>

                        <!-- Message Textarea -->
                        <div class="mb-6">
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Pesan</label>
                            <textarea id="message" name="message" rows="6"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                placeholder="Tulis pesan Anda di sini..." required></textarea>
                            <div class="mt-1 text-sm text-gray-500">
                                Maksimal 1000 karakter. <span id="charCount">0</span>/1000
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-between">
                            <a href="{{ route('users.index') }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Kembali
                            </a>

                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                    </path>
                                </svg>
                                Kirim WhatsApp
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endunless
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const messageTextarea = document.getElementById('message');
            const charCount = document.getElementById('charCount');
            const templateButtons = document.querySelectorAll('.template-btn');
            const whatsappForm = document.getElementById('whatsappForm');

            // Character counter
            messageTextarea.addEventListener('input', function() {
                const length = this.value.length;
                charCount.textContent = length;

                if (length > 1000) {
                    charCount.classList.add('text-red-500');
                } else {
                    charCount.classList.remove('text-red-500');
                }
            });

            // Template button handlers
            templateButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const template = this.getAttribute('data-template');
                    messageTextarea.value = template;
                    messageTextarea.dispatchEvent(new Event('input'));

                    // Visual feedback
                    templateButtons.forEach(btn => btn.classList.remove('ring-2', 'ring-red-500',
                        'border-red-500'));
                    this.classList.add('ring-2', 'ring-red-500', 'border-red-500');
                });
            });

            // Form submission
            whatsappForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const submitButton = this.querySelector('button[type="submit"]');
                const originalText = submitButton.innerHTML;

                // Disable button and show loading
                submitButton.disabled = true;
                submitButton.innerHTML =
                    '<svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Mengirim...';

                fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Open WhatsApp in new tab
                            window.open(data.whatsapp_url, '_blank');

                            // Show success message
                            alert('WhatsApp berhasil dibuka! Pesan telah disiapkan untuk dikirim.');

                            // Redirect back to users index
                            window.location.href = '{{ route('users.index') }}';
                        } else {
                            alert('Terjadi kesalahan: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat memproses permintaan.');
                    })
                    .finally(() => {
                        // Re-enable button
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                    });
            });
        });
    </script>
@endsection
