<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;

class DummyPostsSeeder extends Seeder
{
    /**
     * Seed post table with dummy data for preview purposes.
     * These records are marked as dummy and can be easily identified for deletion.
     */
    public function run(): void
    {
        // Get or create a dummy category for testing
        $dummyCategory = Category::firstOrCreate(
            ['name' => 'Dummy Preview', 'type' => 'blog'],
            [
                'slug' => 'dummy-preview',
                'type' => 'blog',
            ]
        );

        $dummyPosts = [
            [
                'title' => '[DUMMY] 10 Tips Produktivitas Kerja di Era Remote',
                'excerpt' => 'Bagaimana cara meningkatkan produktivitas saat bekerja dari rumah? Ikuti 10 tips praktis dari para expert.',
                'content' => '<p>Bekerja dari rumah memerlukan disiplin dan strategi khusus. Dalam artikel ini kami membagikan 10 tips yang telah terbukti meningkatkan produktivitas kerja remote.</p><p>Dengan menerapkan tips-tips ini, Anda dapat memaksimalkan efisiensi kerja sambil menjaga work-life balance.</p>',
            ],
            [
                'title' => '[DUMMY] Tren Teknologi 2024 yang Harus Anda Ketahui',
                'excerpt' => 'Teknologi terus berkembang. Apa saja tren teknologi terbaru yang akan membentuk masa depan?',
                'content' => '<p>Tahun 2024 membawa berbagai inovasi teknologi yang mengubah cara kita hidup dan bekerja.</p><p>Dari AI yang semakin canggih hingga quantum computing, mari kita bahas tren teknologi terkini.</p>',
            ],
            [
                'title' => '[DUMMY] Panduan Publikasi Penelitian di Jurnal Internasional',
                'excerpt' => 'Ingin mempublikasikan penelitian Anda di jurnal internasional? Berikut adalah panduan lengkapnya.',
                'content' => '<p>Mempublikasikan penelitian di jurnal internasional adalah impian banyak akademisi.</p><p>Proses ini memang kompleks namun dengan persiapan yang tepat, Anda bisa meraih kesempatan emas ini.</p>',
            ],
            [
                'title' => '[DUMMY] Pentingnya Kolaborasi Interdisiplin dalam Penelitian',
                'excerpt' => 'Mengapa kolaborasi multidisiplin semakin penting dalam dunia akademik modern?',
                'content' => '<p>Era globalisasi mengharuskan peneliti untuk bekerja lintas disiplin ilmu.</p><p>Kolaborasi ini tidak hanya menghasilkan penelitian yang lebih kaya perspektif namun juga lebih relevan dengan masalah nyata di masyarakat.</p>',
            ],
            [
                'title' => '[DUMMY] Metodologi Penelitian Kualitatif: Panduan Praktis',
                'excerpt' => 'Apa itu penelitian kualitatif dan bagaimana melaksanakannya dengan baik?',
                'content' => '<p>Penelitian kualitatif memungkinkan peneliti untuk memahami fenomena sosial secara mendalam.</p><p>Dalam artikel ini kami jelaskan metodologi, teknik pengumpulan data, dan analisis dalam penelitian kualitatif.</p>',
            ],
            [
                'title' => '[DUMMY] Etika Penelitian dan Tanggung Jawab Akademik',
                'excerpt' => 'Etika penelitian adalah fondasi integritas akademik. Bagaimana menerapkannya dalam penelitian Anda?',
                'content' => '<p>Peneliti memiliki tanggung jawab moral untuk melaksanakan penelitian dengan etis dan bertanggung jawab.</p><p>Kami diskusikan pentingnya informed consent, kerahasiaan data, dan integritas dalam penelitian.</p>',
            ],
            [
                'title' => '[DUMMY] Cara Menggunakan Mendeley untuk Manajemen Referensi',
                'excerpt' => 'Tool manajemen referensi Mendeley dapat menghemat waktu dan usaha Anda. Pelajari cara menggunakannya.',
                'content' => '<p>Mengelola ratusan referensi penelitian bisa menjadi pekerjaan yang memakan waktu.</p><p>Mendeley adalah tool yang powerful untuk mengorganisir, mengelola, dan membagikan referensi penelitian Anda.</p>',
            ],
            [
                'title' => '[DUMMY] Menulis Abstrak Berkualitas untuk Publikasi Jurnal',
                'excerpt' => 'Abstrak yang baik adalah kunci untuk menarik pembaca. Bagaimana menulis abstrak yang efektif?',
                'content' => '<p>Abstrak adalah ringkasan singkat dari penelitian Anda yang sering menjadi faktor penentu apakah seseorang akan membaca naskah lengkap.</p><p>Tips-tips dalam artikel ini akan membantu Anda menulis abstrak yang compelling dan informatif.</p>',
            ],
        ];

        $publishContent = false;
        $startDate = Carbon::now()->subMonths(3);

        foreach ($dummyPosts as $index => $postData) {
            Post::create([
                'category_id' => $dummyCategory->id,
                'title' => $postData['title'],
                'slug' => \Illuminate\Support\Str::slug($postData['title']),
                'excerpt' => $postData['excerpt'],
                'body' => $postData['content'],
                'featured_image' => 'https://via.placeholder.com/1200x600?text=' . urlencode(substr($postData['title'], 0, 20)),
                'published_at' => $publishContent ? $startDate->addDays($index) : null,
                'is_published' => $publishContent,
                'created_at' => $startDate->addDays($index),
                'updated_at' => $startDate->addDays($index),
            ]);
        }

        $this->command->info('✅ 8 dummy posts created successfully for preview.');
        $this->command->line('📌 These are marked as [DUMMY] in the title and not published yet.');
        $this->command->line('💡 Publish them via the admin panel to make them visible.');
    }
}
