<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fuel_purchases', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pump_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fuel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete(); // optional
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // who recorded the purchase

            $table->decimal('liters', 15, 2);
            $table->decimal('price_per_liter', 15, 4);
            $table->decimal('total_cost', 15, 2);

            $table->date('purchase_date');
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fuel_purchases');
    }
};
