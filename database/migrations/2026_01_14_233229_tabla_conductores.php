<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('conductores', function (Blueprint $table) {
        $table->id();
        $table->string('nombre'); // Nombre [cite: 24]
        $table->string('cargo'); // Cargo [cite: 24]
        $table->string('departamento'); // Departamento [cite: 24]
        $table->string('fotografia')->nullable(); // Fotografía [cite: 24]
        $table->date('fecha_licencia'); // Validación de Licencia [cite: 25]
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
