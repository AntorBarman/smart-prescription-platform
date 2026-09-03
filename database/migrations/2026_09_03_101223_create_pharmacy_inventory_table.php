<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pharmacy_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pharmacy_id')->constrained('pharmacies')->onDelete('cascade');
            $table->foreignId('medicine_id')->constrained('medicines')->onDelete('restrict');
            $table->integer('stock_quantity')->default(0);
            $table->decimal('selling_price', 10, 2)->default(0);
            $table->integer('reorder_level')->default(10);
            $table->timestamps();

            $table->unique(['pharmacy_id', 'medicine_id']);
            $table->index(['pharmacy_id', 'stock_quantity']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pharmacy_inventory');
    }
};