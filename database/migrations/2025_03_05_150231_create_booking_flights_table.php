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
        Schema::create('booking_flights', function (Blueprint $table) {
            $table->id();            
            $table->string('flying_from')->nullable();
            $table->string('flying_to')->nullable();
            $table->date('departing_date')->nullable();
            $table->date('returning_date')->nullable();
            $table->unsignedBigInteger('userid')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('booking_id')->nullable()->references('id')->on('bookings')->onDelete('cascade');
            $table->integer('total_days')->nullable();
            $table->string('flexibility')->nullable();
            $table->string('preferred_airline')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_flights');
    }
};
