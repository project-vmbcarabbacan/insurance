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
        Schema::create('vehicle_policies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('policy_id')->constrained('policies')->cascadeOnDelete();
            $table->string('vehicle_type', 10);
            $table->string('vehicle_make', 100);
            $table->string('vehicle_model', 100);
            $table->integer('year');
            $table->string('identifier_type', 50)->comment('VIN | CHASSIS_NUMBER');
            $table->string('plate_number', 30);
            $table->string('engine_number', 50);
            $table->decimal('vehicle_value', 12, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policy_vehicles');
    }
};
