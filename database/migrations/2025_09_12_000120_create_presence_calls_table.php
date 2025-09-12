<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('presence_calls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained('children')->cascadeOnDelete();
            $table->timestamp('timestamp');
            $table->string('sequence');
            $table->unsignedTinyInteger('strength');
            $table->unsignedSmallInteger('duration_seconds');
            $table->enum('dnd_mode', ['respect','ignore'])->default('respect');
            $table->enum('status', ['sent','failed']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presence_calls');
    }
};


