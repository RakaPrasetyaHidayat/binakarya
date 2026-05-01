<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            // Add Google Scholar related fields if they don't exist
            if (!Schema::hasColumn('books', 'scholar_id')) {
                $table->string('scholar_id')->nullable()->unique()->after('id');
            }
            
            if (!Schema::hasColumn('books', 'citation_count')) {
                $table->integer('citation_count')->default(0)->after('scholar_id');
            }
            
            if (!Schema::hasColumn('books', 'publication_year')) {
                $table->year('publication_year')->nullable()->after('citation_count');
            }
            
            if (!Schema::hasColumn('books', 'external_url')) {
                $table->string('external_url')->nullable()->after('publication_year');
            }
            
            if (!Schema::hasColumn('books', 'pdf_url')) {
                $table->string('pdf_url')->nullable()->after('external_url');
            }
            
            if (!Schema::hasColumn('books', 'doi')) {
                $table->string('doi')->nullable()->unique()->after('pdf_url');
            }
            
            if (!Schema::hasColumn('books', 'indexed_at')) {
                $table->timestamp('indexed_at')->nullable()->after('doi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn([
                'scholar_id',
                'citation_count',
                'publication_year',
                'external_url',
                'pdf_url',
                'doi',
                'indexed_at'
            ]);
        });
    }
};
