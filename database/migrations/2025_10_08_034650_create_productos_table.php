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
            $table->string('codigo_producto')->unique();
            $table->string('nombre_producto');
            $table->integer('stock_minimo')->default(0);
            $table->integer('stock_actual')->default(0);
            $table->text('observaciones')->nullable();

            $table->string('id_unidad');

            $table->foreign('id_unidad')->references('id_unidad')->on('unidads')->onDelete('restrict');

            $table->timestamps();
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
