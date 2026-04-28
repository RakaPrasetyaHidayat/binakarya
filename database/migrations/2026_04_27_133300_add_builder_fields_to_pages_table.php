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
        Schema::table('pages', function (Blueprint $table) {
            $table->string('content_mode')->default('classic')->after('content');
            $table->json('content_blocks')->nullable()->after('content_mode');
            $table->longText('custom_css')->nullable()->after('content_blocks');
            $table->longText('custom_js')->nullable()->after('custom_css');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['content_mode', 'content_blocks', 'custom_css', 'custom_js']);
        });
    }
};
