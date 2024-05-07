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
        Schema::create('imagenes', function (Blueprint $table) {
            $table->id('imagen_id'); // Use id() for auto-incrementing primary key
            $table->string('nombre_archivo', 255); // VARCHAR(255) to store the image filename
            $table->integer('tiempo'); // INTEGER to store the image display time
            $table->binary('data'); // BLOB to store the image data
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
        Schema::dropIfExists('imagenes');
    }
};