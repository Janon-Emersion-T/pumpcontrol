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
        Schema::create('meter_readings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pump_id');
            $table->unsignedBigInteger('fuel_id');
            $table->unsignedBigInteger('user_id');
            $table->decimal('opening_reading', 15, 3);
            $table->decimal('closing_reading', 15, 3);
            $table->decimal('total_dispensed', 15, 3);
            $table->decimal('price_per_liter', 8, 3);
            $table->decimal('total_amount', 15, 2);
            $table->date('reading_date');
            $table->time('reading_time');
            $table->enum('shift', ['morning', 'afternoon', 'evening', 'night']);
            $table->text('notes')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamps();

            $table->foreign('pump_id')->references('id')->on('pumps')->onDelete('cascade');
            $table->foreign('fuel_id')->references('id')->on('fuels')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');

            $table->index(['pump_id', 'reading_date']);
            $table->index(['fuel_id', 'reading_date']);
            $table->index('reading_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meter_readings');
    }
};
