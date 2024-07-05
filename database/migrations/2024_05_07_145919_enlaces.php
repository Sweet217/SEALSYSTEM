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
        Schema::create('enlaces', function (Blueprint $table) {
            $table->id('enlace_id'); // Use id() for auto-incrementing primary key
            $table->string('data', 255); // VARCHAR(255) to store the YouTube link (Puede ser muy largo)
            $table->string('nombre_enlace', 255); //
            $table->unsignedBigInteger('multimedia_id')->nullable(); // Use unsignedBigInteger for foreign keys
            $table->foreign('multimedia_id')->references('multimedia_id')->on('multimedia')->onDelete('cascade'); // Define foreign key relationship
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enlaces');
    }
};