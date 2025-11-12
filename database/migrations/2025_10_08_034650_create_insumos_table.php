<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insumos', function (Blueprint $table) {
            $table->string('id_insumo')->primary();
            $table->string('nombre_insumo');
            $table->integer('stock_minimo')->default(0);
            $table->integer('stock_actual')->default(0);
            $table->string('id_unidad');
            $table->string('codigo_barra', 50)->nullable();
            $table->timestamps();
            $table->foreign('id_unidad')->references('id_unidad')->on('unidad_medidas')->onDelete('cascade');
            $table->index('nombre_insumo');
            $table->index('id_unidad');
            $table->index(['stock_actual', 'stock_minimo']); // Para consultas de stock bajo
            $table->index('created_at');
            $table->foreignId('tipo_insumo_id')->nullable()->constrained('tipo_insumos')->onDelete('cascade');
            $table->index('tipo_insumo_id');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('insumos');
    }
};
