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
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_cliente')->constrained('clientes');
            $table->foreignId('id_mascota')->constrained('mascotas');
            $table->foreignId('id_user')->constrained('users')->nulleable();
            $table->date('fecha');
            $table->time('hora');
            $table->string('estado')->default('PENDIENTE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
