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
        Schema::create('managers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title')->nullable(); // position/title (e.g., "Ketua", "Direktur")
            $table->string('department')->nullable(); // department name
            $table->string('photo')->nullable(); // photo file path in storage
            $table->text('biography')->nullable(); // short bio
            $table->integer('sort_order')->default(0); // for ordering
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('managers');
    }
};
