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
        Schema::create('states', function (Blueprint $table) {
           
            $table->id();
            $table->unsignedBigInteger('region_country_id')->nullable();
            $table->string('name');
            $table->string('uf');
            $table->jsonb('info')->nullable(); // Parâmetros específicos do estado 
            $table->timestamps();

            // Chaves estrangeiras
            $table->foreign('region_country_id')->references('id')->on('states')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('states');
    }
};
