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
        Schema::table('quotations', function (Blueprint $table) {
            $table->foreignId('provider_id')->after('lead_id')->constrained('policy_providers')->cascadeOnDelete();
            $table->foreignId('plan_id')->after('provider_id')->constrained('plans')->cascadeOnDelete();
        });
        Schema::table('policies', function (Blueprint $table) {
            $table->foreignId('provider_id')->after('quotation_id')->constrained('policy_providers')->cascadeOnDelete();
            $table->foreignId('plan_id')->after('provider_id')->constrained('plans')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropForeign(['provider_id']);
            $table->dropForeign(['plan_id']);

            $table->dropColumn(['provider_id', 'plan_id']);
        });
        Schema::table('policies', function (Blueprint $table) {
            $table->dropForeign(['provider_id']);
            $table->dropForeign(['plan_id']);

            $table->dropColumn(['provider_id', 'plan_id']);
        });
    }
};
