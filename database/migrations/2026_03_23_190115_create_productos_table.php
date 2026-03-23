<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nombre');
            $table->text('descripcion');
            $table->decimal('precio', 10, 2);
            $table->integer('stock')->default(0);
            $table->decimal('peso', 8, 2)->comment('En kilogramos');
            $table->decimal('alto', 8, 2)->nullable()->comment('En cm');
            $table->decimal('ancho', 8, 2)->nullable()->comment('En cm');
            $table->decimal('largo', 8, 2)->nullable()->comment('En cm');
            $table->string('categoria');
            $table->string('imagen')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};