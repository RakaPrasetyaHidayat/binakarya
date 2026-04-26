@extends('layouts.public')

@section('title', 'Kebijakan Privasi')
@section('meta_description', 'Kebijakan privasi kami menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi data pribadi Anda.')

@@section('content')
{{-- Header --}}
<section class="py-12 sm:py-16 lg:py-20 bg-gray-50 dark:bg-slate-900 transition-colors pt-24 sm:pt-28 md:pt-32 lg:pt-36">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-3xl sm:text-4xl lg:text-4xl font-serif font-bold text-gray-900 dark:text-white mb-4">Kebijakan Privasi</h1>
        <p class="text-base sm:text-lg text-gray-600 dark:text-gray-400">Kami berkomitmen melindungi privasi dan data pribadi Anda.</p>
    </div>
</section>

{{-- Content --}}
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-20">
    <div class="prose prose-gray max-w-full dark:prose-invert">
        <p class="text-sm text-gray-500">Terakhir diperbarui: <strong>{{ now()->format('d F Y') }}</strong></p>

        <h2 class="text-2xl font-serif font-bold text-gray-900 dark:text-white mt-8 mb-4">Kebijakan Privasi</h2>
        <p class="text-gray-600 dark:text-gray-300 leading-relaxed mb-4">
            Kebijakan Privasi {{ $siteSettings->get('site_name', 'Binakarya Cendikia') }} (“Kebijakan Privasi”) berlaku untuk penggunaan Platform dan merupakan bagian dari Syarat dan Ketentuan {{ $siteSettings->get('site_name', 'Binakarya Cendikia') }} serta Kebijakan Penggunaan. Dengan menggunakan Platform, Anda menyetujui bahwa informasi yang Anda kirimkan dapat diakses sesuai dengan ketentuan perlindungan data yang berlaku.
        </p>

        <h2 class="text-2xl font-serif font-bold text-gray-900 dark:text-white mt-8 mb-4">Siapa kami</h2>
        <p class="text-gray-600 dark:text-gray-300 leading-relaxed mb-4">
            Situs web resmi kami adalah platform digital yang dirancang untuk memberikan layanan kursus online dan penerbitan secara efektif. Melalui situs ini, pengguna dapat mengakses pendaftaran kursus, informasi program, jadwal kelas, serta materi pembelajaran. Kami berkomitmen untuk menjaga keamanan data pengguna sesuai dengan standar privasi yang berlaku.
        </p>

        <h2 class="text-2xl font-serif font-bold text-gray-900 dark:text-white mt-8 mb-4">Komentar</h2>
        <p class="text-gray-600 dark:text-gray-300 leading-relaxed mb-4">
            Saat pengunjung meninggalkan komentar di situs, kami mengumpulkan data yang ditampilkan di formulir komentar, serta alamat IP dan data browser untuk membantu deteksi spam. String anonim dari alamat email Anda dapat diberikan ke layanan Gravatar untuk verifikasi profil.
        </p>

        <h2 class="text-2xl font-serif font-bold text-gray-900 dark:text-white mt-8 mb-4">Media</h2>
        <p class="text-gray-600 dark:text-gray-300 leading-relaxed mb-4">
            Jika Anda mengunggah gambar ke situs web, Anda harus menghindari mengunggah gambar dengan data lokasi tertanam (GPS EXIF). Pengunjung situs web dapat mengunduh dan mengekstrak data lokasi dari gambar di situs web.
        </p>

        <h2 class="text-2xl font-serif font-bold text-gray-900 dark:text-white mt-8 mb-4">Cookies</h2>
        <p class="text-gray-600 dark:text-gray-300 leading-relaxed mb-4">
            Kami menggunakan cookie untuk kenyamanan Anda, seperti menyimpan detail saat Anda meninggalkan komentar (bertahan selama satu tahun) atau menyimpan informasi login Anda (bertahan sesuai pilihan Anda). Cookie ini membantu kami memberikan pengalaman yang lebih personal dan efisien.
        </p>

        <h2 class="text-2xl font-serif font-bold text-gray-900 dark:text-white mt-8 mb-4">Konten yang disematkan</h2>
        <p class="text-gray-600 dark:text-gray-300 leading-relaxed mb-4">
            Artikel di situs ini mungkin menyertakan konten yang disematkan (video, gambar, dll.). Konten yang disematkan dari situs web lain berperilaku sama persis seperti jika pengunjung telah mengunjungi situs web lain tersebut, yang mungkin mengumpulkan data tentang Anda.
        </p>

        <h2 class="text-2xl font-serif font-bold text-gray-900 dark:text-white mt-8 mb-4">Penyimpanan dan Hak Data</h2>
        <p class="text-gray-600 dark:text-gray-300 leading-relaxed mb-4">
            Data komentar disimpan tanpa batas waktu. Untuk pengguna yang mendaftar, kami menyimpan informasi pribadi di profil pengguna. Anda berhak meminta ekspor data pribadi yang kami simpan atau meminta penghapusan data tersebut, kecuali untuk data yang wajib kami simpan demi alasan hukum atau keamanan.
        </p>

        <h2 class="text-2xl font-serif font-bold text-gray-900 dark:text-white mt-8 mb-4">Hubungi Kami</h2>
        <div class="bg-gray-50 dark:bg-slate-900 p-6 rounded-lg mt-4 text-gray-700 dark:text-gray-300 space-y-2 translate-colors">
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
