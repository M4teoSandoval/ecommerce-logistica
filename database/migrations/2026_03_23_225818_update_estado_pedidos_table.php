<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE pedidos MODIFY COLUMN estado ENUM('pendiente','confirmado','preparando','en_camino','cerca','entregado','cancelado') DEFAULT 'pendiente'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pedidos MODIFY COLUMN estado ENUM('pendiente','confirmado','en_camino','entregado','cancelado') DEFAULT 'pendiente'");
    }
};