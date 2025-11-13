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
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->string('tipo'); // 'solicitud', 'aprobacion', 'rechazo', etc.
            $table->string('titulo');
            $table->text('mensaje');
            $table->string('user_id'); // RUN del usuario que recibe la notificación
            $table->foreign('user_id')->references('run')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('solicitud_id')->nullable(); // ID de la solicitud relacionada
            $table->foreign('solicitud_id')->references('id')->on('solicitudes')->onDelete('cascade');
            $table->boolean('leida')->default(false);
            $table->timestamp('leida_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'leida']);
            $table->index(['solicitud_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
