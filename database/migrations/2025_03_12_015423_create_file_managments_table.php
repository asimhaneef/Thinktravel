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
        Schema::create('file_managments', function (Blueprint $table) {
            $table->id();            
            $table->unsignedBigInteger('userid')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->boolean('active')->default(true);
            $table->string('document_description')->nullable();
            $table->string('document_name')->nullable();
            $table->string('document_access')->nullable();
            $table->string('document_category')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_managments');
    }
};
