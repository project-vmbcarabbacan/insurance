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
        Schema::table('leads', function (Blueprint $table) {
            $table->foreignId('customer_id')->nullable()->after('uuid')->constrained('customers')->cascadeOnDelete();
            $table->dateTime('due_date')->nullable()->after('status')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropIndex(['due_date']);
            $table->dropColumn(['customer_id', 'due_date']);
        });
    }
};
