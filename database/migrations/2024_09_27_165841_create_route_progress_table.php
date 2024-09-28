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
        Schema::create('route_progress', function (Blueprint $table) {
            $table->id('rp_id');
            $table->unsignedBigInteger('r_id'); 
            $table->foreign('r_id')->references('r_id')->on('routes');
            $table->string('rp_progress', 500);
            $table->string('rp_status',500);
            $table->string('rp_current_location')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('route_progress');
    }
};
