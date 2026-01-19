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
        Schema::create('vehicle_maintenance_states', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->integer('last_oil_change_km')->nullable();
            $table->integer('next_oil_change_km')->nullable();
            $table->enum('tire_status_front', ['good', 'fair', 'poor'])->default('good');
            $table->enum('tire_status_rear', ['good', 'fair', 'poor'])->default('good');
            $table->date('last_service_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_maintenance_states');
    }
};
