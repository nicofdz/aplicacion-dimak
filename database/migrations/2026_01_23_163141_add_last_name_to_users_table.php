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
        Schema::table('users', function (Blueprint $table) {
            $table->string('last_name')->nullable()->after('name');
        });

        // Migrar datos existentes
        $users = \App\Models\User::all();
        foreach ($users as $user) {
            $parts = explode(' ', $user->name);
            $count = count($parts);

            $name = '';
            $lastName = '';

            if ($count >= 4) {
                // 2 Nombres + 2 Apellidos (o más)
                $name = $parts[0] . ' ' . $parts[1];
                $lastName = implode(' ', array_slice($parts, 2));
            } elseif ($count == 3) {
                // Asumimos 1 Nombre + 2 Apellidos (Común)
                $name = $parts[0];
                $lastName = $parts[1] . ' ' . $parts[2];
            } elseif ($count == 2) {
                $name = $parts[0];
                $lastName = $parts[1];
            } else {
                $name = $user->name;
                $lastName = '';
            }

            // Guardamos directamente en DB para evitar validaciones del modelo aún no actualizado
            DB::table('users')->where('id', $user->id)->update([
                'name' => $name,
                'last_name' => $lastName
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Restaurar concatenación aproximada
            // Nota: Esto concatena todo de vuelta en 'name', pero se pierde la distinción original exacta si se edita después.
            // Para un down simple, solo borramos la columna.
            $table->dropColumn('last_name');
        });

        // Opcional: Podríamos intentar restaurar los nombres concatenando antes de borrar la columna, 
        // pero Laravel Rollback primero ejecuta down y dropColumn borraría la data de last_name.
        // Lo correcto sería actualizar 'name' = trim(name . ' ' . last_name) ANTES de dropColumn.

        $users = \App\Models\User::all();
        foreach ($users as $user) {
            if (!empty($user->last_name)) {
                DB::table('users')->where('id', $user->id)->update([
                    'name' => trim($user->name . ' ' . $user->last_name)
                ]);
            }
        }
    }
};
