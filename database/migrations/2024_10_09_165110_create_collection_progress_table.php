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
        Schema::create('collection_progress', function (Blueprint $table) {
            $table->id('cp_id');
            $table->unsignedBigInteger('td_id')->nullable();
            $table->foreign('td_id')->references('td_id')->on('truck_drivers');
            $table->unsignedBigInteger('brgy_id')->nullable();
            $table->foreign('brgy_id')->references('brgy_id')->on('brgy_lists');
            $table->string('status')->nullable();
            $table->string('time_entered')->nullable();
            $table->string('time_out')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_progress');
    }
};
