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
        Schema::create('listas_reproduccion', function (Blueprint $table) {
            $table->id('id_lista_reproduccion'); // Use id() for auto-incrementing primary key
            $table->string('nombre', 255)->unique(); // Make 'Nombre' unique to avoid duplicate playlists 
            $table->unsignedBigInteger('equipo_id'); // Use unsignedBigInteger for foreign keys
            $table->foreign('equipo_id')->references('equipo_id')->on('equipos')->onDelete('cascade'); // Define foreign key relationship
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listas_reproduccion');
    }
};