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
        Schema::table('vehicle_trims', function (Blueprint $table) {
            $table->string('type')->nullable();
            $table->integer('seats')->nullable();
            $table->integer('doors')->nullable();
            $table->string('engine_type')->nullable();
            $table->string('fuel_type')->nullable();
            $table->string('cylinders')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle_trims', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('seats');
            $table->dropColumn('doors');
            $table->dropColumn('engine_type');
            $table->dropColumn('cylinders');
        });
    }
};
