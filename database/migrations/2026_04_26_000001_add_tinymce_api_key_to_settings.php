<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Settings table uses key-value, so we just ensure the key exists via seeder or direct insert
        // No schema change needed for settings table
    }

    public function down(): void
    {
        // No rollback needed for key-value settings
    }
};

