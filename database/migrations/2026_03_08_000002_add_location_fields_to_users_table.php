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
            if (!Schema::hasColumn('users', 'house_no')) {
                $table->string('house_no')->nullable()->after('phone');
            }

            if (!Schema::hasColumn('users', 'barangay')) {
                $table->string('barangay')->nullable()->after('house_no');
            }

            if (!Schema::hasColumn('users', 'municipality_city')) {
                $table->string('municipality_city')->nullable()->after('barangay');
            }

            if (!Schema::hasColumn('users', 'nationality')) {
                $table->string('nationality')->nullable()->after('municipality_city');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [];

            if (Schema::hasColumn('users', 'house_no')) {
                $columns[] = 'house_no';
            }

            if (Schema::hasColumn('users', 'barangay')) {
                $columns[] = 'barangay';
            }

            if (Schema::hasColumn('users', 'municipality_city')) {
                $columns[] = 'municipality_city';
            }

            if (Schema::hasColumn('users', 'nationality')) {
                $columns[] = 'nationality';
            }

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
