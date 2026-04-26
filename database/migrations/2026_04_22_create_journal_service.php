<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Check if journal service already exists
        $exists = DB::table('services')->where('slug', 'journal-cendikia')->exists();
        
        if (!$exists) {
            DB::table('services')->insert([
                'title' => 'Journal Cendikia',
                'slug' => 'journal-cendikia',
                'excerpt' => 'Publikasi jurnal ilmiah berkala terpercaya untuk peneliti dan akademisi',
                'body' => 'Journal Cendikia adalah publikasi jurnal ilmiah berkala yang menerbitkan artikel-artikel penelitian berkualitas tinggi dari berbagai disiplin ilmu. Kami berkomitmen untuk menjaga standar akademik dan integritas ilmiah dalam setiap edisi.',
                'is_active' => true,
                'order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('services')->where('slug', 'journal-cendikia')->delete();
    }
};
