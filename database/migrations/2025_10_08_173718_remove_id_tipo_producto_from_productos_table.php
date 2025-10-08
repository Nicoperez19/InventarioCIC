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
        Schema::table('productos', function (Blueprint $table) {
            $table->dropForeign(['id_tipo_producto']);
            $table->dropColumn('id_tipo_producto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->string('id_tipo_producto');
            $table->foreign('id_tipo_producto')->references('id_tipo_producto')->on('tipo__productos')->onDelete('restrict');
        });
    }
};