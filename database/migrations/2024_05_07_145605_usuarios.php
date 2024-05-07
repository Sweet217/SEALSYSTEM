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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('usuario_id'); // Use id() for auto-incrementing primary key
            $table->string('nombre', 255);
            $table->string('correo', 255)->unique(); // Enforce unique email addresses
            $table->string('contrasena', 255); 
            $table->string('estado', 50);
            $table->string('telefono', 20);
            $table->string('tipo_usuario', 50)->default('Usuario');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('create_usuarios_tables');
    }
};