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
        Schema::create('active_drivers', function (Blueprint $table) {
            $table->id('ad_id');
            $table->unsignedBigInteger('td_id')->nullable();
            $table->foreign('td_id')->references('td_id')->on('truck_drivers');
            $table->string('ad_coordinates')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('active_drivers');
    }
};
