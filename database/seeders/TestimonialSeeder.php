<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Dr. Ahmad Santoso',
                'position' => 'Dosen',
                'organization' => 'Universitas Indonesia',
                'content' => 'Bina Karya Cendekia sangat membantu dalam proses publikasi jurnal saya. Tim profesional dan respon cepat.',
                'rating' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Prof. Siti Rahayu',
                'position' => 'Peneliti',
                'organization' => 'LIPI',
                'content' => 'Pelayanan editing dan proofreading yang sangat berkualitas. Buku saya terbit dengan standar internasional.',
                'rating' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Budi Pratama',
                'position' => 'Mahasiswa S3',
                'organization' => 'ITB',
                'content' => 'Pelatihan penulisan ilmiahnya sangat bermanfaat. Metode yang diajarkan praktis dan mudah dipahami.',
                'rating' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Dewi Lestari',
                'position' => 'Penulis',
                'organization' => 'Freelance',
                'content' => 'Proses penerbitan buku sangat lancar dan transparan. Tim sangat supportif dari awal hingga akhir.',
                'rating' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}
