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
        Schema::create('asset_maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activo_id')->constrained('assets')->onDelete('cascade');
            $table->enum('tipo', ['preventiva', 'correctiva']);
            $table->text('descripcion');
            $table->date('fecha');
            $table->integer('costo')->nullable()->comment('Costo de la mantención');
            $table->string('evidencia_path')->nullable()->comment('Foto o documento de evidencia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_maintenances');
    }
};
