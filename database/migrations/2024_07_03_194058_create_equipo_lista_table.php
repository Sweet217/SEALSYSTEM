<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('equipo_lista', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('equipo_id');
            $table->unsignedBigInteger('lista_id');
            $table->timestamps();

            $table->foreign('equipo_id')->references('equipo_id')->on('equipos')->onDelete('cascade');
            $table->foreign('lista_id')->references('id_lista')->on('listas')->onDelete('cascade');

            // Asegurar que no haya listas duplicadas asociadas al mismo equipo
            $table->unique(['equipo_id', 'lista_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipo_lista');
    }
};
