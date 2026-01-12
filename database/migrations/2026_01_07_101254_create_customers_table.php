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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('status', 30)->default('lead');
            $table->string('first_name', 100);
            $table->string('last_name', 100)->nullable();
            $table->string('phone_country_code', 30);
            $table->string('phone_number', 30);
            $table->string('email', 150)->unique();
            $table->date('dob')->nullable();
            $table->string('gender', 10)->nullable();
            $table->timestamps();

            $table->unique(['phone_country_code', 'phone_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
