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
        Schema::table('users', function (Blueprint $table) {
            // Add new fields
            $table->unsignedBigInteger('manager_id')->nullable()->after('user_type');
            $table->boolean('is_active')->default(true)->after('manager_id');
            $table->boolean('display_onlist')->default(true)->after('is_active');
            $table->timestamp('last_login')->nullable()->after('display_onlist');

            // Remove the `is_first_login` field
            // $table->dropColumn('is_first_login');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('users', function (Blueprint $table) {
            // Reverse the changes (remove added fields and re-add `is_first_login`)
            $table->dropColumn(['user_type', 'manager_id', 'is_active', 'display_onlist', 'last_login']);
            // $table->boolean('is_first_login')->default(true);
        });
    }
};
