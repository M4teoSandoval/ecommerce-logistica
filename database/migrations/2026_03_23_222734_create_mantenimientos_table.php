<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mantenimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('drone_id')->constrained('drones')->onDelete('cascade');
            $table->enum('tipo', ['preventivo', 'correctivo', 'incidente']);
            $table->date('fecha');
            $table->text('descripcion');
            $table->decimal('costo', 10, 2)->nullable();
            $table->string('tecnico')->nullable();
            $table->enum('estado', ['programado', 'en_proceso', 'completado'])->default('programado');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mantenimientos');
    }
};