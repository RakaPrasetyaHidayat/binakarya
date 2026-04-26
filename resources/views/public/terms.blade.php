@extends('layouts.public')

@section('title', 'Syarat & Ketentuan')
@section('meta_description', 'Syarat dan ketentuan penggunaan situs web dan layanan Binakarya Cendikia.')

@section('content')
{{-- Header --}}
<section class="py-12 sm:py-16 lg:py-20 bg-gray-50 dark:bg-slate-900 transition-colors pt-24 sm:pt-28 md:pt-32 lg:pt-36">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-3xl sm:text-4xl lg:text-4xl font-serif font-bold text-gray-900 dark:text-white mb-4 transition-colors">Syarat & Ketentuan</h1>
        <p class="text-base sm:text-lg text-gray-600 dark:text-gray-400 transition-colors">Ketentuan penggunaan situs dan layanan kami.</p>
    </div>
</section>

{{-- Content --}}
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-20 bg-white dark:bg-slate-900 transition-colors">
    <div class="prose prose-gray max-w-full dark:prose-invert">
        <p class="text-sm text-gray-500 dark:text-gray-400 transition-colors">Terakhir diperbarui: <strong>{{ now()->format('d F Y') }}</strong></p>
        
        <h2 class="text-2xl font-serif font-bold text-gray-900 mt-8 mb-4">Pendahuluan</h2>
        <p class="text-gray-600 leading-relaxed">
            Selamat datang di {{ $siteSettings->get('site_name', 'Binakarya Cendikia') }}! Syarat dan Ketentuan ini mengatur penggunaan layanan bimbingan belajar dan penerbitan kami, termasuk situs web ini dan layanan lain yang kami sediakan. Dengan menggunakan layanan kami, Anda dianggap telah membaca, memahami, dan menyetujui seluruh ketentuan yang tercantum di bawah ini.
        </p>
        
        <h2 class="text-2xl font-serif font-bold text-gray-900 mt-8 mb-4">Layanan yang Disediakan</h2>
        <p class="text-gray-600 leading-relaxed mb-4">{{ $siteSettings->get('site_name', 'Binakarya Cendikia') }} menyediakan layanan bimbingan belajar baik secara online & offline serta layanan penerbitan buku ilmiah. Program meliputi:</p>
        <ul class="space-y-3 text-gray-600 list-disc pl-5">
            <li>Bimbingan Belajar & Pelatihan Akademik</li>
            <li>Penerbitan Buku & Karya Ilmiah</li>
            <li>Tryout & Evaluasi Kompetensi</li>
            <li>Pengembangan Softskills</li>
        </ul>
        
        <h2 class="text-2xl font-serif font-bold text-gray-900 mt-8 mb-4">Pendaftaran dan Akun</h2>
        <ul class="space-y-3 text-gray-600 list-disc pl-5">
            <li>Pengguna diwajibkan untuk mendaftar menggunakan informasi yang valid dan akurat.</li>
            <li>Setiap akun bersifat pribadi dan tidak dapat dipindahtangankan.</li>
            <li>Pengguna bertanggung jawab untuk menjaga kerahasiaan akun mereka, termasuk kata sandi.</li>
        </ul>
        
        <h2 class="text-2xl font-serif font-bold text-gray-900 mt-8 mb-4">Pembayaran</h2>
        <ul class="space-y-3 text-gray-600 list-disc pl-5">
            <li>Semua pembayaran untuk layanan dilakukan melalui metode pembayaran yang disediakan di platform kami.</li>
            <li>Biaya pendaftaran dan biaya program yang telah dibayarkan tidak dapat dikembalikan kecuali dalam kondisi tertentu yang disetujui oleh pihak {{ $siteSettings->get('site_name', 'Binakarya Cendikia') }}.</li>
            <li>Promo dan diskon hanya berlaku sesuai syarat dan ketentuan masing-masing promosi.</li>
        </ul>
        
        <h2 class="text-2xl font-serif font-bold text-gray-900 mt-8 mb-4">Kebijakan Pembatalan</h2>
        <ul class="space-y-3 text-gray-600 list-disc pl-5">
            <li>Pembatalan harus diinformasikan kepada pihak {{ $siteSettings->get('site_name', 'Binakarya Cendikia') }}.</li>
            <li>Pembatalan yang dilakukan setelah kelas dimulai atau proses penerbitan berjalan tidak akan mendapatkan pengembalian dana.</li>
        </ul>
        
        <h2 class="text-2xl font-serif font-bold text-gray-900 mt-8 mb-4">Hak dan Kewajiban Pengguna</h2>
        <ul class="space-y-3 text-gray-600 list-disc pl-5">
            <li>Pengguna wajib menjaga perilaku yang baik selama mengikuti kelas atau proses layanan.</li>
            <li>Pengguna tidak diperkenankan menyebarluaskan materi pembelajaran atau draf karya tanpa izin tertulis dari {{ $siteSettings->get('site_name', 'Binakarya Cendikia') }}.</li>
            <li>Pelanggaran terhadap aturan dapat menyebabkan penangguhan atau penghentian layanan tanpa pengembalian dana.</li>
        </ul>
        
        <h2 class="text-2xl font-serif font-bold text-gray-900 mt-8 mb-4">Penyesuaian Layanan</h2>
        <p class="text-gray-600 leading-relaxed">
            {{ $siteSettings->get('site_name', 'Binakarya Cendikia') }} berhak mengubah jadwal kelas, metode pengajaran, atau pengajar sesuai dengan kebutuhan operasional, dengan pemberitahuan sebelumnya kepada pengguna.
        </p>
        
        <h2 class="text-2xl font-serif font-bold text-gray-900 mt-8 mb-4">Batasan Tanggung Jawab</h2>
        <p class="text-gray-600 leading-relaxed mb-4">{{ $siteSettings->get('site_name', 'Binakarya Cendikia') }} tidak bertanggung jawab atas kerugian yang timbul akibat:</p>
        <ul class="space-y-3 text-gray-600 list-disc pl-5">
            <li>Gangguan teknis di luar kendali kami.</li>
            <li>Penyalahgunaan akun oleh pihak lain.</li>
            <li>Ketidakhadiran pengguna pada kelas yang telah dijadwalkan.</li>
        </ul>
        
        <h2 class="text-2xl font-serif font-bold text-gray-900 mt-8 mb-4">Hukum yang Berlaku</h2>
        <p class="text-gray-600 leading-relaxed">
            Syarat dan Ketentuan ini tunduk pada hukum yang berlaku di Republik Indonesia.
        </p>
        
        <h2 class="text-2xl font-serif font-bold text-gray-900 mt-8 mb-4">Hubungi Kami</h2>
        <div class="bg-gray-50 dark:bg-slate-900 p-6 rounded-lg mt-4 text-gray-700 dark:text-gray-300 space-y-2">
            @if($siteSettings->get('email'))
            <p><strong>Email:</strong> <a href="mailto:{{ $siteSettings->get('email') }}" class="text-primary-600 hover:text-primary-700">{{ $siteSettings->get('email') }}</a></p>
            @endif
            @if($siteSettings->get('address'))
            <p><strong>Alamat:</strong> {{ $siteSettings->get('address') }}</p>
            @endif
            @if($siteSettings->get('wa_number'))
            <p><strong>WhatsApp:</strong> <a href="https://wa.me/{{ $siteSettings->get('wa_number') }}" target="_blank" rel="noopener" class="text-primary-600 hover:text-primary-700">{{ $siteSettings->get('wa_number') }}</a></p>
            @endif
        </div>
    </div>
</div>
@endsection
