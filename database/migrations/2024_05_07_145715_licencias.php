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
        Schema::create('licencias', function (Blueprint $table) {
            $table->id('licencia_id'); // ID de la tabla
            $table->string('licencia'); // La licencia de licencias.
            $table->string('periodo'); //Periodo de la licencia, PRUEBA, MENSUAL, TRIMESTRAL, SEMESTRAL Y ANUAL
            $table->date('licencia_inicio')->nullable(); // Se usa now() para que guarde la fecha actual, es el inicio de la licencia
            $table->date('licencia_final')->nullable(); // Agrega 1 mes de vigencia (Para crear licencias rapido, puedes tu poner la fecha que necesites desde los seeders o al crear valores en la tabla)
            $table->unsignedBigInteger('equipo_id')->nullable(); // Usa unsignedBigInteger para foreign keys (Requerimiento)
            $table->foreign('equipo_id')->references('equipo_id')->on('equipos')->onDelete('cascade'); // Relacion de equipo_id con licencias.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('create_licencias_tables');
    }
};