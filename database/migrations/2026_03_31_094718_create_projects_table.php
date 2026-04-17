<?php

declare(strict_types=1);

use App\Enums\ProjectStatus;
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
        Schema::create('projects', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('workspace_id')->constrained('workspaces')->onDelete('cascade');
            $table->string('name');
            $table->string('slug');
            $table->string('description')->nullable();
            $table->string('status')->default(ProjectStatus::Planning->value);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('budget_hours', 8, 2)->nullable();
            $table->string('color')->default('blue');
            $table->softDeletes('deleted_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
