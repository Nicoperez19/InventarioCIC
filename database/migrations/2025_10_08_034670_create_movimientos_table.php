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
        Schema::create('movimientos', function (Blueprint $table) {
            $table->string('id_movimiento')->primary();
            $table->enum('tipo_movimiento', ['entrada', 'salida', 'ajuste', 'inventario']);
            $table->integer('cantidad');
            $table->datetime('fecha_movimiento');
            $table->text('observaciones')->nullable();
            $table->string('id_producto');
            $table->string('id_usuario');
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_producto')->references('id_producto')->on('productos')->onDelete('cascade');
            $table->foreign('id_usuario')->references('run')->on('users')->onDelete('cascade');
            
            // Índices para optimización
            $table->index(['id_producto', 'fecha_movimiento']);
            $table->index(['id_usuario', 'fecha_movimiento']);
            $table->index(['tipo_movimiento', 'fecha_movimiento']);
            $table->index('fecha_movimiento');
            $table->index('created_at');
            
            // Índice compuesto para consultas de movimientos por producto y fecha
            $table->index(['id_producto', 'tipo_movimiento', 'fecha_movimiento']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos');
    }
};