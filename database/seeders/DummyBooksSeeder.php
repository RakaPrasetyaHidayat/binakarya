<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DummyBooksSeeder extends Seeder
{
    /**
     * Seed book table with dummy data for preview purposes.
     * These records are marked as dummy and can be easily identified for deletion.
     */
    public function run(): void
    {
        // Get or create a dummy category for testing
        $dummyCategory = Category::firstOrCreate(
            ['name' => 'Dummy Preview', 'type' => 'book'],
            [
                'slug' => 'dummy-preview',
                'type' => 'book',
            ]
        );

        $dummyBooks = [
            [
                'title' => '[DUMMY] Panduan Lengkap Python untuk Pemula',
                'author' => 'John Developer',
                'isbn' => '978-DUMMY-0001',
                'doi' => '10.00000/dummy.python.2024',
                'published_year' => 2024,
                'edition' => '1st',
                'abstract' => 'Buku dummy untuk preview. Panduan lengkap memulai pemrograman Python dari nol dengan contoh praktis dan mudah dipahami.',
                'keywords' => 'python, programming, tutorial, dummy',
                'price' => 85000,
                'preview_url' => 'https://www.w3.org/WAI/WCAG21/Techniques/pdf/dummy.pdf',
            ],
            [
                'title' => '[DUMMY] Metodologi Penelitian Sosial',
                'author' => 'Dr. Dummy Researcher',
                'isbn' => '978-DUMMY-0002',
                'doi' => '10.00000/dummy.research.2024',
                'published_year' => 2024,
                'edition' => '2nd',
                'abstract' => 'Buku dummy untuk preview. Komprehensif tentang metodologi penelitian kualitatif dan kuantitatif untuk ilmu sosial.',
                'keywords' => 'research, methodology, social, dummy',
                'price' => 125000,
                'preview_url' => 'https://www.w3.org/WAI/WCAG21/Techniques/pdf/dummy.pdf',
            ],
            [
                'title' => '[DUMMY] Inovasi Teknologi AI di Era Digital',
                'author' => 'Tech Innovator',
                'isbn' => '978-DUMMY-0003',
                'doi' => '10.00000/dummy.ai.2024',
                'published_year' => 2024,
                'edition' => '1st',
                'abstract' => 'Buku dummy untuk preview. Eksplorasi mendalam tentang aplikasi kecerdasan buatan dalam berbagai industri modern.',
                'keywords' => 'artificial intelligence, technology, innovation, dummy',
                'price' => 150000,
                'preview_url' => 'https://www.w3.org/WAI/WCAG21/Techniques/pdf/dummy.pdf',
            ],
            [
                'title' => '[DUMMY] Strategi Pemasaran Digital Terpadu',
                'author' => 'Marketing Expert',
                'isbn' => '978-DUMMY-0004',
                'doi' => '10.00000/dummy.marketing.2024',
                'published_year' => 2024,
                'edition' => '1st',
                'abstract' => 'Buku dummy untuk preview. Panduan praktis strategi pemasaran digital yang efektif untuk bisnis skala kecil hingga enterprise.',
                'keywords' => 'marketing, digital, strategy, dummy',
                'price' => 95000,
                'preview_url' => 'https://www.w3.org/WAI/WCAG21/Techniques/pdf/dummy.pdf',
            ],
            [
                'title' => '[DUMMY] Ekologi dan Keberlanjutan Lingkungan',
                'author' => 'Prof. Eco Scientist',
                'isbn' => '978-DUMMY-0005',
                'doi' => '10.00000/dummy.ecology.2024',
                'published_year' => 2024,
                'edition' => '1st',
                'abstract' => 'Buku dummy untuk preview. Kajian mendalam tentang ekosistem, konservasi, dan pembangunan berkelanjutan.',
                'keywords' => 'ecology, environment, sustainability, dummy',
                'price' => 110000,
                'preview_url' => 'https://www.w3.org/WAI/WCAG21/Techniques/pdf/dummy.pdf',
            ],
            [
                'title' => '[DUMMY] Entrepreneurship: Dari Ide ke Aksi',
                'author' => 'Startup Founder',
                'isbn' => '978-DUMMY-0006',
                'doi' => '10.00000/dummy.startup.2024',
                'published_year' => 2024,
                'edition' => '1st',
                'abstract' => 'Buku dummy untuk preview. Pengalaman nyata membangun startup dari nol dan mencapai kesuksesan di era digital.',
                'keywords' => 'entrepreneurship, startup, business, dummy',
                'price' => 98000,
                'preview_url' => 'https://www.w3.org/WAI/WCAG21/Techniques/pdf/dummy.pdf',
            ],
            [
                'title' => '[DUMMY] Psikologi Positif untuk Kehidupan Sehari-hari',
                'author' => 'Dr. Psychology',
                'isbn' => '978-DUMMY-0007',
                'doi' => '10.00000/dummy.psychology.2024',
                'published_year' => 2024,
                'edition' => '1st',
                'abstract' => 'Buku dummy untuk preview. Aplikasi praktis psikologi positif untuk meningkatkan kesejahteraan mental dan kualitas hidup.',
                'keywords' => 'psychology, wellbeing, positive, dummy',
                'price' => 87000,
                'preview_url' => 'https://www.w3.org/WAI/WCAG21/Techniques/pdf/dummy.pdf',
            ],
            [
                'title' => '[DUMMY] Sejarah Indonesia dalam Perspektif Baru',
                'author' => 'Historian Prof.',
                'isbn' => '978-DUMMY-0008',
                'doi' => '10.00000/dummy.history.2024',
                'published_year' => 2024,
                'edition' => '1st',
                'abstract' => 'Buku dummy untuk preview. Interpretasi sejarah Indonesia dari masa kerajaan hingga era kontemporer dengan perspektif kritis.',
                'keywords' => 'history, indonesia, culture, dummy',
                'price' => 120000,
                'preview_url' => 'https://www.w3.org/WAI/WCAG21/Techniques/pdf/dummy.pdf',
            ],
            [
                'title' => '[DUMMY] Seni Menulis Novel yang Engaging',
                'author' => 'Author Expert',
                'isbn' => '978-DUMMY-0009',
                'doi' => '10.00000/dummy.writing.2024',
                'published_year' => 2024,
                'edition' => '1st',
                'abstract' => 'Buku dummy untuk preview. Teknik dan strategi penulisan novel yang menarik dari penulis berpengalaman.',
                'keywords' => 'writing, novel, creative, dummy',
                'price' => 92000,
                'preview_url' => 'https://www.w3.org/WAI/WCAG21/Techniques/pdf/dummy.pdf',
            ],
            [
                'title' => '[DUMMY] Nutrisi Sehat untuk Gaya Hidup Modern',
                'author' => 'Nutrition Expert',
                'isbn' => '978-DUMMY-0010',
                'doi' => '10.00000/dummy.nutrition.2024',
                'published_year' => 2024,
                'edition' => '1st',
                'abstract' => 'Buku dummy untuk preview. Panduan nutrisi lengkap untuk hidup sehat di tengah gaya hidup modern yang sibuk.',
                'keywords' => 'nutrition, health, lifestyle, dummy',
                'price' => 75000,
                'preview_url' => 'https://www.w3.org/WAI/WCAG21/Techniques/pdf/dummy.pdf',
            ],
        ];

        foreach ($dummyBooks as $bookData) {
            Book::create([
                'category_id' => $dummyCategory->id,
                'title' => $bookData['title'],
                'author' => $bookData['author'],
                'isbn' => $bookData['isbn'],
                'doi' => $bookData['doi'],
                'published_year' => $bookData['published_year'],
                'edition' => $bookData['edition'],
                'abstract' => $bookData['abstract'],
                'keywords' => $bookData['keywords'],
                'wa_number' => '+62 274 514911',
                'price' => $bookData['price'],
                'preview_url' => null,
                'is_published' => true,
                'cover_image' => null,
            ]);
        }

        $this->command->info('✅ 10 dummy books created successfully for preview.');
        $this->command->line('📌 These are marked as [DUMMY] in the title and can be safely deleted later.');
    }
}
