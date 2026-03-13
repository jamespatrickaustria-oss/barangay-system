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
        Schema::table('users', function (Blueprint $table) {
            // Add valid ID file path
            $table->string('valid_id')->nullable()->after('profile_photo');
            // Add resident ID number (for residents only)
            $table->string('resident_id_number')->nullable()->unique()->after('valid_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['valid_id', 'resident_id_number']);
        });
    }
};
