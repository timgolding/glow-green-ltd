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
        Schema::create('boiler_fuel_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('fuel_type_ref')->nullable(); // e.g., "lpg", "natural-gas"
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boiler_fuel_types');
    }
};
