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
        Schema::create('equipos', function (Blueprint $table) {
            $table->id('equipo_id'); //Id de la tabla autoincremental
            $table->string('nombre'); //Nombre del equipo "Kiosko Sonora"
            $table->string('mac'); //Mac del equipo, se terminara guardando una encriptacion AES
            $table->string('server_key')->nullable(); //Server key del equipo que se genera al querer crear una licencia. //Su valor puede ser nulo
            $table->unsignedBigInteger('licencia_id')->nullable(); //Relacion con licencia // Usar unsignedBigInteger para llaves foraneas (es un requerimiento de laravel)
            $table->unsignedBigInteger('user_id'); //Relacion con usuario // Usar unsignedBigInteger para llaves foraneas (es un requerimiento de laravel)
            $table->foreign('user_id')->references('user_id')->on('usuarios')->onDelete('cascade'); // Definir la relacion de user_id dentro de la tabla.
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