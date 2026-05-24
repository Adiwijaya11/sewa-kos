@extends('layouts.app')
@section('title', 'Tentang Kami - KosinAja')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-12">
    <div class="text-center space-y-4">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Tentang KosinAja</h1>
        <p class="text-sm sm:text-base text-slate-500 max-w-xl mx-auto leading-relaxed">
            Membangun masa depan penyewaan kos yang transparan, terverifikasi, dan aman bagi pelajar, pekerja, serta pemilik kos di Indonesia.
        </p>
    </div>

    <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm leading-relaxed text-xs sm:text-sm text-slate-600 space-y-6">
        <h2 class="text-lg font-bold text-slate-800">Visi & Misi Kami</h2>
        <p>
            Mencari tempat tinggal sementara seperti kos seharusnya menjadi proses yang menyenangkan dan bebas dari kekhawatiran. Namun kenyataannya, banyak pencari kos terjebak dalam kasus penipuan DP fiktif, foto kamar palsu, dan ketidaksesuaian lokasi asli dengan deskripsi internet.
        </p>
        <p>
            **KosinAja** lahir sebagai platform penjembatan terpercaya (marketplace kos terintegrasi) untuk memberikan jaminan keamanan transaksi, kualitas data listing yang akurat, serta kemudahan proses pencarian dan pemesanan secara modern dengan standardisasi verifikasi tinggi.
        </p>
        
        <h3 class="text-sm font-bold text-slate-800 pt-4">Tiga Pilar Utama KosinAja:</h3>
        <ul class="list-disc pl-5 space-y-2">
            <li><strong>Verifikasi Identitas Resmi (Audit KTP):</strong> Setiap pemilik properti wajib melewati pencocokan KTP fisik & video identitas wajah oleh tim admin kami demi memastikan legalitas kepemilikan.</li>
            <li><strong>Sistem Rekening Bersama (Safe Booking):</strong> Dana jaminan deposit sewa ditahan dengan aman oleh sistem KosinAja dan hanya dicairkan setelah penyewa melakukan check-in properti secara memuaskan.</li>
            <li><strong>Akurasi GPS & Foto 100%:</strong> Kami memvalidasi koordinat koordinat peta sesungguhnya untuk menjamin lokasi kos persis berada di peta.</li>
        </ul>
    </div>
</div>
@endsection
