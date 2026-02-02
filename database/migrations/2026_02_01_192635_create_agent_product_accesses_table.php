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
        Schema::create('agent_product_accesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('users');
            $table->string('insurance_product_code');
            $table->foreign('insurance_product_code')->references('code')->on('insurance_products')->cascadeOnDelete();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('priority')->default(1)->comment('lower = higher priority');
            $table->timestamps();

            $table->unique(['agent_id', 'insurance_product_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_product_accesses');
    }
};
