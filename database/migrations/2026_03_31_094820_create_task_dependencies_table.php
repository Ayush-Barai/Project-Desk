<?php

declare(strict_types=1);

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
        Schema::create('task_dependencies', function (Blueprint $table): void {
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->foreignId('blocked_by_task_id')->constrained('tasks')->onDelete('cascade');
            $table->unique(['task_id', 'blocked_by_task_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_dependencies');
    }
};
