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
        Schema::create('solicitud_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_id')->constrained('solicitudes')->onDelete('cascade');
            $table->string('insumo_id');
            $table->foreign('insumo_id')->references('id_insumo')->on('insumos')->onDelete('cascade');
            $table->integer('cantidad_solicitada');
            $table->integer('cantidad_aprobada')->nullable();
            $table->integer('cantidad_entregada')->nullable();
            $table->text('observaciones_item')->nullable();
            $table->enum('estado_item', ['pendiente', 'aprobado', 'rechazado', 'entregado'])->default('pendiente');
            $table->timestamps();
            
            // Índices para optimizar consultas
            $table->index(['solicitud_id', 'estado_item']);
            $table->index(['insumo_id', 'estado_item']);
            $table->unique(['solicitud_id', 'insumo_id']); // Evitar duplicados
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud_items');
    }
};