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
        //
        Schema::table('members', function (Blueprint $table) {
            $table->string('first_name')->after('id'); // Adjust the position as needed
            $table->string('last_name')->after('first_name');
            $table->string('gender')->nullable()->after('last_name'); // Assuming gender can be nullable
            $table->string('user_name')->unique()->after('gender'); // Assuming username should be unique
            $table->unsignedBigInteger('country_id')->nullable()->references('id')->on('countries')->after('user_name'); // Foreign key for country
            $table->string('password');

            $table->dropForeign(['province_id']);
            // Remove fields
            $table->dropColumn(['province_id', 'full_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('members', function (Blueprint $table) {
            // Reverse the changes (remove added fields and re-add dropped fields)
            $table->dropColumn(['first_name', 'last_name', 'gender', 'user_name', 'country_id']);
            $table->string('full_name')->after('id'); // Re-add `full_name`
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('cascade'); // Re-add `province_id`
        });
    }
};
