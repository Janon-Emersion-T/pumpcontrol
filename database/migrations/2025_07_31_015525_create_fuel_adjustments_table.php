<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fuel_adjustments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pump_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fuel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // who recorded it

            $table->decimal('liters', 15, 2); // positive or negative
            $table->enum('type', ['gain', 'loss'])->default('loss');
            $table->text('reason')->nullable(); // e.g. "evaporation", "manual correction"
            $table->date('adjusted_at');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fuel_adjustments');
    }
};
