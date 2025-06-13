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
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('region');
            $table->string('region_desc')->nullable();
            $table->string('sort_order')->nullable();
            $table->foreignId('parent_region_id')->references('id')->on('regions')->onDelete('cascade');
            $table->foreignId('region_category_id')->references('id')->on('region_categories')->onDelete('cascade');
            $table->foreignId('region_type_id')->references('id')->on('region_types')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regions');
    }
};
