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
        Schema::create('sale_forms', function (Blueprint $table) {
            $table->id();
            $table->boolean('active')->default(true);
            $table->date('entry_date')->nullable();
            $table->string('inquiryid')->nullable();
            $table->string('pnr')->nullable();
            $table->string('sale_type')->nullable();
            $table->string('air_usa')->nullable();
            $table->string('air_other')->nullable();
            $table->string('inquiry_no')->nullable();
            $table->date('invoice_date')->nullable();
            $table->string('location')->nullable();
            $table->string('vacation')->nullable();
            $table->string('insurance')->nullable();
            $table->string('agent')->nullable();
            $table->string('supplier')->nullable();
            $table->string('fee_only')->nullable();
            $table->float('balance_due')->nullable();
            $table->string('last_name')->nullable();
            $table->string('first_name')->nullable();
            $table->integer('no_of_pax')->nullable();
            $table->string('ticket_no')->nullable();
            $table->float('gross_supplier')->nullable();
            $table->float('commission')->nullable();
            $table->float('markup')->nullable(); 
            $table->text('remarks_booking')->nullable(); 
            $table->float('customer_mc')->nullable(); 
            $table->float('customer_vi')->nullable(); 
            $table->float('debit')->nullable(); 
            $table->float('cash')->nullable(); 
            $table->float('cheque_draft')->nullable(); 
            $table->float('etransfer')->nullable(); 
            $table->float('past_credit')->nullable(); 
            $table->float('gift_card')->nullable(); 
            $table->float('amex_payment')->nullable(); 
            $table->float('other_payment')->nullable(); 
            $table->text('remarks_payment')->nullable(); 
            $table->date('departure_date')->nullable(); 
            $table->date('return_date')->nullable(); 
            $table->string('web_phone')->nullable(); 
            $table->string('gds')->nullable(); 
            $table->float('customer_card')->nullable(); 
            $table->float('company_emax')->nullable(); 
            $table->float('company_visa')->nullable(); 
            $table->float('company_master')->nullable(); 
            $table->float('other_supplier')->nullable(); 
            $table->text('remarks_supplier')->nullable(); 
            $table->text('other_remarks')->nullable(); 
            $table->string('secondary_agent')->nullable(); 
            $table->string('secondary_agent_share')->nullable(); 
            $table->string('gst')->nullable(); 
            $table->string('airline')->nullable();
            $table->unsignedBigInteger('userid')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_forms');
    }
};
