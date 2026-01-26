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
        // 1. Categorías
        Schema::create('asset_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Activos
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('internal_code')->unique(); // Código interno
            $table->string('barcode')->nullable();     // Código de barras (puede ser el mismo)
            $table->string('name');
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->foreignId('asset_category_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('available'); // available, assigned, maintenance, written_off
            $table->string('location')->nullable(); // Texto libre: Bodega, Oficina 2, Obra X
            $table->date('acquisition_date')->nullable();
            $table->integer('cost')->nullable(); // Valor referencial
            $table->string('image_path')->nullable();
            $table->text('observations')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. Asignaciones (Historial)
        Schema::create('asset_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Responsable
            $table->string('assignment_details')->nullable(); // Para especificar Obra/Proyecto si aplica
            $table->dateTime('assigned_at');
            $table->dateTime('due_date')->nullable(); // Fecha estimada devolución
            $table->dateTime('returned_at')->nullable();
            $table->string('return_condition')->nullable(); // good, fair, poor, damaged
            $table->text('return_comments')->nullable();
            $table->foreignId('assigned_by')->nullable()->constrained('users'); // Quien entregó
            $table->foreignId('received_by')->nullable()->constrained('users'); // Quien recibió
            $table->timestamps();
            $table->softDeletes();
        });

        // 4. Mantenimientos
        Schema::create('asset_maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->onDelete('cascade');
            $table->string('type'); // preventive, corrective
            $table->date('date');
            $table->integer('cost')->nullable();
            $table->text('description')->nullable();
            $table->string('performed_by')->nullable(); // Proveedor o interno
            $table->date('next_maintenance_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_maintenances');
        Schema::dropIfExists('asset_assignments');
        Schema::dropIfExists('assets');
        Schema::dropIfExists('asset_categories');
    }
};
