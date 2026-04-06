<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'emergency_contact_name')) {
                $table->string('emergency_contact_name')->nullable()->after('phone');
            }

            if (!Schema::hasColumn('users', 'emergency_contact_number')) {
                $table->string('emergency_contact_number')->nullable()->after('emergency_contact_name');
            }
        });

        // Normalize empty phone values to null so the unique index can be applied safely.
        DB::table('users')
            ->where(function ($query) {
                $query->whereNull('phone')
                    ->orWhere('phone', '');
            })
            ->update(['phone' => null]);

        // If duplicates exist, keep the earliest record value and null the rest.
        $duplicatePhones = DB::table('users')
            ->select('phone')
            ->whereNotNull('phone')
            ->where('phone', '!=', '')
            ->groupBy('phone')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('phone');

        foreach ($duplicatePhones as $phone) {
            $duplicateIds = DB::table('users')
                ->where('phone', $phone)
                ->orderBy('id')
                ->pluck('id')
                ->slice(1)
                ->values();

            if ($duplicateIds->isNotEmpty()) {
                DB::table('users')
                    ->whereIn('id', $duplicateIds->all())
                    ->update(['phone' => null]);
            }
        }

        // Set a practical default for existing records where nationality was left blank.
        DB::table('users')
            ->where(function ($query) {
                $query->whereNull('nationality')
                    ->orWhere('nationality', '');
            })
            ->update(['nationality' => 'Filipino']);

        try {
            Schema::table('users', function (Blueprint $table) {
                $table->unique('phone', 'users_phone_unique');
            });
        } catch (\Throwable $e) {
            // Ignore if the unique index already exists in the target database.
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->dropUnique('users_phone_unique');
            });
        } catch (\Throwable $e) {
            // Ignore if the unique index does not exist.
        }

        Schema::table('users', function (Blueprint $table) {
            $columns = [];

            if (Schema::hasColumn('users', 'emergency_contact_name')) {
                $columns[] = 'emergency_contact_name';
            }

            if (Schema::hasColumn('users', 'emergency_contact_number')) {
                $columns[] = 'emergency_contact_number';
            }

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
