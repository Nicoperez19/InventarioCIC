<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('run')->primary();
            $table->string('nombre');
            $table->string('correo')->unique();
            $table->timestamp('correo_verificado_at')->nullable();
            $table->string('contrasena');
            $table->string('id_depto');
            $table->softDeletes(); // Agregar soft deletes
            $table->rememberToken();
            $table->timestamps();

            // Foreign key con mejor manejo
            $table->foreign('id_depto')->references('id_depto')->on('departamentos')->onDelete('restrict');
            
            // Índices para optimización
            $table->index(['correo', 'deleted_at']);
            $table->index(['id_depto', 'deleted_at']);
            $table->index('created_at');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
            
            // Índice para optimización
            $table->index('created_at');
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
            
            // Índices adicionales para optimización
            $table->index(['user_id', 'last_activity']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
