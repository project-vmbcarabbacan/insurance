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
        Schema::create('agent_assignment_settings', function (Blueprint $table) {
            $table->id();
            $table->string('insurance_product_code');
            $table->foreign('insurance_product_code')->references('code')->on('insurance_products')->cascadeOnDelete();
            $table->enum('strategy', ['round_robin', 'least_loaded', 'manual']);
            $table->unsignedInteger('max_active_leads_per_agent')->default(10);
            $table->unsignedInteger('reassignment_timeout_minutes')->default(1440);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_assignment_settings');
    }
};
