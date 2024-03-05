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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->enum('status', ['sent', 'delivered', 'error']);
            $table->foreignId('client_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('task_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
