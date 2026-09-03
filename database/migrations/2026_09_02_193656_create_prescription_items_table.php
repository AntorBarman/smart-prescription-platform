<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->constrained('prescriptions')->onDelete('cascade');
            $table->foreignId('medicine_id')->constrained('medicines')->onDelete('restrict');
            $table->string('dosage', 100); // e.g., "1 tablet", "2 teaspoons"
            $table->string('frequency', 100); // e.g., "3 times daily", "before meals"
            $table->integer('duration_days');
            $table->integer('quantity');
            $table->text('instructions')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['prescription_id', 'medicine_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescription_items');
    }
};