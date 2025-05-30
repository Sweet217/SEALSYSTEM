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
        Schema::table('equipos', function (Blueprint $table) {
            $table->foreign('licencia_id')->references('licencia_id')->on('licencias')->onDelete('cascade');
        }); //Para agregar la relacion que tiene licencia_id con equipos.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
    }
};