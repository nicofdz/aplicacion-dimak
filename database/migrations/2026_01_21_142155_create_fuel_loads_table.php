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
        Schema::create('fuel_loads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vehicle_request_id')->nullable()->constrained()->onDelete('set null'); // Optional link to a trip
            $table->timestamp('date');
            $table->integer('mileage'); // Odometer at the time of fill-up
            $table->decimal('liters', 8, 2);
            $table->integer('price_per_liter');
            $table->integer('total_cost');
            $table->string('invoice_number')->nullable();
            $table->string('receipt_photo_path')->nullable();
            $table->decimal('efficiency_km_l', 8, 2)->nullable(); // Calculated efficiency
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuel_loads');
    }
};
