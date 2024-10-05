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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id('comp_id');
            $table->string('comp_name');
            $table->string('comp_contact')->nullable();
            $table->string('comp_email')->nullable();
            $table->string('comp_nature')->nullable();
            $table->string('comp_remarks')->nullable();
            $table->integer('comp_status')->nullable();
            $table->string('comp_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
