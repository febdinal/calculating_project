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
        Schema::create('project_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            // Nullable: feature may be deleted from master list after project is saved
            $table->foreignId('feature_id')->nullable()->constrained()->nullOnDelete();
            // Price snapshot — these values are frozen at save time
            $table->string('feature_name');
            $table->string('category_name')->nullable();
            $table->enum('complexity', ['basic', 'standard', 'advanced', 'custom'])->default('standard');
            $table->integer('quantity')->default(1);
            $table->decimal('cost_price', 15, 2)->nullable();
            $table->decimal('selling_price', 15, 2)->nullable();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->boolean('is_included_in_package')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_features');
    }
};
