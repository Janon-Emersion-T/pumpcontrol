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
        // Rename the table
        Schema::rename('current_fuel_levels', 'pump_meter_readings');

        // Update the column structure
        Schema::table('pump_meter_readings', function (Blueprint $table) {
            $table->renameColumn('current_fuel', 'current_meter_reading');
            $table->decimal('previous_meter_reading', 10, 2)->nullable()->after('pump_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the column changes
        Schema::table('pump_meter_readings', function (Blueprint $table) {
            $table->dropColumn('previous_meter_reading');
            $table->renameColumn('current_meter_reading', 'current_fuel');
        });

        // Rename the table back
        Schema::rename('pump_meter_readings', 'current_fuel_levels');
    }
};
