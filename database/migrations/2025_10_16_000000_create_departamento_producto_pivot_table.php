<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departamento_producto', function (Blueprint $table) {
            $table->string('id_depto');
            $table->string('id_producto');
            $table->timestamps();

            $table->primary(['id_depto', 'id_producto']);

            $table->foreign('id_depto')
                ->references('id_depto')
                ->on('departamentos')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('id_producto')
                ->references('id_producto')
                ->on('productos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departamento_producto');
    }
};
