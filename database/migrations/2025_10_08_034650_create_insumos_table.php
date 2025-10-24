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
        Schema::create('insumos', function (Blueprint $table) {
            $table->string('id_insumo')->primary();
            $table->string('nombre_insumo');
            $table->integer('stock_minimo')->default(0);
            $table->integer('stock_actual')->default(0);
            $table->string('id_unidad');
            $table->softDeletes(); // Agregar soft deletes
            $table->string('codigo_barra', 50)->nullable();
            $table->timestamps();

            // Foreign key
            $table->foreign('id_unidad')->references('id_unidad')->on('unidad_medidas')->onDelete('restrict');

            // Índices para optimización
            $table->index(['nombre_insumo', 'deleted_at']);
            $table->index(['id_unidad', 'deleted_at']);
            $table->index(['stock_actual', 'stock_minimo']); // Para consultas de stock bajo
            $table->index('created_at');

            // Índice compuesto para consultas de stock
            $table->index(['stock_actual', 'stock_minimo', 'deleted_at']);

            $table->foreignId('tipo_insumo_id')->nullable()->constrained('tipo_insumos')->onDelete('set null');
            $table->index('tipo_insumo_id');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insumos');
    }
};
