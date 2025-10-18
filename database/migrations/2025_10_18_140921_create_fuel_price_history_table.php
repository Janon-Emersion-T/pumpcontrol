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
        Schema::create('fuel_price_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fuel_id')->constrained('fuels')->onDelete('cascade');
            $table->decimal('price_per_litre', 8, 2);
            $table->date('effective_date'); // Date when this price becomes effective
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Who made the change
            $table->text('notes')->nullable(); // Reason for price change
            $table->boolean('is_active')->default(true); // Current active price
            $table->timestamps();

            // Index for faster queries
            $table->index(['fuel_id', 'effective_date']);
            $table->index(['fuel_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuel_price_history');
    }
};
