<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departamentos', function (Blueprint $table) {
            $table->string('id_depto')->primary();
            $table->string('nombre_depto');
            $table->timestamps();
            $table->index('nombre_depto');
            $table->index('created_at');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('departamentos');
    }
};
