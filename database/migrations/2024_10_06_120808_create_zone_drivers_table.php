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
        Schema::create('zone_drivers', function (Blueprint $table) {
            $table->id('zd_id');
            $table->unsignedBigInteger('zone_id')->nullable();
            $table->foreign('zone_id')->references('zone_id')->on('zones');
            $table->unsignedBigInteger('td_id')->nullable();
            $table->foreign('td_id')->references('td_id')->on('truck_drivers');
            $table->string('type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zone_drivers');
    }
};
