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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('user_id'); // Auto-incrementing primary key
            $table->string('nombre', 255); // Nombre del usuario
            $table->string('email', 255)->unique(); // Forzar un unico correo del usuario // Correo del usuario
            $table->string('password', 255);  // Contrasena
            $table->string('estado', 50); //Estado activo inactivo
            $table->string('telefono', 20); // Telefono del usuario xxx xxx xxxx
            $table->string('tipo_usuario', 50)->default('Operador'); // Tipo de usuario Administrador / Operador // Por default sera Operador ya que administrador solo seran pocos y se cargaran desde seeders en su mayoria.
            $table->string('remember_token')->nullable();
            $table->timestamps();

            $table->index('email');
        });
        //Laravel basics
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->constrained('usuarios', 'user_id')->onDelete('cascade')->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('usuarios');
    }
};