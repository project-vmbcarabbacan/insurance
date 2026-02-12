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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('lead_id')->nullable();

            // Polymorphic columns
            $table->string('owner_type', 50);
            $table->unsignedBigInteger('owner_id');
            $table->index(['owner_type', 'owner_id']);

            // Document info
            $table->string('original_name');
            $table->string('mime_type');
            $table->text('file_path');
            $table->bigInteger('size');
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('document_type_id')->nullable();

            // Audit
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->foreign('lead_id')
                ->references('id')
                ->on('leads')
                ->onDelete('cascade');

            $table->foreign('document_type_id')
                ->references('id')
                ->on('document_types')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
