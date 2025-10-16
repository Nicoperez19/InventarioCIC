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
        Schema::create('inventarios', function (Blueprint $table) {
            $table->string('id_inventario')->primary();
            $table->string('id_producto');
            $table->date('fecha_inventario');
            $table->integer('cantidad_inventario');
            $table->timestamps();

            // Foreign key
            $table->foreign('id_producto')->references('id_producto')->on('productos')->onDelete('cascade');
            
            // Índices para optimización
            $table->index(['id_producto', 'fecha_inventario']);
            $table->index('fecha_inventario');
            $table->index('created_at');
            
            // Índice único para evitar inventarios duplicados del mismo producto en la misma fecha
            $table->unique(['id_producto', 'fecha_inventario']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventarios');
    }
};
