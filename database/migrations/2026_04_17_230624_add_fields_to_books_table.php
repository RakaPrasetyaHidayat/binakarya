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
            // Add keywords field for book metadata and SEO
            $table->text('keywords')->nullable()->after('abstract');
            // Add edition field to track book editions
            $table->string('edition')->nullable()->after('keywords');
            // Add preview_url for PDF/PNG preview functionality
            $table->string('preview_url')->nullable()->after('edition');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['keywords', 'edition', 'preview_url']);
        });
    }
};
