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
        Schema::create('vehicle_trims', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reference_id');
            $table->unsignedBigInteger('vehicle_make_id');
            $table->unsignedBigInteger('vehicle_model_id');
            $table->integer('year');
            $table->string('name', 128);
            $table->string('description', 256);
            $table->decimal('msrp', 12, 0);

            $table->unique(['reference_id', 'vehicle_make_id', 'vehicle_model_id', 'year', 'name'], 'vehicle_trim_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_trims');
    }
};
