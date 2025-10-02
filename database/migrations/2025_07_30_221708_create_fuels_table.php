<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fuels', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Petrol, Diesel, etc.
            $table->decimal('price_per_litre', 8, 2); // e.g., 145.50
            $table->decimal('stock_litres', 10, 2); // current stock
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fuels');
    }
};
