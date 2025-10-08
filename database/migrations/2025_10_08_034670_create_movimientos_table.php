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
            $table->enum('tipo_movimiento', ['entrada', 'salida']);
            $table->integer('cantidad');
            $table->dateTime('fecha_movimiento');
            $table->text('observaciones')->nullable();
            $table->string('id_producto');
            $table->string('id_usuario');

            $table->foreign('id_producto')->references('id_producto')->on('productos')->onDelete('cascade');
            $table->foreign('id_usuario')->references('run')->on('users')->onDelete('cascade');

            $table->timestamps();
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
