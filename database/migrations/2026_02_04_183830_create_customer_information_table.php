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
        Schema::create('customer_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->string('meta_key', 50);
            $table->string('meta_value', 150);
            $table->timestamps();
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'dob', 'gender']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_information');

        Schema::table('customers', function (Blueprint $table) {
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->date('dob')->nullable();
            $table->string('gender', 10)->nullable();
        });
    }
};
