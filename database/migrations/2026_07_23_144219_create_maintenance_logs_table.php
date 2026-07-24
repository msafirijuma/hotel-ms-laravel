<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->foreignId('reported_by')->nullable()->constrained('users');
            $table->foreignId('fixed_by')->nullable()->constrained('users');
            $table->text('issue_description');
            $table->enum('status', ['pending', 'in_progress', 'fixed'])->default('pending');
            $table->timestamp('fixed_at')->nullable();
            $table->timestamp('reported_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_logs');
    }
};