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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('region_country_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->timestamps();
           
            $table->foreign('country_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('region_country_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('state_id')->references('id')->on('stores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
