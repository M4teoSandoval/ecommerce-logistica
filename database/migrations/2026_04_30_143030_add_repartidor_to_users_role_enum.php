<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite no soporta MODIFY COLUMN ni ENUM, se maneja a nivel aplicación
    }

    public function down(): void
    {
        // No action needed for SQLite
    }
};
