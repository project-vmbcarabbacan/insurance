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
            $table->string('type', 10);
            $table->string('make', 100);
            $table->string('model', 100);
            $table->integer('year');
            $table->string('identifier_type', 50)->comment('VIN | CHASSIS_NUMBER')->nullable();
            $table->string('plate_number', 30)->nullable();
            $table->string('engine_number', 50)->nullable();
            $table->decimal('current_value', 12, 2)->nullable();
            $table->string('currency', 10);
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
