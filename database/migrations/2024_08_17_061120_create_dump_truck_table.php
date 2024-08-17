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
        Schema::create('dump_truck', function (Blueprint $table) {
            $table->id('dt_id');
            $table->string('model');
            $table->string('can_carry');
            $table->unsignedBigInteger('td_id'); 
            $table->foreign('td_id')->references('td_id')->on('truck_drivers');
            $table->string('profile_pic')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dump_truck_models');
    }
};
