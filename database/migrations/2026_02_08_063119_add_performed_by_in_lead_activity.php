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
        Schema::table('lead_activities', function (Blueprint $table) {
            $table->foreignId('performed_by_id')->nullable()->after('lead_id')->constrained('users')->cascadeOnDelete();
            $table->string('performed_by_name')->nullable()->after('performed_by_id');
            $table->text('notes')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lead_activities', function (Blueprint $table) {
            $table->dropForeign(['performed_by_id']);
            $table->dropColumn(['performed_by_id', 'performed_by_name']);
        });
    }
};
