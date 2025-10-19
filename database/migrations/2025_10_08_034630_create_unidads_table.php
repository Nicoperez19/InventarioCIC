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
        Schema::create('unidads', function (Blueprint $table) {
            $table->string('id_unidad')->primary();
            $table->string('nombre_unidad');
            $table->softDeletes(); // Agregar soft deletes
            $table->timestamps();

            // Índices para optimización
            $table->index(['nombre_unidad', 'deleted_at']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unidads');
    }
};
