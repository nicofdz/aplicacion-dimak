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
        Schema::table('asset_maintenances', function (Blueprint $table) {
            $table->text('detalles_solucion')->nullable()->after('descripcion');
            $table->date('fecha_termino')->nullable()->after('fecha');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_maintenances', function (Blueprint $table) {
            $table->dropColumn(['detalles_solucion', 'fecha_termino']);
        });
    }
};
