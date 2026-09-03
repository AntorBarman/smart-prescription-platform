<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->string('prescription_number', 50)->unique();
            $table->foreignId('doctor_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('patient_id')->constrained('patients')->onDelete('restrict');
            $table->text('diagnosis')->nullable();
            $table->text('notes')->nullable();
            $table->string('status', 30)->default('issued'); // draft, issued, fulfilled, cancelled, expired
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['doctor_id', 'status']);
            $table->index(['patient_id', 'status']);
            $table->index(['prescription_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};