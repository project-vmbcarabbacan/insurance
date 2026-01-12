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
        Schema::create('policies', function (Blueprint $table) {
            $table->id();
            $table->string('policy_number', 50)->nullable()->unique();
            $table->foreignId('lead_id')->constrained('leads')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('insurance_product_code')->constrained('insurance_products')->cascadeOnDelete();
            $table->foreignId('quotation_id')->constrained('quotes')->cascadeOnDelete();
            $table->string('status', 30);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('premium_amount', 12, 2)->nullable();
            $table->decimal('vat', 12, 2);
            $table->string('currency', 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policies');
    }
};
