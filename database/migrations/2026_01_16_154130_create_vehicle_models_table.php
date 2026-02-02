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
        Schema::create('vehicle_models', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reference_id');
            $table->unsignedBigInteger('vehicle_make_id');
            $table->integer('year');
            $table->string('name', 64);

            $table->unique(
                ['reference_id', 'year', 'vehicle_make_id', 'name'],
                'vehicle_model_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_models');
    }
};
