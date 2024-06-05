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
        Schema::create('equipos', function (Blueprint $table) {
            $table->id('equipo_id'); // Use id() for auto-incrementing primary key
            $table->string('nombre');
            $table->unsignedBigInteger('licencia_id')->nullable();
            $table->unsignedBigInteger('user_id'); // Use unsignedBigInteger for foreign keys (es un requerimiento de laravel)
            $table->foreign('user_id')->references('user_id')->on('usuarios')->onDelete('cascade'); // Define foreign key relationship
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('create_equipos_tables');
    }
};