<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->string('icon')->nullable()->after('target');
            $table->string('subtitle')->nullable()->after('icon');
            $table->text('description')->nullable()->after('subtitle');
            $table->string('thumbnail')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn(['icon', 'subtitle', 'description', 'thumbnail']);
        });
    }
};
