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
        Schema::create('dumpsite_turnovers', function (Blueprint $table) {
            $table->id('dst_id');
            $table->unsignedBigInteger('td_id')->nullable();
            $table->foreign('td_id')->references('td_id')->on('truck_drivers');
            $table->unsignedBigInteger('dt_id')->nullable();
            $table->foreign('dt_id')->references('dt_id')->on('dump_truck');
            $table->integer('percentage')->nullable()->default(100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dumpsite_turnovers');
    }
};
