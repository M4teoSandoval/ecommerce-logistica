<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('modelo')->nullable();
            $table->decimal('peso_maximo', 8, 2)->comment('Kg máximo de carga');
            $table->decimal('distancia_maxima', 8, 2)->comment('Km máximo de vuelo');
            $table->decimal('altitud_maxima', 8, 2)->comment('Metros máximos');
            $table->decimal('velocidad_promedio', 8, 2)->comment('Km/h');
            $table->decimal('capacidad_bateria', 8, 2)->comment('mAh');
            $table->decimal('consumo_por_km', 8, 2)->comment('mAh por km');
            $table->enum('estado', ['disponible', 'en_vuelo', 'mantenimiento', 'inactivo'])->default('disponible');
            $table->decimal('horas_vuelo_total', 10, 2)->default(0);
            $table->decimal('horas_vuelo_desde_mantenimiento', 10, 2)->default(0);
            $table->decimal('horas_alerta_mantenimiento', 8, 2)->default(50);
            $table->string('zonas_prohibidas')->nullable()->comment('JSON de coordenadas');
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drones');
    }
};