<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Scanner Section --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold">📱 Scan QR Code Peserta</h2>
                <div class="flex items-center gap-2">
                    <span id="scanner-status" class="inline-flex h-3 w-3 rounded-full bg-gray-400"></span>
                    <span id="scanner-status-text" class="text-sm text-gray-600 dark:text-gray-400">Menginisialisasi...</span>
                </div>
            </div>

            {{-- Camera Permission Warning --}}
            <div id="camera-warning" class="mb-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg hidden">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h3 class="font-semibold text-yellow-800 dark:text-yellow-300">Izin Kamera Diperlukan</h3>
                        <p class="text-sm text-yellow-700 dark:text-yellow-400 mt-1">
                            Browser meminta izin untuk mengakses kamera. Silakan klik <strong>"Allow"</strong> atau <strong>"Izinkan"</strong> pada popup yang muncul.
                        </p>
                        <p class="text-sm text-yellow-700 dark:text-yellow-400 mt-2">
                            💡 <strong>Tips:</strong> Untuk keamanan, gunakan HTTPS atau localhost. Jika masih bermasalah, gunakan input manual di bawah.
                        </p>
                    </div>
                </div>
            </div>

            {{-- HTTPS Warning --}}
            <div id="https-warning" class="mb-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg hidden">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h3 class="font-semibold text-blue-800 dark:text-blue-300">Gunakan HTTPS untuk Scanner Optimal</h3>
                        <p class="text-sm text-blue-700 dark:text-blue-400 mt-1">
                            Kamera bekerja lebih baik di HTTPS. Untuk testing, Anda bisa gunakan:
                        </p>
                        <ul class="text-sm text-blue-700 dark:text-blue-400 mt-2 ml-4 list-disc">
                            <li>Ngrok: <code class="bg-blue-100 dark:bg-blue-900 px-1 rounded">ngrok http 8000</code></li>
                            <li>Laravel Valet: <code class="bg-blue-100 dark:bg-blue-900 px-1 rounded">valet secure</code></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            {{-- Video Preview --}}
            <div class="mb-6">
                <div class="relative max-w-md mx-auto">
                    <video id="preview" class="w-full rounded-lg border-4 border-gray-200 dark:border-gray-700" style="min-height: 300px; background: #f3f4f6;"></video>
                    <div id="scanner-overlay" class="absolute inset-0 pointer-events-none hidden">
                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 border-4 border-primary-500 rounded-lg"></div>
                    </div>
                    {{-- Loading Indicator --}}
                    <div id="camera-loading" class="absolute inset-0 flex items-center justify-center bg-gray-100 dark:bg-gray-700 rounded-lg">
                        <div class="text-center">
                            <svg class="animate-spin h-12 w-12 text-primary-600 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Memuat kamera...</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Manual Input --}}
            <div class="max-w-md mx-auto">
                <label class="block text-sm font-medium mb-2">Atau masukkan kode manual:</label>
                <x-filament::input.wrapper>
                    <x-filament::input
                        type="text"
                        wire:model.live="scannedCode"
                        placeholder="Ketik atau scan kode unik peserta"
                        class="w-full text-center text-lg"
                        id="manual-code-input"
                    />
                </x-filament::input.wrapper>

                <div class="mt-4 flex gap-3">
                    <x-filament::button
                        wire:click="checkIn"
                        color="primary"
                        class="flex-1"
                        size="lg"
                    >
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Check-in
                        </span>
                    </x-filament::button>

                    <x-filament::button
                        wire:click="resetScan"
                        color="gray"
                        size="lg"
                    >
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Reset
                        </span>
                    </x-filament::button>
                </div>
            </div>
        </div>

        {{-- Participant Info --}}
        @if($participantData)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border-l-4 
                    {{ $participantData->is_checked_in ? 'border-green-500' : 'border-blue-500' }}">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold">👤 Informasi Peserta</h3>
                @if($participantData->is_checked_in)
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Sudah Check-in
                    </span>
                @endif
            </div>
            
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Nama Lengkap</dt>
                    <dd class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $participantData->name }}</dd>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Email</dt>
                    <dd class="text-lg text-gray-900 dark:text-gray-100">{{ $participantData->email }}</dd>
                </div>
                
                @if($participantData->phone)
                <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Nomor Telepon</dt>
                    <dd class="text-lg text-gray-900 dark:text-gray-100">{{ $participantData->phone }}</dd>
                </div>
                @endif
                
                <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Kode Unik</dt>
                    <dd class="text-sm font-mono text-gray-900 dark:text-gray-100 break-all">{{ $participantData->unique_code }}</dd>
                </div>
                
                @if($participantData->checked_in_at)
                <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Waktu Check-in</dt>
                    <dd class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        {{ $participantData->checked_in_at->format('l, d F Y - H:i:s') }}
                    </dd>
                </div>
                @endif
            </dl>
        </div>
        @endif

        {{-- Statistics --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Peserta</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ \App\Models\Participant::count() }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Sudah Check-in</p>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ \App\Models\Participant::where('is_checked_in', true)->count() }}</p>
                    </div>
                    <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                        <svg class="w-8 h-8 text-green-600 dark:text-green-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Belum Check-in</p>
                        <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ \App\Models\Participant::where('is_checked_in', false)->count() }}</p>
                    </div>
                    <div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-full">
                        <svg class="w-8 h-8 text-orange-600 dark:text-orange-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- QR Scanner Script --}}
    @push('scripts')
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        let html5QrCode;
        let isScanning = false;

        function updateStatus(status, text) {
            const statusEl = document.getElementById('scanner-status');
            const statusTextEl = document.getElementById('scanner-status-text');
            
            statusEl.className = 'inline-flex h-3 w-3 rounded-full';
            
            if (status === 'active') {
                statusEl.classList.add('bg-green-500', 'animate-pulse');
                statusTextEl.textContent = text || 'Scanner Aktif';
            } else if (status === 'error') {
                statusEl.classList.add('bg-red-500');
                statusTextEl.textContent = text || 'Error';
            } else {
                statusEl.classList.add('bg-gray-400');
                statusTextEl.textContent = text || 'Offline';
            }
        }

        function onScanSuccess(decodedText, decodedResult) {
            if (isScanning) return;
            isScanning = true;

            if (navigator.vibrate) {
                navigator.vibrate(200);
            }

            @this.set('scannedCode', decodedText);
            @this.call('checkIn').then(() => {
                setTimeout(() => {
                    isScanning = false;
                }, 2000);
            });
        }

        function onScanFailure(error) {
            // Ignore
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Check if HTTPS
            if (window.location.protocol !== 'https:' && window.location.hostname !== 'localhost' && window.location.hostname !== '127.0.0.1') {
                document.getElementById('https-warning').classList.remove('hidden');
            }

            html5QrCode = new Html5Qrcode("preview");
            
            Html5Qrcode.getCameras().then(cameras => {
                if (cameras && cameras.length) {
                    document.getElementById('camera-loading').style.display = 'none';
                    
                    let cameraId = cameras[cameras.length - 1].id;
                    
                    html5QrCode.start(
                        cameraId,
                        {
                            fps: 10,
                            qrbox: { width: 250, height: 250 },
                            aspectRatio: 1.0
                        },
                        onScanSuccess,
                        onScanFailure
                    ).then(() => {
                        updateStatus('active', 'Scanner Aktif');
                        document.getElementById('scanner-overlay').classList.remove('hidden');
                    }).catch(err => {
                        console.error('Error starting scanner:', err);
                        document.getElementById('camera-loading').style.display = 'none';
                        document.getElementById('camera-warning').classList.remove('hidden');
                        updateStatus('error', 'Kamera Diblokir');
                    });
                } else {
                    document.getElementById('camera-loading').style.display = 'none';
                    alert('Tidak ada kamera yang terdeteksi.');
                    updateStatus('error', 'Kamera Tidak Ditemukan');
                }
            }).catch(err => {
                console.error('Error getting cameras:', err);
                document.getElementById('camera-loading').style.display = 'none';
                document.getElementById('camera-warning').classList.remove('hidden');
                updateStatus('error', 'Izin Ditolak');
            });
        });

        window.addEventListener('auto-reset', () => {
            setTimeout(() => {
                @this.call('resetScan');
            }, 3000);
        });

        window.addEventListener('beforeunload', function() {
            if (html5QrCode) {
                html5QrCode.stop().catch(err => console.log(err));
            }
        });

        document.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                @this.call('checkIn');
            }
        });
    </script>
    @endpush
</x-filament-panels::page>