<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scholar_settings', function (Blueprint $table) {
            $table->id();
            $table->string('api_key')->nullable();
            $table->string('author_id')->nullable();
            $table->string('institution')->nullable();
            $table->boolean('auto_sync')->default(false);
            $table->integer('sync_interval')->default(24);
            $table->timestamp('last_sync')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scholar_settings');
    }
};
