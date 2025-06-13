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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('contact_no')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('inquiry_code')->nullable();
            $table->string('booking_type')->nullable();
            $table->string('flight_type')->nullable();
            $table->string('cruising_origin')->nullable();
            $table->string('cruising_location')->nullable();
            $table->string('cruising_month')->nullable();
            $table->string('flying_from')->nullable();
            $table->string('flying_to')->nullable();
            $table->date('departing_date')->nullable();
            $table->date('returning_date')->nullable();
            $table->boolean('add_hotel')->default(false);
            $table->date('hotel_checkin_date')->nullable();
            $table->date('hotel_checkout_date')->nullable();
            $table->string('travel_doc')->nullable();
            $table->string('travel_doc_nationality')->nullable();
            $table->string('travel_doc_other')->nullable();
            $table->text('additional_notes')->nullable();
            $table->integer('adults')->nullable();
            $table->integer('children')->nullable();
            $table->integer('infants')->nullable();
            $table->string('vacation_origin')->nullable();
            $table->string('vacation_total_days')->nullable();
            $table->string('vacation_destination')->nullable();
            $table->string('vacation_flexibility')->nullable();
            $table->string('vacation_preferred_airline')->nullable();
            $table->date('vacation_departing_date')->nullable();

            $table->unsignedBigInteger('member_id')->nullable()->references('id')->on('members')->onDelete('cascade');
            $table->boolean('usa_connection')->default(false);
            $table->string('payment_mode')->nullable();
            $table->unsignedBigInteger('agent_name')->nullable();
            $table->boolean('agree_on_terms')->default(false);
            $table->boolean('contact_by_email')->default(false);
            $table->string('images_attachment')->nullable();
            $table->string('booking_status')->nullable();
            $table->string('customer_identification')->nullable();
            $table->boolean('quote_submitted')->default(false);
            $table->boolean('customer_contacted')->default(false);
            $table->string('booking_ref')->nullable();
            $table->boolean('payment_received')->default(false);
            $table->boolean('insurance_purchased')->default(false);
            $table->boolean('ticket_issued')->default(false);
            $table->boolean('reservation_made')->default(false);
            $table->unsignedBigInteger('userid')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
