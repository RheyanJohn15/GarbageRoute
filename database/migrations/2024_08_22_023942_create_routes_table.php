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
        Schema::create('routes', function (Blueprint $table) {
            $table->id('r_id');
            $table->string('r_name');
            $table->string('r_start_longitude');
            $table->string('r_start_latitude');
            $table->string('r_end_longitude');
            $table->string('r_end_latitude');
            $table->string('r_assigned_truck');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routes_models');
    }
};
