<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrematriculasTable extends Migration
{
    public function up(): void
    {
        Schema::create('prematriculas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Padre que registra
            $table->string('dni_estudiante')->unique();
            $table->string('nombres_estudiante');
            $table->string('apellidos_estudiante');
            $table->enum('genero', ['M', 'F']);
            $table->date('fecha_nacimiento');
            $table->string('grado_postula'); // campo adicional
            $table->string('estado')->default('Pendiente'); // estado de revisión
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prematriculas');
    }
}
