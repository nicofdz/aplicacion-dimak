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
        Schema::table('vehicles', function (Blueprint $table) {
            \DB::statement("ALTER TABLE vehicles MODIFY COLUMN status ENUM('available', 'workshop', 'maintenance', 'occupied') NOT NULL DEFAULT 'available'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            \DB::statement("ALTER TABLE vehicles MODIFY COLUMN status ENUM('available', 'workshop', 'maintenance') NOT NULL DEFAULT 'available'");
        });
    }
};
