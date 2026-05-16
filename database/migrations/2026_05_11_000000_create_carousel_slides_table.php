<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carousel_slides', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('slot')->unique();
            $table->string('image_path')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        $now = now();

        DB::table('carousel_slides')->insert([
            ['slot' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['slot' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['slot' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['slot' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['slot' => 5, 'created_at' => $now, 'updated_at' => $now],
            ['slot' => 6, 'created_at' => $now, 'updated_at' => $now],
            ['slot' => 7, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('carousel_slides');
    }
};
