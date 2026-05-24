@extends('layouts.dashboard')

@section('title', 'Moderasi KTP - KosinAja')
@section('header_title', 'Kelola Verifikasi Identitas Owner')

@section('content')
<div class="space-y-6">
    <!-- Header Page Actions -->
    <div>
        <h2 class="text-lg font-bold text-slate-800">Daftar Pengajuan Verifikasi KTP</h2>
        <p class="text-xs text-slate-400">Verifikasi berkas legalitas pendaftaran pemilik kos baru demi keamanan transaksi</p>
    </div>

    <!-- Verifications Card List -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-slate-400 text-[10px] uppercase font-bold tracking-wider bg-slate-50/50">
                        <th class="py-3 px-5">Pemilik Kos</th>
                        <th class="py-3 px-5">Foto KTP</th>
                        <th class="py-3 px-5">Selfie memegang KTP</th>
                        <th class="py-3 px-5">Video Verifikasi</th>
                        <th class="py-3 px-5">Tanggal Mengajukan</th>
                        <th class="py-3 px-5">Status</th>
                        <th class="py-3 px-5 text-right">Aksi Peninjauan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-xs text-slate-700">
                    @forelse($verifications as $v)
                        <tr class="hover:bg-slate-50/30 transition-colors" x-data="{ showReject: false }">
                            
                            <!-- Pemilik Kos -->
                            <td class="py-4 px-5">
                                <div>
                                    <p class="font-bold text-slate-800">{{ $v->user->name }}</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $v->user->email }}</p>
                                    <span class="inline-flex items-center px-1.5 py-0.2 rounded-[4px] text-[8px] font-bold tracking-wide bg-slate-100 text-slate-500 uppercase mt-1">Role: {{ $v->user->role }}</span>
                                </div>
                            </td>
                            
                            <!-- Foto KTP -->
                            <td class="py-4 px-5">
                                <div class="w-20 aspect-[1.58] rounded-lg border border-slate-200 overflow-hidden bg-slate-100 cursor-pointer shadow-sm relative group" onclick="window.open(this.querySelector('img').src)">
                                    <img src="{{ asset($v->ktp_image) }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-slate-900/30 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-[9px] font-bold transition-opacity">Zoom</div>
                                </div>
                            </td>
                            
                            <!-- Selfie memegang KTP -->
                            <td class="py-4 px-5">
                                <div class="w-20 aspect-[1.58] rounded-lg border border-slate-200 overflow-hidden bg-slate-100 cursor-pointer shadow-sm relative group" onclick="window.open(this.querySelector('img').src)">
                                    <img src="{{ asset($v->selfie_image) }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-slate-900/30 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-[9px] font-bold transition-opacity">Zoom</div>
                                </div>
                            </td>
                            
                            <!-- Video Verifikasi -->
                            <td class="py-4 px-5">
                                @if($v->verification_video)
                                    <button onclick="playAdminVideo('{{ asset($v->verification_video) }}')" 
                                            class="px-2.5 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-700 text-[10px] font-bold flex items-center shadow-sm">
                                        <i class="fa-solid fa-circle-play mr-1.5 text-emerald-500"></i>Putar Rekaman
                                    </button>
                                @else
                                    <span class="text-[10px] text-slate-400 italic">Tidak terlampir</span>
                                @endif
                            </td>
                            
                            <!-- Tanggal Mengajukan -->
                            <td class="py-4 px-5 font-medium text-slate-500">
                                {{ $v->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') }} WIB
                            </td>
                            
                            <!-- Status -->
                            <td class="py-4 px-5">
                                @if($v->status === 'approved')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        <i class="fa-solid fa-circle-check mr-1"></i>Approved
                                    </span>
                                @elseif($v->status === 'rejected')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-red-50 text-red-700 border border-red-100" title="{{ $v->notes }}">
                                        <i class="fa-solid fa-circle-xmark mr-1"></i>Rejected
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-100">
                                        <i class="fa-solid fa-clock-rotate-left mr-1"></i>Pending Review
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Aksi Peninjauan -->
                            <td class="py-4 px-5 text-right relative">
                                @if($v->status === 'pending')
                                    <div class="flex items-center justify-end space-x-1.5">
                                        <!-- Approve form -->
                                        <form action="{{ route('admin.verifications.approve', $v->id) }}" method="POST" class="inline confirm-form"
                                              data-confirm-title="Setujui Verifikasi KTP?"
                                              data-confirm-text="Apakah Anda yakin berkas identitas pemilik kos {{ $v->user->name }} sudah benar dan absah? Akun owner ini akan berstatus TERVERIFIKASI."
                                              data-confirm-button="Ya, Setujui"
                                              data-confirm-color="#10b981"
                                              data-confirm-icon="success">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1.5 rounded bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-bold shadow-sm transition-all">
                                                Setujui
                                            </button>
                                        </form>
                                        
                                        <!-- Reject Trigger -->
                                        <button @click="showReject = true" class="px-2.5 py-1.5 rounded bg-red-50 hover:bg-red-500 text-red-600 hover:text-white border border-red-100 text-[10px] font-bold transition-all">
                                            Tolak
                                        </button>
                                    </div>

                                    <!-- Rejection Input Overlay popup -->
                                    <div x-show="showReject" 
                                         @click.away="showReject = false" 
                                         x-transition 
                                         class="absolute right-5 bottom-12 z-20 w-64 p-4 rounded-xl bg-white shadow-xl border border-slate-100 text-left" 
                                         x-cloak>
                                        <h4 class="text-xs font-bold text-slate-800">Tentukan Catatan Penolakan</h4>
                                        <p class="text-[9px] text-slate-400 mt-0.5 mb-2.5">Tuliskan alasan penolakan agar owner memperbaikinya.</p>
                                        
                                        <form action="{{ route('admin.verifications.reject', $v->id) }}" method="POST" class="space-y-3 confirm-form"
                                              data-confirm-title="Kirim Penolakan Verifikasi?"
                                              data-confirm-text="Apakah Anda yakin ingin menolak pengajuan verifikasi dari {{ $v->user->name }}?"
                                              data-confirm-button="Ya, Tolak"
                                              data-confirm-color="#ef4444"
                                              data-confirm-icon="warning">
                                            @csrf
                                            <textarea name="notes" rows="3" placeholder="Contoh: Dokumen KTP buram, tidak terbaca dengan jelas..." 
                                                      class="w-full p-2 border border-slate-200 rounded-lg text-[10px] focus:outline-none focus:border-emerald-500" required></textarea>
                                            
                                            <div class="flex items-center justify-end space-x-1.5">
                                                <button type="button" @click="showReject = false" class="px-2 py-1 rounded bg-slate-100 hover:bg-slate-200 text-slate-600 text-[9px] font-semibold">Batal</button>
                                                <button type="submit" class="px-2 py-1 rounded bg-red-600 hover:bg-red-700 text-white text-[9px] font-semibold shadow-sm">Kirim Tolak</button>
                                            </div>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-[10px] text-slate-400 font-semibold italic">Selesai Ditinjau</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center text-slate-400">
                                <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 mx-auto mb-4 border border-slate-100">
                                    <i class="fa-solid fa-id-card-clip text-2xl"></i>
                                </div>
                                <p class="font-bold text-slate-700 text-sm">Tidak Ada Dokumen Verifikasi</p>
                                <p class="text-xs text-slate-400 mt-1">Belum ada berkas pendaftaran identitas yang masuk di platform.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        @if($verifications->hasPages())
            <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/20">
                {{ $verifications->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Video player overlay modal -->
<div id="videoModal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md hidden items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-xl overflow-hidden shadow-2xl relative">
        <div class="px-4 py-3 border-b border-slate-800 flex justify-between items-center bg-slate-950/50">
            <span class="text-xs font-bold text-white"><i class="fa-solid fa-video mr-1.5 text-emerald-400"></i>Review Video Verifikasi</span>
            <button onclick="closeAdminVideo()" class="text-slate-400 hover:text-white"><i class="fa-solid fa-xmark text-sm"></i></button>
        </div>
        <div class="p-2 flex justify-center bg-black aspect-video items-center">
            <video id="modalVideoPlayer" src="" controls class="w-full max-h-[350px]"></video>
        </div>
    </div>
</div>

<script>
    const playAdminVideo = (url) => {
        const modal = document.getElementById('videoModal');
        const player = document.getElementById('modalVideoPlayer');
        player.src = url;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        player.play();
    };

    const closeAdminVideo = () => {
        const modal = document.getElementById('videoModal');
        const player = document.getElementById('modalVideoPlayer');
        player.pause();
        player.src = '';
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    };
</script>
@endsection
