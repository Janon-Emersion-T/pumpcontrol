<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pump_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pump_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fuel_id')->constrained()->cascadeOnDelete();

            $table->date('record_date'); // one record per day
            $table->decimal('opening_meter', 10, 2); // taken from pump
            $table->decimal('closing_meter', 10, 2); // entered by user
            $table->decimal('litres_sold', 10, 2)->default(0);
            $table->decimal('price_per_litre', 10, 2); // snapshot of that day
            $table->decimal('total_sales', 12, 2)->default(0);

            $table->timestamps();

            $table->unique(['pump_id', 'record_date']); // only one per pump per day
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pump_records');
    }
};
