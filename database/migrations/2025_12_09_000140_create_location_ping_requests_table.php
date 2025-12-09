<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('location_ping_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained('children')->cascadeOnDelete();
            $table->timestamp('requested_at');
            $table->timestamp('fulfilled_at')->nullable();
            $table->enum('status', ['pending', 'fulfilled', 'expired'])->default('pending');
            $table->timestamps();
            
            $table->index(['child_id', 'status', 'requested_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('location_ping_requests');
    }
};

