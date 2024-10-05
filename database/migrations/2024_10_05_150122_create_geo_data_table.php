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
        Schema::create('geo_data', function (Blueprint $table) {
            $table->id('gd_id');
            $table->string('type');
            $table->string('geometry_type');
            $table->unsignedBigInteger('brgy_id')->nullable();
            $table->foreign('brgy_id')->references('brgy_id')->on('brgy_lists');
            $table->string('property_color');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('geo_data');
    }
};
