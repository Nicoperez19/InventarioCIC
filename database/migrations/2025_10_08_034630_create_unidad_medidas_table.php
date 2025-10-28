<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unidad_medidas', function (Blueprint $table) {
            $table->string('id_unidad')->primary();
            $table->string('nombre_unidad_medida');
            $table->softDeletes(); // Agregar soft deletes
            $table->timestamps();
            $table->index(['nombre_unidad_medida', 'deleted_at']);
            $table->index('created_at');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('unidad_medidas');
    }
};
