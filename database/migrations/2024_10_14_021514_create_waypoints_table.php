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
        Schema::create('waypoints', function (Blueprint $table) {
            $table->id('wp_id');
            $table->unsignedBigInteger('brgy_id')->nullable();
            $table->foreign('brgy_id')->references('brgy_id')->on('brgy_lists');
            $table->unsignedBigInteger('zone_id')->nullable();
            $table->foreign('zone_id')->references('zone_id')->on('zones');
            $table->string('longitude');
            $table->string('latitude');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waypoints');
    }
};
