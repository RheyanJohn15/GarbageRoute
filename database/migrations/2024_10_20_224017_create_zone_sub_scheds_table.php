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
        Schema::create('zone_sub_scheds', function (Blueprint $table) {
            $table->id('zss_id');
            $table->unsignedBigInteger('zone_id');
            $table->unsignedBigInteger('wp_id');
            $table->string('days');
            $table->foreign('zone_id')->references('zone_id')->on('zones');
            $table->foreign('wp_id')->references('wp_id')->on('waypoints');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zone_sub_scheds');
    }
};
