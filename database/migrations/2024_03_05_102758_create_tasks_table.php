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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('time', false, true)->nullable(false)->default(0);
            $table->boolean('active')->nullable(false)->default(true);
            $table->enum('type', ['once', 'daily', 'weekly', 'monthly', 'birthday'])->nullable(false)->default('once');
            $table->text('text')->nullable(false);
            $table->foreignId('segment_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
