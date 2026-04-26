<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class DummyServicesSeeder extends Seeder
{
    /**
     * Seed service table with dummy data for preview purposes.
     * These records are marked as dummy and can be easily identified for deletion.
     */
    public function run(): void
    {
        $dummyServices = [
            [
                'title' => '[DUMMY] Konsultasi Penelitian Gratis',
                'excerpt' => 'Dapatkan konsultasi gratis dari para ahli tentang metodologi dan strategi penelitian Anda.',
                'body' => '<h3>Konsultasi Penelitian Gratis</h3><p>Layanan ini membantu peneliti untuk:</p><ul><li>Merancang metodologi penelitian yang tepat</li><li>Mengidentifikasi gap dalam penelitian</li><li>Menyusun rencana publikasi</li><li>Mengatasi permasalahan teknis penelitian</li></ul><p>Hubungi kami untuk menjadwalkan sesi konsultasi gratis Anda.</p>',
                'icon' => '🔬',
                'order' => 0,
                'is_active' => true,
            ],
            [
                'title' => '[DUMMY] Pelatihan Penulisan Jurnal',
                'excerpt' => 'Workshop intensif tentang cara menulis artikel jurnal yang siap untuk publikasi internasional.',
                'body' => '<h3>Pelatihan Penulisan Jurnal</h3><p>Dalam pelatihan ini peserta akan belajar:</p><ul><li>Struktur artikel jurnal yang efektif</li><li>Teknik penulisan yang jelas dan sistematis</li><li>Cara handling reviewer feedback</li><li>Strategi publikasi di top-tier journals</li></ul><p>Instruktur adalah akademisi berpengalaman dengan publikasi internasional.</p>',
                'icon' => '📚',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => '[DUMMY] Editing & Proofreading Profesional',
                'excerpt' => 'Layanan penyuntingan berkualitas tinggi untuk naskah akademik, tesis, dan artikel jurnal.',
                'body' => '<h3>Editing & Proofreading</h3><p>Tim editor profesional kami menawarkan:</p><ul><li>Copy editing untuk grammar dan style</li><li>Substantive editing untuk struktur dan argumen</li><li>Proofreading detail untuk publikasi akhir</li><li>Language polishing untuk naskah non-English native</li></ul><p>Semua editor kami memiliki pengalaman di bidang akademik dan publikasi.</p>',
                'icon' => '✏️',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => '[DUMMY] Penerbitan E-Book Akademik',
                'excerpt' => 'Layanan penerbitan digital untuk buku, monograf, atau kumpulan artikel dengan ISBN.',
                'body' => '<h3>Penerbitan E-Book</h3><p>Kami membantu Anda menerbitkan karya akademik dalam format digital dengan:</p><ul><li>Desain layout profesional</li><li>Pengurusan ISBN</li><li>Distribusi digital</li><li>Dukungan marketing dan promosi</li></ul><p>Proses cepat, terjangkau, dan transparan untuk semua jenis publikasi akademik.</p>',
                'icon' => '📖',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'title' => '[DUMMY] Indexing & Database Submission',
                'excerpt' => 'Bantuan submit artikel Anda ke berbagai indexing database seperti Scopus, Google Scholar, dan lainnya.',
                'body' => '<h3>Indexing & Database Submission</h3><p>Kami membantu meningkatkan visibilitas penelitian Anda melalui:</p><ul><li>Submission ke Google Scholar</li><li>Pendaftaran ke Scopus index</li><li>Listing di berbagai open access database</li><li>Cross-reference management</li></ul><p>Semakin banyak indexing, semakin mudah penelitian Anda ditemukan oleh akademisi lain.</p>',
                'icon' => '🔍',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'title' => '[DUMMY] Mentoring Program untuk Peneliti Muda',
                'excerpt' => 'Program mentoring intensif untuk memandu peneliti muda dalam mengembangkan karir akademik.',
                'body' => '<h3>Mentoring untuk Peneliti Muda</h3><p>Program ini dirancang untuk:</p><ul><li>Membimbing dalam mengidentifikasi research topics</li><li>Mengembangkan research proposal yang kuat</li><li>Strategi publikasi bertahap</li><li>Networking dengan komunitas akademik</li></ul><p>Mentor kami adalah profesor dan peneliti senior dengan track record publikasi internasional.</p>',
                'icon' => '👨‍🎓',
                'order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($dummyServices as $serviceData) {
            Service::create($serviceData);
        }

        $this->command->info('✅ 6 dummy services created successfully for preview.');
        $this->command->line('📌 These are marked as [DUMMY] in the title and can be safely deleted later.');
    }
}
