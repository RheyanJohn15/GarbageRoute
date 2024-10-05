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
        Schema::create('geo_data_coordinates', function (Blueprint $table) {
            $table->id('gdc_id');
            $table->unsignedBigInteger('gd_id')->nullable();
            $table->foreign('gd_id')->references('gd_id')->on('geo_data');
            $table->string('gd_longitude');
            $table->string('gd_latitude');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('geo_data_coordinates');
    }
};
