<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use App\Models\Post;
use App\Models\Service;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== USERS =====
        // Admin user
        User::firstOrCreate(
            ['email' => 'admin@binakaryacendekia.id'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
            ]
        );

        // Legacy admin login support with MD5 fallback
        if (!\Illuminate\Support\Facades\DB::table('users')->where('email', 'admin@example.com')->exists()) {
            \Illuminate\Support\Facades\DB::table('users')->insert([
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => md5('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $legacyAdmin = User::where('email', 'admin@example.com')->first();
        if ($legacyAdmin && ! $legacyAdmin->hasRole('admin')) {
            $legacyAdmin->assignRole('admin');
        }

        // Contributor users
        User::firstOrCreate(
            ['email' => 'budi@binakaryacendekia.id'],
            [
                'name'     => 'Budi Santoso',
                'password' => Hash::make('password123'),
                'role'     => 'contributor',
            ]
        );

        User::firstOrCreate(
            ['email' => 'siti@binakaryacendekia.id'],
            [
                'name'     => 'Siti Nurhaliza',
                'password' => Hash::make('password123'),
                'role'     => 'contributor',
            ]
        );

        User::firstOrCreate(
            ['email' => 'ahmad@binakaryacendekia.id'],
            [
                'name'     => 'Ahmad Wijaya',
                'password' => Hash::make('password123'),
                'role'     => 'contributor',
            ]
        );

        // ===== SETTINGS =====
        $settings = [
            'site_name'        => 'Bina Karya Cendekia',
            'site_tagline'     => 'Platform Publikasi dan Gerakan Sosial untuk Literasi',
            'site_description' => 'Bina Karya Cendekia adalah yayasan yang berdedikasi untuk mendukung publikasi buku ilmiah, penerbitan jurnal, dan pengembangan literasi di Indonesia.',
            'hero_title'       => 'Bina Karya Cendekia',
            'hero_subtitle'    => 'Memperkuat Gerakan Sosial Melalui Publikasi Ilmiah dan Literasi',
            'about_profile'    => 'Bina Karya Cendekia didirikan dengan visi menjadi lembaga terdepan dalam pengembangan ilmu pengetahuan, publikasi berkualitas, dan pemberdayaan masyarakat melalui literasi. Kami berkomitmen untuk membuka akses pendidikan dan pengetahuan bagi semua kalangan.',
            'about_vision'     => 'Menjadi pusat publikasi ilmiah dan gerakan sosial yang memberdayakan masyarakat Indonesia melalui akses pengetahuan dan literasi berkualitas.',
            'about_mission'    => "1. Menerbitkan buku dan jurnal ilmiah berkualitas tinggi dengan standar internasional\n2. Mendukung penelitian dan pengembangan ilmu pengetahuan\n3. Memfasilitasi diseminasi pengetahuan kepada masyarakat luas\n4. Memberdayakan penulis, peneliti, dan pendidik Indonesia\n5. Membangun gerakan literasi sosial yang inklusif",
            'wa_number'        => '+62 274 514911',
            'email'            => 'info@binakaryacendekia.id',
            'phone'            => '+62 274 514911',
            'address'          => 'Jl. DPR V Cileunyi Kulon, Kec. Cileunyi, Kabupaten Bandung, Jawa Barat 40622',
            'cta_title'        => 'Hubungi Kami',
            'cta_description'  => 'Dapatkan pembaruan terbaru seputar jurnal, program yayasan, serta akses eksklusif ke literatur spesial kami.',
            'cta_button_text'  => 'Hubungi Kami ',
            'footer_text'      => '© ' . date('Y') . ' Bina Karya Cendekia. All rights reserved.',
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }

        // ===== CATEGORIES =====
        // Book categories
        $bookCats = ['Filsafat', 'Pendidikan', 'Sosial Budaya', 'Teknologi', 'Agama', 'Sains', 'Ekonomi'];
        $bookCategories = [];
        foreach ($bookCats as $name) {
            $bookCategories[] = Category::create(['name' => $name, 'type' => 'book', 'slug' => \Illuminate\Support\Str::slug($name)]);
        }

        // Blog categories
        $blogCats = ['Berita', 'Jurnal', 'Panduan', 'Wawancara', 'Riset'];
        $blogCategories = [];
        foreach ($blogCats as $name) {
            $blogCategories[] = Category::create(['name' => $name, 'type' => 'blog', 'slug' => \Illuminate\Support\Str::slug($name)]);
        }

        // ===== BOOKS (12+) =====
        $books = [
            // Filsafat (category 0)
            ['title' => 'Etika Bersama dalam Era Digital', 'author' => 'Prof. Dr. Suharto Wijaya, S.Fil.', 'isbn' => '978-602-8765-01-3', 'doi' => '10.12345/etika.2024', 'year' => 2024, 'cat' => 0, 'edition' => '1st'],
            ['title' => 'Filsafat Politik Modern Indonesia', 'author' => 'Dr. Bambang Sutrisno, M.Phil.', 'isbn' => '978-602-8765-02-0', 'doi' => '10.12345/fpmi.2023', 'year' => 2023, 'cat' => 0, 'edition' => '2nd'],
            
            // Pendidikan (category 1)
            ['title' => 'Metodologi Pembelajaran Inovatif', 'author' => 'Dr. Dewi Kusuma, M.Pd.', 'isbn' => '978-602-8765-03-7', 'doi' => '10.12345/mpi.2023', 'year' => 2023, 'cat' => 1, 'edition' => '1st'],
            ['title' => 'Psikologi Anak dan Perkembangan', 'author' => 'Prof. Dr. Rina Marlina, M.Psi.', 'isbn' => '978-602-8765-04-4', 'doi' => '10.12345/pap.2024', 'year' => 2024, 'cat' => 1, 'edition' => '3rd'],
            ['title' => 'Kurikulum Berbasis Kompetensi', 'author' => 'Dr. Hendra Wijaya, M.Ed.', 'isbn' => '978-602-8765-05-1', 'doi' => '10.12345/kbk.2023', 'year' => 2023, 'cat' => 1, 'edition' => '1st'],
            
            // Sosial Budaya (category 2)
            ['title' => 'Identitas Budaya dan Modernitas', 'author' => 'Dr. Ahmad Fauzi, M.Hum.', 'isbn' => '978-602-8765-06-8', 'doi' => '10.12345/ibm.2024', 'year' => 2024, 'cat' => 2, 'edition' => '1st'],
            ['title' => 'Antropologi Sosial Indonesia', 'author' => 'Prof. Dr. Bambang Setiawan', 'isbn' => '978-602-8765-07-5', 'doi' => '10.12345/asi.2023', 'year' => 2023, 'cat' => 2, 'edition' => '2nd'],
            
            // Teknologi (category 3)
            ['title' => 'Artificial Intelligence dan Etika', 'author' => 'Dr. Budi Santoso, M.T.', 'isbn' => '978-602-8765-08-2', 'doi' => '10.12345/aie.2024', 'year' => 2024, 'cat' => 3, 'edition' => '1st'],
            ['title' => 'Cybersecurity untuk Era Digital', 'author' => 'Dr. Siti Rahayu, M.Kom.', 'isbn' => '978-602-8765-09-9', 'doi' => '10.12345/ced.2023', 'year' => 2023, 'cat' => 3, 'edition' => '2nd'],
            
            // Agama (category 4)
            ['title' => 'Fiqih Kontemporer dan Tantangan Zaman', 'author' => 'Dr. Muhammad Ali, M.Ag.', 'isbn' => '978-602-8765-10-5', 'doi' => '10.12345/fktz.2024', 'year' => 2024, 'cat' => 4, 'edition' => '1st'],
            
            // Sains (category 5)
            ['title' => 'Dasar-Dasar Bioteknologi Terapan', 'author' => 'Dr. Wati Supriyono, M.Sc.', 'isbn' => '978-602-8765-11-2', 'doi' => '10.12345/dbt.2023', 'year' => 2023, 'cat' => 5, 'edition' => '1st'],
            ['title' => 'Ekosistem dan Konservasi Alam', 'author' => 'Prof. Dr. Yusuf Rahman, Ph.D.', 'isbn' => '978-602-8765-12-9', 'doi' => '10.12345/eka.2024', 'year' => 2024, 'cat' => 5, 'edition' => '1st'],
            
            // Ekonomi (category 6)
            ['title' => 'Ekonomi Kerakyatan dan UKM', 'author' => 'Dr. Zainal Abidin, M.Ec.', 'isbn' => '978-602-8765-13-6', 'doi' => '10.12345/eku.2024', 'year' => 2024, 'cat' => 6, 'edition' => '1st'],
            ['title' => 'Strategi Keuangan Berkelanjutan', 'author' => 'Dr. Nuri Handayani, M.B.A.', 'isbn' => '978-602-8765-14-3', 'doi' => '10.12345/skb.2023', 'year' => 2023, 'cat' => 6, 'edition' => '2nd'],
        ];

        foreach ($books as $b) {
            Book::create([
                'category_id'    => $bookCategories[$b['cat']]->id,
                'title'          => $b['title'],
                'author'         => $b['author'],
                'isbn'           => $b['isbn'],
                'doi'            => $b['doi'],
                'published_year' => $b['year'],
                'edition'        => $b['edition'],
                'abstract'       => 'Buku ini menghadirkan pembahasan mendalam tentang ' . strtolower($b['title']) . '. Ditulis oleh para ahli di bidangnya, buku ini menjadi referensi penting bagi akademisi, praktisi, dan peneliti yang ingin memahami lebih komprehensif tentang topik ini. Dilengkapi dengan studi kasus, data empiris, dan rekomendasi praktis.',
                'keywords'       => implode(', ', array_slice(explode(' ', strtolower($b['title'])), 0, 4)),
                'wa_number'      => '+62 274 514911',
                'price'          => rand(8, 25) * 10000,
                'is_published'   => true,
                'cover_image'    => 'https://via.placeholder.com/300x400?text=' . urlencode($b['title']),
            ]);
        }

        // Services
        $services = [
            ['title' => 'Penerbitan Buku Ilmiah', 'icon' => '📚', 'excerpt' => 'Layanan penerbitan buku ilmiah dengan standar internasional, termasuk proses peer-review dan ISBN.'],
            ['title' => 'Konsultasi Penelitian', 'icon' => '🔬', 'excerpt' => 'Konsultasi metodologi penelitian untuk mahasiswa S1, S2, dan S3.'],
            ['title' => 'Pelatihan Akademik', 'icon' => '🎓', 'excerpt' => 'Program pelatihan penulisan ilmiah, sitasi, dan publikasi jurnal internasional.'],
            ['title' => 'Editing & Proofreading', 'icon' => '✏️', 'excerpt' => 'Layanan penyuntingan naskah ilmiah oleh editor berpengalaman.'],
        ];

        foreach ($services as $i => $s) {
            Service::create([
                'title'     => $s['title'],
                'excerpt'   => $s['excerpt'],
                'body'      => '<p>' . $s['excerpt'] . '</p><p>Hubungi kami untuk informasi lebih lanjut mengenai layanan ini.</p>',
                'icon'      => $s['icon'],
                'order'     => $i,
                'is_active' => true,
            ]);
        }

        // Posts
        $admin = User::first();
        $posts = [
            ['title' => 'Pentingnya Publikasi Ilmiah bagi Peneliti Indonesia', 'cat' => 2],
            ['title' => 'Tips Menulis Abstrak yang Baik untuk Jurnal Internasional', 'cat' => 3],
            ['title' => 'Perkembangan Open Access dalam Dunia Akademik', 'cat' => 0],
        ];

        foreach ($posts as $p) {
            Post::create([
                'user_id'      => $admin->id,
                'category_id'  => $blogCategories[$p['cat']]->id,
                'title'        => $p['title'],
                'excerpt'      => 'Artikel ini membahas tentang ' . strtolower($p['title']) . ' secara mendalam dan komprehensif.',
                'body'         => '<p>Artikel ini membahas tentang ' . strtolower($p['title']) . '.</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.</p><p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident.</p>',
                'is_published' => true,
                'published_at' => now()->subDays(rand(1, 30)),
            ]);
        }
    }
}
