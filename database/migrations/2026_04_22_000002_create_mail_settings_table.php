<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mail_settings', function (Blueprint $table) {
            $table->id();
            $table->string('driver')->default('smtp');
            $table->string('host')->nullable();
            $table->integer('port')->default(587);
            $table->string('encryption')->default('tls');
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('from_address')->nullable();
            $table->string('from_name')->nullable();
            $table->string('mailketing_api_key')->nullable();
            $table->string('mailketing_sender_email')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mail_settings');
    }
};
