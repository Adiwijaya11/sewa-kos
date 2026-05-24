@extends('layouts.dashboard')

@section('title', 'Verifikasi Akun - KosinAja')
@section('header_title', 'Verifikasi Identitas Owner')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Verification Status Banner -->
    @if(!$verification)
        <!-- NOT YET SUBMITTED -->
        <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm space-y-4">
            <div class="flex items-center space-x-3 text-amber-500">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Akun Anda Belum Terverifikasi</h3>
                    <p class="text-xs text-slate-400">Verifikasi KTP & Wajah wajib dilakukan untuk mendapatkan kepercayaan renter</p>
                </div>
            </div>
            
            <p class="text-xs text-slate-500 leading-relaxed">
                KosinAja menerapkan <span class="font-semibold text-slate-700">Anti-Scam Verification System</span> yang ketat. 
                Dengan memverifikasi identitas Anda, properti kos Anda akan mendapatkan lencana <span class="text-emerald-600 font-semibold">Verified Kos ✅</span> yang muncul di halaman depan pencarian, meningkatkan kepercayaan calon penyewa hingga berkali-kali lipat.
            </p>
        </div>
    @elseif($verification->status === 'pending')
        <!-- PENDING REVIEW -->
        <div class="bg-gradient-to-tr from-blue-600/10 to-teal-500/5 rounded-2xl border border-blue-200/50 p-6 shadow-sm space-y-4">
            <div class="flex items-center space-x-3.5 text-blue-600">
                <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-lg">
                    <i class="fa-solid fa-hourglass-half animate-pulse"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Dokumen Sedang Ditinjau Admin</h3>
                    <p class="text-xs text-slate-400">Proses peninjauan biasanya memakan waktu maksimal 1x24 jam</p>
                </div>
            </div>
            
            <p class="text-xs text-slate-500 leading-relaxed">
                Terima kasih telah mengirimkan dokumen Anda! Tim kepatuhan kami sedang melakukan pengecekan keabsahan KTP, foto selfie, serta video verifikasi Anda demi mencegah penipuan berkedok sewa kos.
            </p>
        </div>
    @elseif($verification->status === 'approved')
        <!-- APPROVED / VERIFIED -->
        <div class="bg-gradient-to-tr from-emerald-600/10 to-teal-500/5 rounded-2xl border border-emerald-200/50 p-6 shadow-sm space-y-4">
            <div class="flex items-center space-x-3.5 text-emerald-600">
                <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-lg">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Akun Terverifikasi Resmi</h3>
                    <p class="text-xs text-slate-400">Selamat! Lencana Verified Owner telah disematkan</p>
                </div>
            </div>
            
            <p class="text-xs text-slate-500 leading-relaxed">
                Identitas Anda telah divalidasi oleh sistem administrasi KosinAja. Semua iklan kos Anda kini tayang dengan lambang <span class="text-emerald-600 font-semibold">Verified Kos ✅</span> dan mendapat prioritas teratas di halaman pencarian peta renter.
            </p>
        </div>
    @elseif($verification->status === 'rejected')
        <!-- REJECTED -->
        <div class="bg-red-50 rounded-2xl border border-red-200 p-6 shadow-sm space-y-4">
            <div class="flex items-start space-x-3.5 text-red-600">
                <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-lg flex-shrink-0">
                    <i class="fa-solid fa-circle-xmark text-red-500"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Verifikasi Identitas Ditolak</h3>
                    <p class="text-xs text-slate-400">Pengajuan ditolak dengan alasan berikut:</p>
                    <div class="mt-2 p-3 bg-white rounded-lg border border-red-100 text-red-800 text-xs font-semibold leading-relaxed">
                        <i class="fa-solid fa-comment-dots mr-1.5"></i>{{ $verification->notes ?? 'Dokumen KTP kurang jelas atau buram.' }}
                    </div>
                </div>
            </div>
            
            <p class="text-xs text-slate-500 leading-relaxed">
                Jangan khawatir! Anda dapat mengunggah kembali foto KTP, foto selfie, dan video verifikasi yang lebih jelas melalui formulir di bawah ini agar Admin kami dapat meninjau ulang.
            </p>
        </div>
    @endif

    <!-- UPLOAD FORM -->
    @if(!$verification || $verification->status === 'rejected')
        <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm" x-data="verificationUploads()">
            <!-- Form Header -->
            <div class="border-b border-slate-50 pb-3 mb-5">
                <h3 class="font-bold text-slate-800 text-sm">Unggah Dokumen Verifikasi</h3>
                <p class="text-[11px] text-slate-400">Pastikan pencahayaan cukup dan teks KTP terbaca dengan jelas</p>
            </div>

            <form action="{{ route('owner.verification.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 confirm-form"
                  data-confirm-title="Kirim Dokumen Verifikasi?"
                  data-confirm-text="Apakah Anda yakin data KTP, foto selfie, dan video verifikasi yang diunggah sudah benar, jelas, dan terbaca dengan baik oleh sistem audit?"
                  data-confirm-button="Ya, Kirim Sekarang"
                  data-confirm-color="#10b981"
                  data-confirm-icon="question">
                @csrf
                
                <!-- Inputs grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- KTP Image input -->
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-700 block">1. Foto KTP Asli <span class="text-red-500">*</span></label>
                        <div class="border-2 border-dashed border-slate-200 hover:border-emerald-500 rounded-2xl p-4 bg-slate-50/50 flex flex-col items-center justify-center cursor-pointer transition-colors relative min-h-[140px]">
                            <i class="fa-regular fa-id-card text-2xl text-slate-400 mb-1.5" x-show="!ktpPreview"></i>
                            <span class="text-[10px] font-bold text-slate-700" x-show="!ktpPreview">Pilih Foto KTP</span>
                            
                            <img :src="ktpPreview" class="absolute inset-0 w-full h-full object-cover rounded-2xl" x-show="ktpPreview" x-cloak>
                            
                            <input type="file" name="ktp_image" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer" @change="previewKtp" required>
                        </div>
                        <span class="text-[10px] text-slate-400 block leading-tight">Foto KTP harus pas di frame, tidak terpotong, dan semua tulisan terbaca jelas.</span>
                    </div>

                    <!-- Selfie Image input -->
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-700 block">2. Foto Selfie memegang KTP <span class="text-red-500">*</span></label>
                        <div class="border-2 border-dashed border-slate-200 hover:border-emerald-500 rounded-2xl p-4 bg-slate-50/50 flex flex-col items-center justify-center cursor-pointer transition-colors relative min-h-[140px]">
                            <i class="fa-solid fa-camera-retro text-2xl text-slate-400 mb-1.5" x-show="!selfiePreview"></i>
                            <span class="text-[10px] font-bold text-slate-700" x-show="!selfiePreview">Pilih Foto Selfie</span>
                            
                            <img :src="selfiePreview" class="absolute inset-0 w-full h-full object-cover rounded-2xl" x-show="selfiePreview" x-cloak>
                            
                            <input type="file" name="selfie_image" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer" @change="previewSelfie" required>
                        </div>
                        <span class="text-[10px] text-slate-400 block leading-tight">Wajah Anda dan informasi KTP harus terlihat jelas secara bersamaan tanpa terhalang flash.</span>
                    </div>
                </div>

                <!-- Video upload (optional/highly recommended) -->
                <div class="space-y-2 pt-2">
                    <label class="text-xs font-bold text-slate-700 block">3. Video Verifikasi Singkat (Opsional - Sangat Direkomendasikan)</label>
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex flex-col sm:flex-row items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl flex-shrink-0">
                            <i class="fa-solid fa-video"></i>
                        </div>
                        <div class="flex-grow text-center sm:text-left">
                            <h4 class="text-xs font-bold text-slate-700">Video Rekaman Wajah memegang KTP</h4>
                            <p class="text-[10px] text-slate-400 leading-relaxed mt-0.5">
                                Unggah rekaman video singkat 5-10 detik memegang KTP sambil menyebutkan nama Anda untuk kelulusan instan verifikasi (maks 15MB, format MP4).
                            </p>
                        </div>
                        <label class="px-4 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-bold rounded-lg cursor-pointer hover:bg-slate-100 flex-shrink-0 transition-colors shadow-sm">
                            Pilih Video
                            <input type="file" name="verification_video" accept="video/*" class="hidden" @change="videoSelected">
                        </label>
                    </div>
                    <div x-show="videoName" class="p-2 bg-emerald-50 rounded-lg text-emerald-800 text-[10px] font-semibold flex items-center justify-between border border-emerald-100" x-cloak>
                        <span><i class="fa-solid fa-circle-check mr-1.5"></i>Video terpilih: <span x-text="videoName"></span></span>
                        <button type="button" @click="clearVideo" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                </div>

                <!-- Warnings and Submit -->
                <div class="pt-4 border-t border-slate-50 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center space-x-2 text-[10px] text-slate-400">
                        <i class="fa-solid fa-lock text-emerald-500"></i>
                        <span>Enkripsi Aman SSL. Data pribadi Anda terlindungi dan tidak akan dipublikasikan.</span>
                    </div>
                    <button type="submit" class="w-full sm:w-auto px-6 h-11 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all shadow-lg shadow-emerald-600/10">
                        <i class="fa-solid fa-circle-check mr-1.5"></i>Ajukan Verifikasi
                    </button>
                </div>
            </form>
        </div>
    @else
        <!-- DISPLAYING SUBMITTED DETAILS FOR PENDING/APPROVED STATES -->
        <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm space-y-5">
            <div class="border-b border-slate-50 pb-3 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 text-sm">Dokumen Terlampir</h3>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600">
                    {{ $verification->status }}
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- KTP View -->
                <div class="space-y-1.5">
                    <span class="text-[10px] text-slate-400 uppercase font-bold block">Lampiran KTP</span>
                    <div class="aspect-[1.58] rounded-xl border border-slate-200 overflow-hidden bg-slate-100 cursor-pointer shadow-sm relative group" onclick="window.open(this.querySelector('img').src)">
                        <img src="{{ asset($verification->ktp_image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-xs font-semibold transition-opacity"><i class="fa-solid fa-magnifying-glass-plus mr-1.5"></i>Perbesar</div>
                    </div>
                </div>

                <!-- Selfie View -->
                <div class="space-y-1.5">
                    <span class="text-[10px] text-slate-400 uppercase font-bold block">Lampiran Selfie memegang KTP</span>
                    <div class="aspect-[1.58] rounded-xl border border-slate-200 overflow-hidden bg-slate-100 cursor-pointer shadow-sm relative group" onclick="window.open(this.querySelector('img').src)">
                        <img src="{{ asset($verification->selfie_image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-xs font-semibold transition-opacity"><i class="fa-solid fa-magnifying-glass-plus mr-1.5"></i>Perbesar</div>
                    </div>
                </div>

                <!-- Video View -->
                @if($verification->verification_video)
                    <div class="space-y-1.5 sm:col-span-2">
                        <span class="text-[10px] text-slate-400 uppercase font-bold block">Rekaman Video Verifikasi</span>
                        <div class="rounded-xl border border-slate-100 overflow-hidden shadow-sm max-w-md bg-slate-950">
                            <video src="{{ asset($verification->verification_video) }}" controls class="w-full max-h-48"></video>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Verified information info -->
            @if($verification->verified_at)
                <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-100 text-[10px] text-slate-500 flex items-center">
                    <i class="fa-solid fa-circle-info text-emerald-600 mr-2 text-xs"></i>
                    <span>Telah diverifikasi pada <span class="font-bold text-slate-700">{{ $verification->updated_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}</span>. Tim audit berhak membatalkan jika terjadi manipulasi data di kemudian hari.</span>
                </div>
            @endif
        </div>
    @endif

    <!-- BANK ACCOUNT CARD -->
    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm space-y-4 mt-6" x-data="bankAccountVerification()">
        <div class="border-b border-slate-50 pb-3 flex items-center space-x-3.5">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-building-columns"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Rekening Bank Pencairan Dana</h3>
                <p class="text-xs text-slate-400">Masukkan detail bank Anda untuk menerima hasil pencairan sewa kos.</p>
            </div>
        </div>

        <!-- Payout Warning Alert -->
        <div class="p-4 bg-amber-50/70 border border-amber-200/60 rounded-2xl flex items-start space-x-3.5 text-amber-800 shadow-sm">
            <i class="fa-solid fa-triangle-exclamation text-base mt-0.5 text-amber-600 shrink-0 animate-pulse"></i>
            <div class="space-y-1">
                <h4 class="text-xs font-bold text-amber-900">PENTING: Pastikan Data Rekening 100% Akurat</h4>
                <p class="text-[10px] text-amber-700 leading-relaxed">
                    Demi keamanan pencairan dana sewa Rekber KosinAja, **Nama Pemilik Rekening Bank harus sama persis dengan nama pada KTP** yang Anda unggah untuk verifikasi identitas. Jika terjadi kesalahan ketik nomor rekening atau perbedaan nama, sistem pencairan dana akan **ditangguhkan otomatis oleh Admin** demi mencegah kegagalan transfer.
                </p>
            </div>
        </div>

        @if(session('success') && str_contains(session('success'), 'Rekening'))
            <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-100 text-[11px] text-emerald-800 font-semibold">
                <i class="fa-solid fa-circle-check mr-1.5"></i>{{ session('success') }}
            </div>
        @endif

        <form action="{{ route('owner.bank-account.update') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                <!-- Nama Bank -->
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Nama Bank</label>
                    <select name="bank_name" x-model="bankName" @change="resetVerification()" required class="w-full border border-slate-200 rounded-xl text-xs px-3 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        <option value="">Pilih Bank</option>
                        @foreach(['BCA' => 'Bank Central Asia (BCA)', 'MANDIRI' => 'Bank Mandiri', 'BRI' => 'Bank Rakyat Indonesia (BRI)', 'BNI' => 'Bank Negara Indonesia (BNI)', 'BSI' => 'Bank Syariah Indonesia (BSI)', 'CIMB' => 'CIMB Niaga'] as $code => $name)
                            <option value="{{ $code }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Nomor Rekening -->
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Nomor Rekening</label>
                    <div class="flex gap-2">
                        <input type="text" name="bank_account_number" x-model="accountNumber" @input="resetVerification()" required placeholder="Contoh: 1234567890"
                               class="w-full border border-slate-200 rounded-xl text-xs px-3 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        
                        <button type="button" @click="checkAccount()" :disabled="isVerifying || !bankName || !accountNumber"
                                class="shrink-0 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 disabled:bg-slate-100 disabled:text-slate-400 text-white text-xs font-bold rounded-xl transition-all cursor-pointer shadow-sm">
                            <span x-show="!isVerifying">Cek Rekening</span>
                            <span x-show="isVerifying" x-cloak class="flex items-center gap-1">
                                <i class="fa-solid fa-spinner animate-spin"></i> Loading
                            </span>
                        </button>
                    </div>
                </div>

                <!-- Nama Pemilik Rekening -->
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Nama Pemilik Rekening</label>
                    <div class="relative">
                        <input type="text" name="bank_account_name" x-model="accountName" readonly placeholder="Klik 'Cek Rekening' dahulu"
                               :class="isVerified ? 'bg-emerald-50/10 text-slate-800 border-emerald-500/45 focus:ring-2 focus:ring-emerald-300' : 'bg-slate-50 text-slate-400 border-slate-200'"
                               class="w-full border rounded-xl text-xs px-3 py-2.5 font-bold focus:outline-none transition-all duration-250">
                        
                        <span x-show="isVerified" x-cloak class="absolute right-3 top-2.5 text-[10px] bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 px-2 py-0.5 rounded font-black flex items-center gap-1">
                            <i class="fa-solid fa-circle-check text-[9px]"></i> Aktif
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" :disabled="!isVerified"
                        class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 disabled:bg-slate-100 disabled:text-slate-400 disabled:shadow-none text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-emerald-500/10 cursor-pointer">
                    <i class="fa-solid fa-floppy-disk mr-1.5"></i>Simpan Rekening
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function verificationUploads() {
        return {
            ktpPreview: null,
            selfiePreview: null,
            videoName: '',
            
            previewKtp(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.ktpPreview = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            },

            previewSelfie(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.selfiePreview = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            },

            videoSelected(event) {
                const file = event.target.files[0];
                if (file) {
                    this.videoName = file.name;
                }
            },

            clearVideo() {
                const fileInput = document.querySelector('input[name="verification_video"]');
                if (fileInput) fileInput.value = '';
                this.videoName = '';
            }
        };
    }

    function bankAccountVerification() {
        return {
            bankName: '{{ auth()->user()->bank_name ?? "" }}',
            accountNumber: '{{ auth()->user()->bank_account_number ?? "" }}',
            accountName: '{{ auth()->user()->bank_account_name ?? "" }}',
            isVerifying: false,
            isVerified: '{{ auth()->user()->bank_account_name ? "true" : "false" }}' === 'true',
            
            checkAccount() {
                if (!this.bankName || !this.accountNumber) return;
                
                // Let's ask them to input their real name to match real data instead of Budi Santoso
                Swal.fire({
                    title: 'Masukkan Nama Pemilik Rekening',
                    text: 'Silakan ketik nama asli Anda sesuai yang terdaftar di Bank:',
                    input: 'text',
                    inputPlaceholder: 'Contoh: MADE ADITYA',
                    showCancelButton: true,
                    confirmButtonText: 'Cek Rekening',
                    confirmButtonColor: '#10b981',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'rounded-3xl border border-slate-100 shadow-2xl p-6',
                        title: 'text-sm font-bold text-slate-800',
                        input: 'rounded-xl text-xs border border-slate-200'
                    },
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Nama pemilik rekening wajib diisi!';
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.accountName = result.value.toUpperCase();
                        this.performCheck();
                    }
                });
            },

            performCheck() {
                this.isVerifying = true;
                
                fetch('{{ route("owner.bank-account.check") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        bank_name: this.bankName,
                        bank_account_number: this.accountNumber
                    })
                })
                .then(res => {
                    if (!res.ok) {
                        return res.json().then(err => { throw err; });
                    }
                    return res.json();
                })
                .then(data => {
                    this.isVerifying = false;
                    this.isVerified = true;
                    
                    Swal.fire({
                        title: 'Rekening Ditemukan!',
                        html: `Sistem Bank mendeteksi rekening terdaftar atas nama:<br><b class="text-emerald-600 text-sm">${this.accountName}</b>`,
                        icon: 'success',
                        confirmButtonText: 'Lanjutkan',
                        confirmButtonColor: '#10b981',
                        customClass: {
                            popup: 'rounded-3xl border border-slate-100 shadow-2xl p-6',
                            title: 'text-sm font-bold text-slate-800'
                        }
                    });
                })
                .catch(err => {
                    this.isVerifying = false;
                    this.isVerified = false;
                    this.accountName = '';
                    
                    Swal.fire({
                        title: 'Rekening Tidak Ditemukan',
                        text: err.message || 'Nomor rekening tidak ditemukan atau salah. Silakan periksa kembali Bank dan Nomor Rekening Anda.',
                        icon: 'error',
                        confirmButtonText: 'Coba Lagi',
                        confirmButtonColor: '#ef4444',
                        customClass: {
                            popup: 'rounded-3xl border border-slate-100 shadow-2xl p-6',
                            title: 'text-sm font-bold text-slate-800'
                        }
                    });
                });
            },
            
            resetVerification() {
                this.isVerified = false;
                this.accountName = '';
            }
        };
    }
</script>
@endsection
