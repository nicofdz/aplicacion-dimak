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
        Schema::table('asset_assignments', function (Blueprint $table) {
            $table->foreignId('usuario_id')->nullable()->change();
            $table->string('trabajador_nombre')->nullable();
            $table->string('trabajador_rut')->nullable();
            $table->string('trabajador_departamento')->nullable();
            $table->string('trabajador_cargo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_assignments', function (Blueprint $table) {
            $table->foreignId('usuario_id')->nullable(false)->change();
            $table->dropColumn([
                'trabajador_nombre',
                'trabajador_rut',
                'trabajador_departamento',
                'trabajador_cargo'
            ]);
        });
    }
};
