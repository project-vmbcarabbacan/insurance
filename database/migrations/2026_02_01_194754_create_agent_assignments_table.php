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
        Schema::create('agent_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id');
            $table->string('insurance_product_code');
            $table->foreign('insurance_product_code')->references('code')->on('insurance_products')->cascadeOnDelete();
            $table->foreignId('agent_id')->constrained('users');
            $table->enum('status', [
                'assigned',
                'contacted',
                'accepted',
                'rejected',
                'expired'
            ]);
            $table->timestamp('assigned_at');
            $table->timestamp('contacted_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            $table->index(['lead_id', 'agent_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_assignments');
    }
};
