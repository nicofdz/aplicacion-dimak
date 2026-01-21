<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'occupied' back to the enum list
        DB::statement("ALTER TABLE vehicles MODIFY COLUMN status ENUM('available', 'maintenance', 'out_of_service', 'occupied') NOT NULL DEFAULT 'available'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to the previous state (without occupied), assuming no data needs to be saved since this is a fix
        // However, if we have 'occupied' data, this might fail or truncate. 
        // For safety in down(), we might just leave it or revert carefully.
        // Let's revert to the version without 'occupied' to be strict.

        // Convert any 'occupied' back to 'available' to avoid truncation error on rollback
        DB::table('vehicles')->where('status', 'occupied')->update(['status' => 'available']);

        DB::statement("ALTER TABLE vehicles MODIFY COLUMN status ENUM('available', 'maintenance', 'out_of_service') NOT NULL DEFAULT 'available'");
    }
};
