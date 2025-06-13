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
        Schema::create('sys_params', function (Blueprint $table) {
            $table->id();
            $table->string('param_name', 255); // Parameter name
            $table->text('param_value'); // Parameter value (can be large text)
            $table->boolean('is_active')->default(true); // Active status, default to true
            $table->timestamp('recdate')->useCurrent(); // Record creation date, default to current timestamp
            $table->text('description')->nullable(); // Description, nullable field
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sys_params');
    }
};
