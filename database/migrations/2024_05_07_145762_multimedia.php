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
        Schema::create('multimedia', function (Blueprint $table) {
            $table->id('multimedia_id'); // Use id() for auto-incrementing primary key
            $table->string('tipo', 12); // Se refiere al tipo de multimedia (video, imagen o enlace)
            $table->unsignedInteger('posicion')->nullable();
            $table->unsignedBigInteger('id_lista'); // Use unsignedBigInteger for foreign keys
            $table->foreign('id_lista')->references('id_lista')->on('listas')->onDelete('cascade'); // Define foreign key relationship
            $table->timestamps();

            $table->unique(['id_lista', 'posicion']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('multimedia');
    }
};