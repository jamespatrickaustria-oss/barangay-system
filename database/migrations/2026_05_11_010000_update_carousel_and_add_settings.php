<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('carousel_slides')) {
            Schema::table('carousel_slides', function (Blueprint $table) {
                if (!Schema::hasColumn('carousel_slides', 'enabled')) {
                    $table->boolean('enabled')->default(true)->after('slot');
                }

                if (!Schema::hasColumn('carousel_slides', 'link_url')) {
                    $table->string('link_url')->nullable()->after('description');
                }

                if (!Schema::hasColumn('carousel_slides', 'open_in_new_tab')) {
                    $table->boolean('open_in_new_tab')->default(false)->after('link_url');
                }
            });
        }

        if (!Schema::hasTable('carousel_settings')) {
            Schema::create('carousel_settings', function (Blueprint $table) {
                $table->id();
                $table->boolean('autoplay_enabled')->default(true);
                $table->unsignedInteger('autoplay_speed')->default(4000); // milliseconds
                $table->boolean('pause_on_hover')->default(true);
                $table->boolean('loop')->default(true);
                $table->timestamps();
            });

            // Insert default settings
            DB::table('carousel_settings')->insert([
                'autoplay_enabled' => true,
                'autoplay_speed' => 4000,
                'pause_on_hover' => true,
                'loop' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('carousel_settings')) {
            Schema::dropIfExists('carousel_settings');
        }

        if (Schema::hasTable('carousel_slides')) {
            Schema::table('carousel_slides', function (Blueprint $table) {
                if (Schema::hasColumn('carousel_slides', 'open_in_new_tab')) {
                    $table->dropColumn('open_in_new_tab');
                }

                if (Schema::hasColumn('carousel_slides', 'link_url')) {
                    $table->dropColumn('link_url');
                }

                if (Schema::hasColumn('carousel_slides', 'enabled')) {
                    $table->dropColumn('enabled');
                }
            });
        }
    }
};