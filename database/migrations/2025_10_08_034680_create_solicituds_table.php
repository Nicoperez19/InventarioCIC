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
        Schema::create('solicituds', function (Blueprint $table) {
            $table->string('id_solicitud')->primary();
            $table->date('fecha_solicitud');
            $table->enum('estado_solicitud', ['pendiente', 'aprobada', 'rechazada', 'entregada'])->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->string('id_usuario');
            $table->softDeletes(); // Agregar soft deletes
            $table->timestamps();

            // Foreign key
            $table->foreign('id_usuario')->references('run')->on('users')->onDelete('cascade');

            // Índices para optimización
            $table->index(['estado_solicitud', 'deleted_at']);
            $table->index(['id_usuario', 'estado_solicitud']);
            $table->index(['fecha_solicitud', 'estado_solicitud']);
            $table->index('created_at');

            // Índice compuesto para consultas frecuentes
            $table->index(['estado_solicitud', 'fecha_solicitud', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicituds');
    }
};
