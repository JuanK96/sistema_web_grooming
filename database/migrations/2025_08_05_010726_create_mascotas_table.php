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
        Schema::create('mascotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_cliente')->constrained('clientes');
            $table->string('name');
            $table->string('especie');
            $table->string('raza');
            $table->string('peso');
            $table->date('fecha_nacimiento');
            $table->string('sexo');
            $table->boolean('esterilizado')->default(false);
            $table->boolean('vacunado')->default(false);
            $table->string('enfermedades')->nullable();
            $table->string('descripcion');
            $table->string('ruta_foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mascotas');
    }
};
