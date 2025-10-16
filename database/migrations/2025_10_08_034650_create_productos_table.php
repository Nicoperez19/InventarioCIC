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
        Schema::create('productos', function (Blueprint $table) {
            $table->string('id_producto')->primary();
            $table->string('nombre_producto');
            $table->integer('stock_minimo')->default(0);
            $table->integer('stock_actual')->default(0);
            $table->text('observaciones')->nullable();
            $table->string('id_unidad');
            $table->softDeletes(); // Agregar soft deletes
            $table->timestamps();

            // Foreign key
            $table->foreign('id_unidad')->references('id_unidad')->on('unidads')->onDelete('restrict');
            
            // Índices para optimización
            $table->index(['nombre_producto', 'deleted_at']);
            $table->index(['id_unidad', 'deleted_at']);
            $table->index(['stock_actual', 'stock_minimo']); // Para consultas de stock bajo
            $table->index('created_at');
            
            // Índice compuesto para consultas de stock
            $table->index(['stock_actual', 'stock_minimo', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
