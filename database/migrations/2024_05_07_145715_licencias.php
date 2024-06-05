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
        Schema::create('licencias', function (Blueprint $table) {
            $table->id('licencia_id'); // Use id() for auto-incrementing primary key
            $table->string('licencia');
            $table->date('licencia_inicio')->default(now()); // Use 'now()' for current date
            $table->date('licencia_final')->default(now()->addMonth()); // Add one month to current date (Para crear licencias rapido, puedes tu poner la fecha que necesites desde los seeders o al crear valores en la tabla)
            $table->unsignedBigInteger('equipo_id')->nullable(); // Use unsignedBigInteger for foreign keys (Requerimiento)
            $table->foreign('equipo_id')->references('equipo_id')->on('equipos')->onDelete('cascade'); // Define foreign key relationship
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