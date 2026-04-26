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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('action'); // create, update, delete, login, logout, view
            $table->string('model_type')->nullable(); // Model class name (e.g., 'App\Models\Book')
            $table->unsignedBigInteger('model_id')->nullable(); // ID of the modified record
            $table->string('ip_address');
            $table->string('user_agent')->nullable();
            $table->json('old_values')->nullable(); // Previous data before change
            $table->json('new_values')->nullable(); // New data after change
            $table->text('description')->nullable(); // Human readable description
            $table->timestamps();
            
            // Indexes for faster queries
            $table->index('user_id');
            $table->index('action');
            $table->index('model_type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
