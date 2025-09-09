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
        Schema::create('current_fuel_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pump_id')->constrained()->onDelete('cascade');
            $table->decimal('current_fuel', 12, 2)->default(0); 
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('current_fuel_levels');
    }
};
