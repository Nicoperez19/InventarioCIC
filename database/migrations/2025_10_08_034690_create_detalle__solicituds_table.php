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
        Schema::create('detalle__solicituds', function (Blueprint $table) {
            $table->string('id_detalle_solicitud')->primary();
            $table->string('id_solicitud');
            $table->string('id_producto');
            $table->integer('cantidad_solicitud');
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_solicitud')->references('id_solicitud')->on('solicituds')->onDelete('cascade');
            $table->foreign('id_producto')->references('id_producto')->on('productos')->onDelete('cascade');
            
            // Índices para optimización
            $table->index(['id_solicitud', 'id_producto']);
            $table->index('id_producto');
            $table->index('created_at');
            
            // Índice único para evitar duplicados
            $table->unique(['id_solicitud', 'id_producto']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle__solicituds');
    }
};