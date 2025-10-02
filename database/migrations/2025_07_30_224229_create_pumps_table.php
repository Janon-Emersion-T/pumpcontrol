<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pumps', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Pump 1"
            $table->foreignId('fuel_id')->constrained()->cascadeOnDelete(); // fuel type for this pump
            $table->boolean('is_active')->default(true); // active/inactive flag
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pumps');
    }
};
