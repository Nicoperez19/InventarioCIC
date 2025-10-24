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
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_solicitud')->unique();
            $table->enum('tipo_solicitud', ['individual', 'masiva'])->default('individual');
            $table->text('observaciones')->nullable();
            $table->enum('estado', ['pendiente', 'aprobada', 'rechazada', 'entregada'])->default('pendiente');
            $table->string('user_id');
            $table->foreign('user_id')->references('run')->on('users')->onDelete('cascade');
            $table->string('departamento_id');
            $table->foreign('departamento_id')->references('id_depto')->on('departamentos')->onDelete('cascade');
            $table->foreignId('tipo_insumo_id')->nullable()->constrained('tipo_insumos')->onDelete('set null');
            $table->timestamp('fecha_solicitud')->useCurrent();
            $table->timestamp('fecha_aprobacion')->nullable();
            $table->timestamp('fecha_entrega')->nullable();
            $table->string('aprobado_por')->nullable();
            $table->foreign('aprobado_por')->references('run')->on('users')->onDelete('set null');
            $table->string('entregado_por')->nullable();
            $table->foreign('entregado_por')->references('run')->on('users')->onDelete('set null');
            $table->timestamps();
            
            // Índices para optimizar consultas
            $table->index(['estado', 'fecha_solicitud']);
            $table->index(['departamento_id', 'estado']);
            $table->index(['tipo_insumo_id', 'estado']);
            $table->index(['user_id', 'fecha_solicitud']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
    }
};