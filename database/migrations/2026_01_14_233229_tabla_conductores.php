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
        $table->string('nombre'); 
        $table->string('cargo'); 
        $table->string('departamento'); 
        $table->string('fotografia')->nullable(); 
        $table->date('fecha_licencia'); 
        $table->softDeletes();
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
