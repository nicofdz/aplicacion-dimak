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
        // First, update existing data
        DB::table('vehicles')
            ->where('status', 'workshop')
            ->update(['status' => 'out_of_service']);

        // Then modify the column to new enum
        // Note: We use raw SQL because Doctrine DBAL has issues with ENUMs sometimes, 
        // and identifying the exact enum string is safer.
        DB::statement("ALTER TABLE vehicles MODIFY COLUMN status ENUM('available', 'maintenance', 'out_of_service') NOT NULL DEFAULT 'available'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert data
        DB::table('vehicles')
            ->where('status', 'out_of_service')
            ->update(['status' => 'workshop']);

        // Revert column
        DB::statement("ALTER TABLE vehicles MODIFY COLUMN status ENUM('available', 'workshop', 'maintenance') NOT NULL DEFAULT 'available'");
    }
};
