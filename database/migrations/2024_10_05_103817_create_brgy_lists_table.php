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
        Schema::create('brgy_lists', function (Blueprint $table) {
            $table->id('brgy_id');
            $table->unsignedBigInteger('zone_id')->nullable();
            $table->foreign('zone_id')->references('zone_id')->on('zones');
            $table->string('brgy_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brgy_lists');
    }
};
