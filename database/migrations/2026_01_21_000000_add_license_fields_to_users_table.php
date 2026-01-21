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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'license_expires_at')) {
                $table->date('license_expires_at')->nullable()->after('profile_photo_path');
            }
            if (!Schema::hasColumn('users', 'license_photo_path')) {
                $table->string('license_photo_path', 2048)->nullable()->after('license_expires_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['license_expires_at', 'license_photo_path']);
        });
    }
};
