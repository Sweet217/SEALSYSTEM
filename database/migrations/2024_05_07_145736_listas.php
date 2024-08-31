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
        Schema::create('listas', function (Blueprint $table) {
            $table->id('id_lista');
            $table->string('nombre', 255)->unique();
            $table->unsignedBigInteger('equipo_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable(); // Nueva columna para el usuario
            $table->boolean('seleccionado')->nullable();
            $table->foreign('equipo_id')->references('equipo_id')->on('equipos')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('usuarios')->onDelete('cascade'); // Relacionar con usuarios
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listas');
    }
};