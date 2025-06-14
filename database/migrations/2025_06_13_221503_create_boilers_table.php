<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('boilers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('manufacturer_part_number')->nullable();
            $table->foreignId('boiler_manufacturer_id')->constrained()->onDelete('cascade');
            $table->foreignId('fuel_type_id')->constrained('boiler_fuel_types')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->string('sku')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boilers');
    }
};
