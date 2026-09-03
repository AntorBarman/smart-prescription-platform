<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('medicine_categories')->onDelete('restrict');
            $table->foreignId('generic_id')->constrained('medicine_generics')->onDelete('restrict');
            $table->string('name', 200);
            $table->string('strength', 50); // e.g., "500mg", "10mg/ml"
            $table->string('dosage_form', 50); // tablet, capsule, syrup, injection
            $table->string('sku', 100)->unique();
            $table->string('barcode', 100)->nullable()->unique();
            $table->text('description')->nullable();
            $table->text('side_effects')->nullable();
            $table->text('contraindications')->nullable();
            $table->boolean('requires_prescription')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for fast search
            $table->index(['name', 'is_active']);
            $table->index(['sku', 'is_active']);
            $table->index(['barcode', 'is_active']);
            $table->index(['category_id', 'is_active']);
            $table->index(['generic_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};