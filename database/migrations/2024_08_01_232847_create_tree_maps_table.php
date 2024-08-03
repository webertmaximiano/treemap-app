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
        Schema::create('tree_maps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name');
            $table->double('value');
            $table->integer('level')->nullable();
            $table->string('color')->nullable();
            $table->string('status')->default('ativo');
            $table->string('path_file')->nullable();
            $table->json('reportData')->nullable();
            $table->double('ratio')->default(0);
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('tree_maps')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tree_maps');
    }
};
