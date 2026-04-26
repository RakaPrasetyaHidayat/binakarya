<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('author');
            $table->string('isbn')->nullable();
            $table->string('doi')->nullable();
            $table->year('published_year')->nullable();
            $table->text('abstract')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('pdf_file')->nullable();
            $table->string('wa_number')->nullable();
            $table->string('shopee_url')->nullable();
            $table->string('tokopedia_url')->nullable();
            $table->string('custom_url')->nullable();
            $table->string('custom_url_label')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
