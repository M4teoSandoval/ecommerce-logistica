<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("
                DO \$\$
                DECLARE
                    rec RECORD;
                BEGIN
                    FOR rec IN (
                        SELECT conname FROM pg_constraint c
                        JOIN pg_class t ON c.conrelid = t.oid
                        JOIN pg_attribute a ON a.attrelid = c.conrelid AND a.attnum = ANY(c.conkey)
                        WHERE t.relname = 'pedidos'
                        AND c.contype = 'c'
                        AND a.attname = 'estado'
                    ) LOOP
                        EXECUTE 'ALTER TABLE pedidos DROP CONSTRAINT ' || quote_ident(rec.conname);
                    END LOOP;
                END \$\$;
            ");
            DB::statement("
                ALTER TABLE pedidos ADD CONSTRAINT pedidos_estado_check
                CHECK (estado::text = ANY (ARRAY['pendiente'::text, 'confirmado'::text, 'preparando'::text, 'en_camino'::text, 'entregado'::text, 'cancelado'::text]))
            ");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("
                DO \$\$
                DECLARE
                    rec RECORD;
                BEGIN
                    FOR rec IN (
                        SELECT conname FROM pg_constraint c
                        JOIN pg_class t ON c.conrelid = t.oid
                        JOIN pg_attribute a ON a.attrelid = c.conrelid AND a.attnum = ANY(c.conkey)
                        WHERE t.relname = 'pedidos'
                        AND c.contype = 'c'
                        AND a.attname = 'estado'
                    ) LOOP
                        EXECUTE 'ALTER TABLE pedidos DROP CONSTRAINT ' || quote_ident(rec.conname);
                    END LOOP;
                END \$\$;
            ");
            DB::statement("
                ALTER TABLE pedidos ADD CONSTRAINT pedidos_estado_check
                CHECK (estado::text = ANY (ARRAY['pendiente'::text, 'confirmado'::text, 'en_camino'::text, 'entregado'::text, 'cancelado'::text]))
            ");
        }
    }
};
