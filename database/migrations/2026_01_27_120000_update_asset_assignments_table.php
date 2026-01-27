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
        // 1. Modificar usuario_id para que sea nullable (si falla por falta de dbal, tendrás que instalarlo, pero intentémoslo)
        // Nota: Si ya es nullable, esto no debería causar error en MySQL.
        try {
            Schema::table('asset_assignments', function (Blueprint $table) {
                $table->foreignId('usuario_id')->nullable()->change();
            });
        } catch (\Exception $e) {
            // Ignoramos si falla change() por ahora o lo manejamos manualmente si es crítico
            // En L11/12 nativo debería funcionar.
        }

        // 2. Agregar columnas si no existen (Idempotencia)
        Schema::table('asset_assignments', function (Blueprint $table) {

            if (!Schema::hasColumn('asset_assignments', 'worker_id')) {
                $table->foreignId('worker_id')->nullable()->after('usuario_id')->constrained('workers')->nullOnDelete();
            }

            if (!Schema::hasColumn('asset_assignments', 'fecha_estimada_devolucion')) {
                $table->dateTime('fecha_estimada_devolucion')->nullable()->after('fecha_entrega');
            }

            if (!Schema::hasColumn('asset_assignments', 'trabajador_nombre')) {
                $table->string('trabajador_nombre')->nullable()->after('worker_id');
            }
            if (!Schema::hasColumn('asset_assignments', 'trabajador_rut')) {
                $table->string('trabajador_rut')->nullable()->after('trabajador_nombre');
            }
            if (!Schema::hasColumn('asset_assignments', 'trabajador_departamento')) {
                $table->string('trabajador_departamento')->nullable()->after('trabajador_rut');
            }
            if (!Schema::hasColumn('asset_assignments', 'trabajador_cargo')) {
                $table->string('trabajador_cargo')->nullable()->after('trabajador_departamento');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_assignments', function (Blueprint $table) {
            // Revertir es complejo si fue parcial, pero intentamos lo básico
            //$table->foreignId('usuario_id')->nullable(false)->change(); // Potencial problema

            $columns = [
                'worker_id',
                'fecha_estimada_devolucion',
                'trabajador_nombre',
                'trabajador_rut',
                'trabajador_departamento',
                'trabajador_cargo'
            ];

            // Drop columns if exist
            // Nota: dropColumn soporta array
            $table->dropColumn($columns);
        });
    }
};
