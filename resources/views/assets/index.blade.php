<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Gestión de Activos') }}
            </h2>
            <div class="flex flex-wrap gap-2 items-center">
                <a href="{{ route('assets.trash') }}"
                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 h-9">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                    {{ __('Papelera') }}
                </a>
                <button x-data="" @click="$dispatch('open-modal', 'create-asset-modal')"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 h-9">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ __('Nuevo') }}
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ 
            deleteAction: '', 
            editingAsset: {}, 
            assignmentAsset: {},
            assignAction: '',
            editAction: '',
            cancelAssignmentAction: '',
            updateAssignmentAction: '',
            searchQuery: '{{ request('search', '') }}'
        }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Búsqueda -->
            <div class="mb-6 flex flex-col sm:flex-row gap-4 items-center">
                <div class="relative w-full sm:max-w-md">
                    <input type="text" x-model="searchQuery" placeholder="Buscar por código, nombre, marca o modelo..."
                        class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-lg pl-10 pr-4 py-2 focus:ring-2 focus:ring-indigo-500 transition-shadow">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="overflow-x-auto">
                        <table class="min-w-full leading-normal">
                            <thead class="bg-gray-800 text-gray-300">
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                        Foto
                                    </th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                        Código
                                    </th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                        Nombre
                                    </th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                        Categoría
                                    </th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                        Ubicación
                                    </th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700 bg-gray-900 text-gray-300">
                                @forelse($assets as $asset)
                                    <tr class="hover:bg-gray-800 transition duration-150"
                                        data-search="{{ strtolower($asset->codigo_interno . ' ' . $asset->nombre . ' ' . $asset->marca . ' ' . $asset->modelo . ' ' . $asset->codigo_barra) }}"
                                        x-show="!searchQuery || $el.dataset.search.split(' ').some(word => word.startsWith(searchQuery.toLowerCase()))">
                                        <!-- Foto -->
                                        <td class="px-5 py-4 text-sm">
                                            @if($asset->foto_path)
                                                <div class="h-10 w-10 flex-shrink-0">
                                                    <img class="h-10 w-10 rounded-full object-cover border border-gray-600"
                                                        src="{{ Storage::url($asset->foto_path) }}" alt="{{ $asset->nombre }}">
                                                </div>
                                            @else
                                                <div
                                                    class="h-10 w-10 rounded-full bg-gray-700 flex items-center justify-center text-xs font-bold text-gray-400 border border-gray-600">
                                                    N/A
                                                </div>
                                            @endif
                                        </td>

                                        <!-- Código -->
                                        <td class="px-5 py-4 text-sm font-bold">
                                            {{ $asset->codigo_interno }}
                                            <div class="text-[10px] text-gray-500">{{ $asset->codigo_barra }}</div>
                                        </td>

                                        <!-- Nombre -->
                                        <td class="px-5 py-4 text-sm">
                                            {{ $asset->nombre }}
                                            @if($asset->marca || $asset->modelo)
                                                <div class="text-xs text-gray-500">
                                                    {{ $asset->marca }} {{ $asset->modelo }}
                                                </div>
                                            @endif
                                        </td>

                                        <!-- Categoría -->
                                        <td class="px-5 py-4 text-sm">
                                            {{ $asset->category->nombre ?? 'Sin categoría' }}
                                        </td>

                                        <!-- Estado -->
                                        <td class="px-5 py-4 text-sm">
                                            @php
                                                $statusClasses = [
                                                    'available' => 'text-green-400 bg-green-900/30 border border-green-900',
                                                    'assigned' => 'text-blue-400 bg-blue-900/30 border border-blue-900',
                                                    'maintenance' => 'text-yellow-400 bg-yellow-900/30 border border-yellow-900',
                                                    'written_off' => 'text-red-400 bg-red-900/30 border border-red-900',
                                                ];
                                                $statusLabel = [
                                                    'available' => 'DISPONIBLE',
                                                    'assigned' => 'ASIGNADO',
                                                    'maintenance' => 'MANTENIMIENTO',
                                                    'written_off' => 'DADO DE BAJA',
                                                ];
                                            @endphp
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-md {{ $statusClasses[$asset->estado] ?? 'text-gray-400 bg-gray-800' }}">
                                                {{ $statusLabel[$asset->estado] ?? strtoupper($asset->estado) }}
                                            </span>
                                            @if($asset->estado === 'assigned' && $asset->activeAssignment)
                                                <div class="text-[10px] text-blue-300 mt-1">
                                                    @if($asset->activeAssignment->user)
                                                        {{ $asset->activeAssignment->user->name }}
                                                    @elseif($asset->activeAssignment->worker)
                                                        {{ $asset->activeAssignment->worker->nombre }}
                                                    @else
                                                        {{ $asset->activeAssignment->trabajador_nombre ?? 'Desconocido' }}
                                                    @endif
                                                </div>
                                            @endif
                                        </td>

                                        <!-- Ubicación -->
                                        <td class="px-5 py-4 text-sm">
                                            {{ $asset->ubicacion ?? 'Sin ubicación' }}
                                        </td>

                                        <!-- Acciones -->
                                        <td class="px-5 py-4 text-sm font-medium">
                                            @php

                                                $activeAssignment = $asset->activeAssignment;
                                                $assignmentData = null;

                                                if ($activeAssignment) {
                                                    $user = $activeAssignment->user;
                                                    $worker = $activeAssignment->worker;

                                                    // Determinar días restantes
                                                    $daysRemaining = null;
                                                    if ($activeAssignment->fecha_estimada_devolucion) {
                                                        $now = now();
                                                        $deadline = \Carbon\Carbon::parse($activeAssignment->fecha_estimada_devolucion);
                                                        $diff = $now->diffInDays($deadline, false);
                                                        $daysRemaining = (int) ceil($diff);
                                                    }

                                                    $assignmentData = [
                                                        'assigned_to' => $user ? $user->name : ($worker ? $worker->nombre : $activeAssignment->trabajador_nombre),
                                                        'rut' => $user ? $user->rut : ($worker ? $worker->rut : $activeAssignment->trabajador_rut),
                                                        'email' => $user ? $user->email : null,
                                                        'phone' => $user ? $user->phone : null,
                                                        'photo_url' => $user && $user->profile_photo_path ? '/storage/' . $user->profile_photo_path : null, // Asumiendo path relativo en DB
                                                        'cargo' => $user ? $user->cargo : ($worker ? $worker->cargo : $activeAssignment->trabajador_cargo),
                                                        'department' => $user ? $user->departamento : ($worker ? $worker->departamento : $activeAssignment->trabajador_departamento),
                                                        'start_date' => $activeAssignment->fecha_entrega ? $activeAssignment->fecha_entrega->format('Y-m-d') : null,
                                                        'end_date' => $activeAssignment->fecha_estimada_devolucion ? $activeAssignment->fecha_estimada_devolucion->format('Y-m-d') : null,
                                                        'days_remaining' => $daysRemaining,
                                                        'observations' => $activeAssignment->observaciones
                                                    ];
                                                }

                                                $jsAsset = [
                                                    'id' => $asset->id,
                                                    'foto_url' => $asset->foto_path ? Storage::url($asset->foto_path) : null,
                                                    'codigo_interno' => $asset->codigo_interno,
                                                    'codigo_barra' => $asset->codigo_barra,
                                                    'nombre' => $asset->nombre,
                                                    'categoria_id' => $asset->categoria_id,
                                                    'marca' => $asset->marca,
                                                    'modelo' => $asset->modelo,
                                                    'numero_serie' => $asset->numero_serie,
                                                    'estado' => $asset->estado,
                                                    'ubicacion' => $asset->ubicacion,
                                                    'fecha_adquisicion' => $asset->fecha_adquisicion?->format('Y-m-d'),
                                                    'valor_referencial' => $asset->valor_referencial ? number_format($asset->valor_referencial, 0, '', '.') : '',
                                                    'observaciones' => $asset->observaciones,
                                                    'active_assignment' => $assignmentData
                                                ];
                                                $jsonAsset = json_encode($jsAsset);
                                            @endphp
                                            <div class="flex items-center space-x-4">
                                                <!-- Asignar (Solo si está disponible) -->
                                                @if($asset->estado === 'available')
                                                    <button
                                                        @click="
                                                                                                                                                                                                                                                assignmentAsset = {{ $jsonAsset }};
                                                                                                                                                                                                                                                assignAction = '{{ route('assets.assign', $asset->id) }}';
                                                                                                                                                                                                                                                $dispatch('open-modal', 'assign-asset-modal');
                                                                                                                                                                                                                                            "
                                                        class="text-blue-500 hover:text-blue-400 transition duration-150"
                                                        title="Asignar Activo">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                                        </svg>
                                                    </button>
                                                @endif


                                                <!-- Ver Historial (Para todos) -->
                                                <a href="{{ route('assets.history', $asset->id) }}"
                                                    class="text-gray-500 hover:text-gray-400 transition duration-150"
                                                    title="Ver Historial Completo">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </a>

                                                <!-- Ver Detalles Asignación (Solo si está asignado) -->
                                                @if($asset->estado === 'assigned')
                                                    <button
                                                        @click="
                                                                                                                                                                                        assignmentAsset = {{ $jsonAsset }};
                                                                                                                                                                                        $dispatch('open-modal', 'view-assignment-modal');
                                                                                                                                                                                    "
                                                        class="text-indigo-400 hover:text-indigo-300 transition duration-150"
                                                        title="Ver Detalles de Asignación">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                @endif

                                                <!-- Código de Barras -->
                                                <a href="{{ route('assets.barcode', $asset->id) }}"
                                                    class="text-purple-400 hover:text-purple-300 transition duration-150"
                                                    title="Descargar Etiqueta PDF">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 4v1m6 11h2m-6 0h-2v4h-4v-4H8m13-4V7a1 1 0 00-1-1H4a1 1 0 00-1 1v3M4 12h16m-7 6h6M5 18v2m14-2v2">
                                                        </path>
                                                        <!-- Icono alternativo más claro de código de barras -->
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 10h18M3 14h18m-9-4v8m-7-8v8m14-8v8M3 6l18-4M3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-18 4z"
                                                            style="display:none;" />
                                                        <!-- Usando un icono simple de impresora o codigo -->
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 5a1 1 0 011-1h16a1 1 0 011 1v3h-2V6H5v2H3V5zm15 10v5h-5v-5h5zm2-3H4v6h2v-4h12v4h2v-6z">
                                                        </path>
                                                    </svg>
                                                </a>

                                                <!-- Ver Detalle -->
                                                <button
                                                    @click="
                                                                                                                                                        editingAsset = {{ $jsonAsset }};
                                                                                                                                                        $dispatch('open-modal', 'view-asset-modal');
                                                                                                                                                    "
                                                    class="text-green-500 hover:text-green-400 transition duration-150"
                                                    title="Ver Detalle">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </button>

                                                <!-- Editar -->
                                                <button
                                                    @click="
                                                                                                                                                        editingAsset = {{ $jsonAsset }};
                                                                                                                                                        editAction = '{{ route('assets.update', $asset->id) }}';
                                                                                                                                                        $dispatch('open-modal', 'edit-asset-modal');
                                                                                                                                                    "
                                                    class="text-blue-400 hover:text-blue-300 transition duration-150"
                                                    title="Editar">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </button>

                                                <!-- Eliminar -->
                                                <button
                                                    @click="$dispatch('open-modal', 'confirm-delete-modal'); deleteAction = '{{ route('assets.destroy', $asset) }}'"
                                                    class="text-red-400 hover:text-red-300 transition duration-150"
                                                    title="Eliminar">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-5 py-5 text-sm text-center text-gray-500">
                                            No hay activos registrados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Crear Activo -->
        <x-modal name="create-asset-modal" :show="$errors->any()" focusable>
            <form method="POST" action="{{ route('assets.store') }}" class="p-6 bg-gray-800 text-gray-100"
                enctype="multipart/form-data">
                @csrf

                <h2 class="text-lg font-medium text-gray-100 mb-4">
                    {{ __('Nuevo Activo') }}
                </h2>

                <!-- Foto -->
                <div class="mb-4">
                    <x-input-label for="foto" :value="__('Foto del Activo')" class="text-gray-300" />
                    <input id="foto" type="file" name="foto" class="mt-1 block w-full text-sm text-gray-400
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-600 file:text-white
                        hover:file:bg-blue-700
                        cursor-pointer focus:outline-none" accept="image/*" />
                    <x-input-error :messages="$errors->get('foto')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Nombre -->
                    <div class="md:col-span-2">
                        <x-input-label for="nombre" :value="__('Nombre del Activo')" class="text-gray-300" />
                        <x-text-input id="nombre"
                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                            type="text" name="nombre" :value="old('nombre')" required autofocus
                            placeholder="Ej: Laptop Dell Latitude 5420" />
                        <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                    </div>

                    <!-- Categoría -->
                    <div>
                        <x-input-label for="categoria_id" :value="__('Categoría')" class="text-gray-300" />
                        <select id="categoria_id" name="categoria_id"
                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                            required>
                            <option value="">Seleccione una categoría</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->nombre }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('categoria_id')" class="mt-2" />
                    </div>

                    <!-- Estado -->
                    <div>
                        <x-input-label for="estado" :value="__('Estado')" class="text-gray-300" />
                        <select id="estado" name="estado"
                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                            <option value="available">Disponible</option>
                            <option value="assigned">Asignado</option>
                            <option value="maintenance">En Mantenimiento</option>
                            <option value="written_off">Dado de Baja</option>
                        </select>
                        <x-input-error :messages="$errors->get('estado')" class="mt-2" />
                    </div>

                    <!-- Marca -->
                    <div>
                        <x-input-label for="marca" :value="__('Marca')" class="text-gray-300" />
                        <x-text-input id="marca"
                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                            type="text" name="marca" :value="old('marca')" placeholder="Ej: Dell" />
                        <x-input-error :messages="$errors->get('marca')" class="mt-2" />
                    </div>

                    <!-- Modelo -->
                    <div>
                        <x-input-label for="modelo" :value="__('Modelo')" class="text-gray-300" />
                        <x-text-input id="modelo"
                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                            type="text" name="modelo" :value="old('modelo')" placeholder="Ej: Latitude 5420" />
                        <x-input-error :messages="$errors->get('modelo')" class="mt-2" />
                    </div>

                    <!-- Número de Serie -->
                    <div class="md:col-span-2">
                        <x-input-label for="numero_serie" :value="__('Número de Serie (Opcional)')"
                            class="text-gray-300" />
                        <x-text-input id="numero_serie"
                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                            type="text" name="numero_serie" :value="old('numero_serie')"
                            placeholder="Número de serie del fabricante" />
                        <x-input-error :messages="$errors->get('numero_serie')" class="mt-2" />
                    </div>

                    <!-- Ubicación -->
                    <div>
                        <x-input-label for="ubicacion" :value="__('Ubicación')" class="text-gray-300" />
                        <x-text-input id="ubicacion"
                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                            type="text" name="ubicacion" :value="old('ubicacion')" placeholder="Ej: Oficina Central" />
                        <x-input-error :messages="$errors->get('ubicacion')" class="mt-2" />
                    </div>

                    <!-- Fecha de Adquisición -->
                    <div>
                        <x-input-label for="fecha_adquisicion" :value="__('Fecha de Adquisición')"
                            class="text-gray-300" />
                        <x-text-input id="fecha_adquisicion"
                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                            type="date" name="fecha_adquisicion" :value="old('fecha_adquisicion')" />
                        <x-input-error :messages="$errors->get('fecha_adquisicion')" class="mt-2" />
                    </div>

                    <!-- Valor Referencial -->
                    <div class="md:col-span-2">
                        <x-input-label for="valor_referencial" :value="__('Valor Referencial (CLP)')"
                            class="text-gray-300" />
                        <x-text-input id="valor_referencial"
                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                            type="text" name="valor_referencial" :value="old('valor_referencial')" placeholder="0"
                            x-on:input="$el.value = $el.value.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')" />
                        <x-input-error :messages="$errors->get('valor_referencial')" class="mt-2" />
                    </div>

                    <!-- Observaciones -->
                    <div class="md:col-span-2">
                        <x-input-label for="observaciones" :value="__('Observaciones')" class="text-gray-300" />
                        <textarea id="observaciones" name="observaciones" rows="3"
                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                            placeholder="Observaciones adicionales...">{{ old('observaciones') }}</textarea>
                        <x-input-error :messages="$errors->get('observaciones')" class="mt-2" />
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <x-secondary-button x-on:click="$dispatch('close')"
                        class="bg-gray-700 text-gray-300 hover:bg-gray-600 border-gray-600">
                        {{ __('Cancelar') }}
                    </x-secondary-button>

                    <x-primary-button class="bg-blue-600 hover:bg-blue-700 border-transparent">
                        {{ __('Guardar Activo') }}
                    </x-primary-button>
                </div>
            </form>
        </x-modal>

        <!-- Modal Editar Activo -->
        <x-modal name="edit-asset-modal" :show="false" focusable>
            <form method="POST" :action="editAction" enctype="multipart/form-data"
                class="p-6 bg-gray-800 text-gray-100">
                @csrf
                @method('PUT')

                <h2 class="text-lg font-medium text-gray-100 mb-4">
                    {{ __('Editar Activo') }}
                </h2>

                <!-- Foto -->
                <div class="mb-4">
                    <x-input-label for="edit_foto" :value="__('Actualizar Foto (Opcional)')" class="text-gray-300" />
                    <input id="edit_foto" type="file" name="foto" accept="image/*" class="mt-1 block w-full text-sm text-gray-400
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-600 file:text-white
                        hover:file:bg-blue-700
                        cursor-pointer focus:outline-none" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Código Interno (Solo lectura) -->
                    <div>
                        <x-input-label for="edit_codigo_interno" :value="__('Código Interno')" class="text-gray-300" />
                        <x-text-input id="edit_codigo_interno"
                            class="block mt-1 w-full bg-gray-700 border-gray-600 text-gray-400 cursor-not-allowed"
                            type="text" x-model="editingAsset.codigo_interno" disabled />
                    </div>

                    <!-- Código de Barras (Solo lectura) -->
                    <div>
                        <x-input-label for="edit_codigo_barra" :value="__('Código de Barras')" class="text-gray-300" />
                        <x-text-input id="edit_codigo_barra"
                            class="block mt-1 w-full bg-gray-700 border-gray-600 text-gray-400 cursor-not-allowed"
                            type="text" x-model="editingAsset.codigo_barra" disabled />
                    </div>

                    <!-- Nombre -->
                    <div class="md:col-span-2">
                        <x-input-label for="edit_nombre" :value="__('Nombre del Activo')" class="text-gray-300" />
                        <x-text-input id="edit_nombre"
                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                            type="text" name="nombre" x-model="editingAsset.nombre" required />
                    </div>

                    <!-- Categoría -->
                    <div>
                        <x-input-label for="edit_categoria_id" :value="__('Categoría')" class="text-gray-300" />
                        <select id="edit_categoria_id" name="categoria_id" x-model="editingAsset.categoria_id"
                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                            required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Estado -->
                    <div>
                        <x-input-label for="edit_estado" :value="__('Estado')" class="text-gray-300" />
                        <select id="edit_estado" name="estado" x-model="editingAsset.estado"
                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                            <option value="available">Disponible</option>
                            <option value="assigned">Asignado</option>
                            <option value="maintenance">En Mantenimiento</option>
                            <option value="written_off">Dado de Baja</option>
                        </select>
                    </div>

                    <!-- Marca -->
                    <div>
                        <x-input-label for="edit_marca" :value="__('Marca')" class="text-gray-300" />
                        <x-text-input id="edit_marca"
                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                            type="text" name="marca" x-model="editingAsset.marca" />
                    </div>

                    <!-- Modelo -->
                    <div>
                        <x-input-label for="edit_modelo" :value="__('Modelo')" class="text-gray-300" />
                        <x-text-input id="edit_modelo"
                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                            type="text" name="modelo" x-model="editingAsset.modelo" />
                    </div>

                    <!-- Número de Serie -->
                    <div class="md:col-span-2">
                        <x-input-label for="edit_numero_serie" :value="__('Número de Serie')" class="text-gray-300" />
                        <x-text-input id="edit_numero_serie"
                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                            type="text" name="numero_serie" x-model="editingAsset.numero_serie" />
                    </div>

                    <!-- Ubicación -->
                    <div>
                        <x-input-label for="edit_ubicacion" :value="__('Ubicación')" class="text-gray-300" />
                        <x-text-input id="edit_ubicacion"
                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                            type="text" name="ubicacion" x-model="editingAsset.ubicacion" />
                    </div>

                    <!-- Fecha de Adquisición -->
                    <div>
                        <x-input-label for="edit_fecha_adquisicion" :value="__('Fecha de Adquisición')"
                            class="text-gray-300" />
                        <x-text-input id="edit_fecha_adquisicion"
                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                            type="date" name="fecha_adquisicion" x-model="editingAsset.fecha_adquisicion" />
                    </div>

                    <!-- Valor Referencial -->
                    <div class="md:col-span-2">
                        <x-input-label for="edit_valor_referencial" :value="__('Valor Referencial (CLP)')"
                            class="text-gray-300" />
                        <x-text-input id="edit_valor_referencial"
                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                            type="text" name="valor_referencial" x-model="editingAsset.valor_referencial"
                            x-on:input="$el.value = $el.value.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')" />
                    </div>

                    <!-- Observaciones -->
                    <div class="md:col-span-2">
                        <x-input-label for="edit_observaciones" :value="__('Observaciones')" class="text-gray-300" />
                        <textarea id="edit_observaciones" name="observaciones" rows="3"
                            x-model="editingAsset.observaciones"
                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <x-secondary-button @click="$dispatch('close')"
                        class="bg-gray-700 text-gray-300 hover:bg-gray-600 border-gray-600">
                        {{ __('Cancelar') }}
                    </x-secondary-button>

                    <x-primary-button class="bg-blue-600 hover:bg-blue-700 border-transparent">
                        {{ __('Actualizar Activo') }}
                    </x-primary-button>
                </div>
            </form>
        </x-modal>

        <!-- Modal Confirmación Eliminar -->
        <x-modal name="confirm-delete-modal" :show="false" focusable>
            <div class="p-6 bg-gray-800 text-gray-100">
                <h2 class="text-lg font-medium text-gray-100">
                    {{ __('¿Estás seguro?') }}
                </h2>

                <p class="mt-1 text-sm text-gray-400">
                    {{ __('El activo se moverá a la papelera. Podrás restaurarlo después si lo necesitas.') }}
                </p>

                <div class="mt-6 flex justify-end space-x-3">
                    <x-secondary-button @click="$dispatch('close')"
                        class="bg-gray-700 text-gray-300 hover:bg-gray-600 border-gray-600">
                        {{ __('Cancelar') }}
                    </x-secondary-button>

                    <form method="POST" :action="deleteAction">
                        @csrf
                        @method('DELETE')
                        <x-danger-button class="ml-3">
                            {{ __('Sí, enviar a la papelera') }}
                        </x-danger-button>
                    </form>
                </div>
            </div>
        </x-modal>
        <!-- Modal Ver Detalle Activo -->
        <x-modal name="view-asset-modal" :show="false" focusable>
            <div class="p-6 bg-gray-800 text-gray-100">
                <div class="flex justify-between items-start mb-6">
                    <h2 class="text-2xl font-bold text-gray-100">
                        {{ __('Detalle del Activo') }}
                    </h2>
                    <button @click="$dispatch('close')" class="text-gray-400 hover:text-gray-200">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Columna Izquierda: Foto e Información Principal -->
                    <div class="space-y-6">
                        <!-- Foto -->
                        <div
                            class="flex justify-center bg-gray-900 p-4 rounded-lg border border-gray-700 min-h-[200px] items-center">
                            <template x-if="editingAsset.foto_url">
                                <img :src="editingAsset.foto_url" class="max-h-64 rounded-lg object-contain shadow-md">
                            </template>
                            <template x-if="!editingAsset.foto_url">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <svg class="w-16 h-16 mb-2 opacity-50" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-sm">Sin fotografía</span>
                                </div>
                            </template>
                        </div>

                        <div>
                            <label class="text-xs text-gray-500 uppercase font-bold">Código Interno</label>
                            <p class="text-lg font-mono text-blue-400" x-text="editingAsset.codigo_interno"></p>
                        </div>

                        <div>
                            <label class="text-xs text-gray-500 uppercase font-bold">Código de Barras</label>
                            <p class="text-sm font-mono text-gray-300" x-text="editingAsset.codigo_barra"></p>
                        </div>

                        <div>
                            <label class="text-xs text-gray-500 uppercase font-bold">Estado</label>
                            <p class="mt-1">
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-md border"
                                    :class="{
                                        'text-green-400 bg-green-900/30 border-green-900': editingAsset.estado === 'available',
                                        'text-blue-400 bg-blue-900/30 border-blue-900': editingAsset.estado === 'assigned',
                                        'text-yellow-400 bg-yellow-900/30 border-yellow-900': editingAsset.estado === 'maintenance',
                                        'text-red-400 bg-red-900/30 border-red-900': editingAsset.estado === 'written_off'
                                    }"
                                    x-text="editingAsset.estado === 'available' ? 'DISPONIBLE' : 
                                           (editingAsset.estado === 'assigned' ? 'ASIGNADO' : 
                                           (editingAsset.estado === 'maintenance' ? 'MANTENIMIENTO' : 'DADO DE BAJA'))">
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- Columna Derecha: Detalles -->
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs text-gray-500 uppercase font-bold">Nombre</label>
                            <p class="text-gray-200 text-lg font-semibold" x-text="editingAsset.nombre"></p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs text-gray-500 uppercase font-bold">Marca</label>
                                <p class="text-gray-300" x-text="editingAsset.marca || 'N/A'"></p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 uppercase font-bold">Modelo</label>
                                <p class="text-gray-300" x-text="editingAsset.modelo || 'N/A'"></p>
                            </div>
                        </div>

                        <div>
                            <label class="text-xs text-gray-500 uppercase font-bold">Número de Serie</label>
                            <p class="text-gray-300 font-mono" x-text="editingAsset.numero_serie || 'N/A'"></p>
                        </div>

                        <div>
                            <label class="text-xs text-gray-500 uppercase font-bold">Ubicación</label>
                            <p class="text-gray-300" x-text="editingAsset.ubicacion || 'N/A'"></p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs text-gray-500 uppercase font-bold">Fecha Adquisición</label>
                                <p class="text-gray-300" x-text="editingAsset.fecha_adquisicion || 'N/A'"></p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 uppercase font-bold">Valor Referencial</label>
                                <p class="text-gray-300"
                                    x-text="editingAsset.valor_referencial ? '$ ' + editingAsset.valor_referencial : 'N/A'">
                                </p>
                            </div>
                        </div>

                        <div>
                            <label class="text-xs text-gray-500 uppercase font-bold">Observaciones</label>
                            <p class="text-gray-400 text-sm bg-gray-900 p-3 rounded border border-gray-700 mt-1 min-h-[80px]"
                                x-text="editingAsset.observaciones || 'Sin observaciones'"></p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <x-secondary-button @click="$dispatch('close')"
                        class="bg-gray-700 text-gray-300 hover:bg-gray-600 border-gray-600">
                        {{ __('Cerrar') }}
                    </x-secondary-button>
                </div>
            </div>
        </x-modal>
        <!-- Modal Asignar Activo -->
        <x-modal name="assign-asset-modal" :show="$errors->has('tipo_asignacion')" focusable>
            <form method="POST" :action="assignAction" class="p-6 bg-gray-800 text-gray-100"
                x-data="{ assignmentType: 'user', isNewWorker: false }">
                @csrf

                <h2 class="text-lg font-medium text-gray-100 mb-4">
                    {{ __('Asignar Activo') }}
                </h2>

                <p class="mb-4 text-sm text-gray-400">
                    Estás asignando el activo: <span class="font-bold text-white"
                        x-text="assignmentAsset.nombre"></span>
                    (<span class="font-mono text-blue-400" x-text="assignmentAsset.codigo_interno"></span>)
                </p>

                <!-- Tipo de Asignación -->
                <div class="mb-4">
                    <span class="block text-sm font-medium text-gray-300 mb-2">Asignar a:</span>
                    <div class="flex space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="tipo_asignacion" value="user" x-model="assignmentType"
                                class="form-radio text-blue-600 bg-gray-900 border-gray-700">
                            <span class="ml-2 text-gray-300">Usuario del Sistema</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="tipo_asignacion" value="worker" x-model="assignmentType"
                                class="form-radio text-blue-600 bg-gray-900 border-gray-700">
                            <span class="ml-2 text-gray-300">Trabajador</span>
                        </label>
                    </div>
                </div>

                <!-- Campos Usuario Sistema -->
                <div x-show="assignmentType === 'user'" class="mb-4 space-y-4">
                    <div>
                        <x-input-label for="usuario_id" :value="__('Seleccionar Usuario')" class="text-gray-300" />
                        <select id="usuario_id" name="usuario_id"
                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                            <option value="">Seleccione un usuario...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('usuario_id')" class="mt-2" />
                    </div>
                </div>

                <!-- Campos Trabajador -->
                <div x-show="assignmentType === 'worker'" class="mb-4 space-y-4">

                    <!-- Selector de Trabajador Existente -->
                    <div x-show="!isNewWorker">
                        <x-input-label for="worker_id_select" :value="__('Seleccionar Trabajador')"
                            class="text-gray-300" />
                        <div class="flex items-center gap-2">
                            <select id="worker_id_select" name="worker_id_select"
                                class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                                <option value="">Seleccione un trabajador...</option>
                                @foreach($workers as $worker)
                                    <option value="{{ $worker->id }}">{{ $worker->nombre }} ({{ $worker->rut }})</option>
                                @endforeach
                            </select>
                        </div>
                        <x-input-error :messages="$errors->get('worker_id_select')" class="mt-2" />
                    </div>

                    <!-- Checkbox Nuevo Trabajador -->
                    <div class="flex items-center mt-2">
                        <input id="is_new_worker" type="checkbox" name="is_new_worker" value="1" x-model="isNewWorker"
                            class="w-4 h-4 text-blue-600 bg-gray-900 border-gray-700 rounded focus:ring-blue-500 focus:ring-2">
                        <label for="is_new_worker" class="ml-2 text-sm font-medium text-gray-300">Trabajador Nuevo
                            (Registrar)</label>
                    </div>

                    <!-- Campos Nuevo Trabajador -->
                    <div x-show="isNewWorker" class="space-y-4 border-l-2 border-blue-500 pl-4 mt-2 transition-all">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="trabajador_nombre" :value="__('Nombre Completo')"
                                    class="text-gray-300" />
                                <x-text-input id="trabajador_nombre"
                                    class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100" type="text"
                                    name="trabajador_nombre" placeholder="Juan Pérez" />
                                <x-input-error :messages="$errors->get('trabajador_nombre')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="trabajador_rut" :value="__('RUT')" class="text-gray-300" />
                                <x-text-input id="trabajador_rut"
                                    class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100" type="text"
                                    name="trabajador_rut" placeholder="12.345.678-9" />
                                <x-input-error :messages="$errors->get('trabajador_rut')" class="mt-2" />
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="trabajador_departamento" :value="__('Departamento')"
                                    class="text-gray-300" />
                                <x-text-input id="trabajador_departamento"
                                    class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100" type="text"
                                    name="trabajador_departamento" placeholder="Operaciones" />
                                <x-input-error :messages="$errors->get('trabajador_departamento')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="trabajador_cargo" :value="__('Cargo')" class="text-gray-300" />
                                <x-text-input id="trabajador_cargo"
                                    class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100" type="text"
                                    name="trabajador_cargo" placeholder="Supervisor" />
                                <x-input-error :messages="$errors->get('trabajador_cargo')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fechas y Observaciones -->
                <div class="space-y-4 border-t border-gray-700 pt-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="fecha_entrega" :value="__('Fecha Entrega (Desde)')"
                                class="text-gray-300" />
                            <x-text-input id="fecha_entrega"
                                class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100"
                                type="datetime-local" name="fecha_entrega" value="{{ now()->format('Y-m-d\TH:i') }}"
                                required />
                            <x-input-error :messages="$errors->get('fecha_entrega')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="fecha_estimada_devolucion" :value="__('Fecha Estimada (Hasta) - Opcional')" class="text-gray-300" />
                            <x-text-input id="fecha_estimada_devolucion"
                                class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100"
                                type="datetime-local" name="fecha_estimada_devolucion" />
                            <x-input-error :messages="$errors->get('fecha_estimada_devolucion')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="assign_observaciones" :value="__('Observaciones de Entrega')"
                            class="text-gray-300" />
                        <textarea id="assign_observaciones" name="observaciones" rows="2"
                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                            placeholder="Estado inicial, accesorios, etc..."></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <x-secondary-button @click="$dispatch('close')"
                        class="bg-gray-700 text-gray-300 hover:bg-gray-600 border-gray-600">
                        {{ __('Cancelar') }}
                    </x-secondary-button>

                    <x-primary-button class="bg-blue-600 hover:bg-blue-700 border-transparent">
                        {{ __('Confirmar Asignación') }}
                    </x-primary-button>
                </div>
            </form>
        </x-modal>

        <!-- Modal Ver Detalles de Asignación -->
        <x-modal name="view-assignment-modal" :show="false" focusable>
            <div class="p-6 bg-gray-800 text-gray-100">
                <template x-if="assignmentAsset && assignmentAsset.active_assignment">
                    <div>
                        <div class="flex justify-between items-start mb-6">
                            <h2 class="text-2xl font-bold text-gray-100 flex items-center gap-2">
                                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                    </path>
                                </svg>
                                {{ __('Detalle de Asignación') }}
                            </h2>
                            <button @click="$dispatch('close')"
                                class="text-gray-400 hover:text-gray-200 transition-colors">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Columna Izquierda: Información del Activo -->
                            <div class="space-y-6">
                                <div class="bg-gray-700/50 p-4 rounded-xl border border-gray-600">
                                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        Activo Asignado
                                    </h3>

                                    <div class="flex flex-col items-center mb-4">
                                        <template x-if="assignmentAsset.foto_url">
                                            <img :src="assignmentAsset.foto_url"
                                                class="h-32 w-auto object-contain rounded-lg shadow-lg bg-gray-800 p-2 border border-gray-600">
                                        </template>
                                        <template x-if="!assignmentAsset.foto_url">
                                            <div
                                                class="h-32 w-32 rounded-lg bg-gray-800 flex items-center justify-center border border-gray-600">
                                                <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        </template>
                                    </div>

                                    <div class="space-y-2">
                                        <p class="text-xl font-bold text-center text-white"
                                            x-text="assignmentAsset.nombre"></p>
                                        <p class="text-sm text-center text-gray-400">
                                            <span x-text="assignmentAsset.marca"></span> <span
                                                x-text="assignmentAsset.modelo"></span>
                                        </p>
                                        <div class="flex justify-center gap-2 mt-2">
                                            <span
                                                class="px-2 py-1 bg-gray-800 rounded text-xs text-blue-300 font-mono border border-blue-900"
                                                x-text="assignmentAsset.codigo_interno"></span>
                                            <span
                                                class="px-2 py-1 bg-gray-800 rounded text-xs text-gray-300 font-mono border border-gray-600"
                                                x-text="assignmentAsset.codigo_barra"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-700/30 p-4 rounded-xl border border-gray-700">
                                    <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">
                                        Observaciones de
                                        Asignación</h4>
                                    <p class="text-gray-300 text-sm italic"
                                        x-text="assignmentAsset.active_assignment.observations || 'Sin observaciones registradas.'">
                                    </p>
                                </div>
                            </div>

                            <!-- Columna Derecha: Información del Usuario y Fechas -->
                            <div class="space-y-6">
                                <!-- Tarjeta de Usuario/Trabajador -->
                                <div
                                    class="bg-gray-700/50 p-5 rounded-xl border border-gray-600 relative overflow-hidden">
                                    <div class="absolute top-0 right-0 p-2 opacity-10">
                                        <svg class="w-24 h-24 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                    </div>

                                    <h3
                                        class="text-lg font-semibold text-white mb-4 flex items-center gap-2 relative z-10">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0c0 .884-.356 1.763-1 2.404C9.288 8.163 8.665 8 8 8S6.712 8.163 6 7.404A4.002 4.002 0 0110 6z">
                                            </path>
                                        </svg>
                                        Asignado A
                                    </h3>

                                    <div class="flex items-start gap-4 relative z-10">
                                        <div class="flex-shrink-0">
                                            <template x-if="assignmentAsset.active_assignment.photo_url">
                                                <img :src="assignmentAsset.active_assignment.photo_url"
                                                    class="h-16 w-16 rounded-full object-cover border-2 border-blue-500 shadow-sm">
                                            </template>
                                            <template x-if="!assignmentAsset.active_assignment.photo_url">
                                                <div
                                                    class="h-16 w-16 rounded-full bg-blue-900/50 flex items-center justify-center border-2 border-blue-500/50 text-blue-200 font-bold text-xl">
                                                    <span
                                                        x-text="(assignmentAsset.active_assignment.assigned_to || '?').charAt(0)"></span>
                                                </div>
                                            </template>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-lg font-bold text-white truncate"
                                                x-text="assignmentAsset.active_assignment.assigned_to">
                                            </p>
                                            <p class="text-sm text-blue-300 font-mono"
                                                x-text="assignmentAsset.active_assignment.rut || 'RUT no registrado'">
                                            </p>
                                            <div class="mt-2 space-y-1">
                                                <p class="text-xs text-gray-400 flex items-center gap-1"
                                                    x-show="assignmentAsset.active_assignment.cargo">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                    <span x-text="assignmentAsset.active_assignment.cargo"></span>
                                                </p>
                                                <p class="text-xs text-gray-400 flex items-center gap-1"
                                                    x-show="assignmentAsset.active_assignment.department">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                                        </path>
                                                    </svg>
                                                    <span x-text="assignmentAsset.active_assignment.department"></span>
                                                </p>
                                                <p class="text-xs text-gray-400 flex items-center gap-1"
                                                    x-show="assignmentAsset.active_assignment.phone">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                        </path>
                                                    </svg>
                                                    <span x-text="assignmentAsset.active_assignment.phone"></span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Fechas y Plazos -->
                                <div class="bg-gray-700/50 p-5 rounded-xl border border-gray-600">
                                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        Plazos de Asignación
                                    </h3>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-xs text-gray-400 uppercase font-bold block mb-1">Fecha
                                                Entrega</label>
                                            <div class="flex items-center gap-2 text-white">
                                                <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                                <span x-text="assignmentAsset.active_assignment.start_date"></span>
                                            </div>
                                        </div>
                                        <div>
                                            <label
                                                class="text-xs text-gray-400 uppercase font-bold block mb-1">Devolución
                                                Estimada</label>
                                            <template x-if="assignmentAsset.active_assignment.end_date">
                                                <div class="flex items-center gap-2 text-white">
                                                    <div class="w-2 h-2 rounded-full bg-red-500"></div>
                                                    <span x-text="assignmentAsset.active_assignment.end_date"></span>
                                                </div>
                                            </template>
                                            <template x-if="!assignmentAsset.active_assignment.end_date">
                                                <span class="text-gray-500 italic">Indefinido</span>
                                            </template>
                                        </div>
                                    </div>

                                    <template x-if="assignmentAsset.active_assignment.end_date">
                                        <div class="mt-4 pt-4 border-t border-gray-600">
                                            <div class="flex justify-between items-center bg-gray-800 p-3 rounded-lg border"
                                                :class="{
                                            'border-green-700 bg-green-900/20': assignmentAsset.active_assignment.days_remaining > 5,
                                            'border-yellow-700 bg-yellow-900/20': assignmentAsset.active_assignment.days_remaining <= 5 && assignmentAsset.active_assignment.days_remaining >= 0,
                                            'border-red-700 bg-red-900/20': assignmentAsset.active_assignment.days_remaining < 0
                                        }">
                                                <span class="text-sm font-medium text-gray-300">Tiempo Restante:</span>
                                                <span class="font-bold text-lg" :class="{
                                                'text-green-400': assignmentAsset.active_assignment.days_remaining > 5,
                                                'text-yellow-400': assignmentAsset.active_assignment.days_remaining <= 5 && assignmentAsset.active_assignment.days_remaining >= 0,
                                                'text-red-400': assignmentAsset.active_assignment.days_remaining < 0
                                            }" x-text="assignmentAsset.active_assignment.days_remaining < 0 ? Math.abs(assignmentAsset.active_assignment.days_remaining) + ' días vencido' : assignmentAsset.active_assignment.days_remaining + ' días'">
                                                </span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-between">
                            <div>
                                <x-danger-button
                                    @click="$dispatch('open-modal', 'confirm-cancel-assignment-modal'); cancelAssignmentAction = `{{ url('/assets') }}/${assignmentAsset.id}/cancel-assignment`"
                                    class="bg-red-600 hover:bg-red-500">
                                    {{ __('Terminar Asignación') }}
                                </x-danger-button>
                            </div>
                            <div class="flex gap-3">
                                <a :href="`{{ url('/assets') }}/${assignmentAsset.id}/history`"
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-gray-600 rounded-md font-semibold text-xs text-gray-300 uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    {{ __('Historial') }}
                                </a>
                                <button
                                    @click="$dispatch('open-modal', 'edit-assignment-modal'); updateAssignmentAction = `{{ url('/assets') }}/${assignmentAsset.id}/assignment/update`"
                                    class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-400 focus:bg-yellow-400 active:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    {{ __('Editar') }}
                                </button>
                                <x-primary-button @click="$dispatch('close')" class="bg-blue-600 hover:bg-blue-500">
                                    {{ __('Entendido') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </div>
            </div>
            </template>
    </div>
    </x-modal>

    <!-- Modal Confirmación de Cancelación -->
    <x-modal name="confirm-cancel-assignment-modal" focusable>
        <div class="p-6 bg-gray-800 text-gray-100">
            <h2 class="text-lg font-medium text-red-500 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
                {{ __('¿Terminar Asignación?') }}
            </h2>

            <p class="mb-6 text-gray-300">
                {{ __('¿Estás seguro de que deseas terminar esta asignación? El activo volverá a estar disponible para ser asignado nuevamente.') }}
            </p>

            <form method="POST" :action="cancelAssignmentAction" class="w-full">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <x-input-label for="estado_devolucion" :value="__('Estado de Devolución')" class="text-gray-300" />
                    <select id="estado_devolucion" name="estado_devolucion"
                        class="mt-1 block w-full bg-gray-900 border-gray-700 text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required>
                        <option value="good" selected>{{ __('Bueno') }}</option>
                        <option value="regular">{{ __('Regular') }}</option>
                        <option value="bad">{{ __('Malo') }}</option>
                        <option value="damaged">{{ __('Dañado') }}</option>
                    </select>
                </div>

                <div class="mb-6">
                    <x-input-label for="comentarios_devolucion" :value="__('Comentarios / Incidentes')"
                        class="text-gray-300" />
                    <textarea id="comentarios_devolucion" name="comentarios_devolucion" rows="3"
                        class="mt-1 block w-full bg-gray-900 border-gray-700 text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Describa el estado o cualquier incidente..."></textarea>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <x-secondary-button @click="$dispatch('close')">
                        {{ __('Cancelar') }}
                    </x-secondary-button>

                    <x-danger-button class="bg-red-600 hover:bg-red-500">
                        {{ __('Sí, Terminar Asignación') }}
                    </x-danger-button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Modal Editar Asignación -->
    <x-modal name="edit-assignment-modal" focusable>
        <div class="p-6 bg-gray-800 text-gray-100">
            <template x-if="assignmentAsset && assignmentAsset.active_assignment">
                <div>
                    <h2 class="text-lg font-medium text-white mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        {{ __('Editar Asignación') }}
                    </h2>

                    <form method="POST" :action="updateAssignmentAction">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="edit_fecha_entrega" :value="__('Fecha Entrega')"
                                    class="text-gray-300" />
                                <input id="edit_fecha_entrega" type="date" name="fecha_entrega"
                                    :value="assignmentAsset.active_assignment.start_date"
                                    class="mt-1 block w-full bg-gray-900 border-gray-700 text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required />
                            </div>
                            <div>
                                <x-input-label for="edit_fecha_estimada_devolucion" :value="__('Devolución Estimada')"
                                    class="text-gray-300" />
                                <input id="edit_fecha_estimada_devolucion" type="date" name="fecha_estimada_devolucion"
                                    :value="assignmentAsset.active_assignment.end_date"
                                    class="mt-1 block w-full bg-gray-900 border-gray-700 text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            </div>
                        </div>

                        <div class="mb-6">
                            <x-input-label for="edit_observaciones" :value="__('Observaciones')"
                                class="text-gray-300" />
                            <textarea id="edit_observaciones" name="observaciones" rows="3"
                                class="mt-1 block w-full bg-gray-900 border-gray-700 text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                x-text="assignmentAsset.active_assignment.observations"></textarea>
                        </div>

                        <div class="flex justify-end gap-3">
                            <x-secondary-button @click="$dispatch('close')">
                                {{ __('Cancelar') }}
                            </x-secondary-button>

                            <x-primary-button class="bg-blue-600 hover:bg-blue-500">
                                {{ __('Guardar Cambios') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </template>
        </div>
    </x-modal>

    <!-- Modal Error RUT -->
    <x-modal name="rut-error-modal" focusable>
        <div class="p-6 bg-gray-800 text-gray-100">
            <h2 class="text-lg font-medium text-red-500 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ __('RUT Duplicado') }}
            </h2>

            <p class="mb-6 text-gray-300">
                Este RUT pertenece a un <strong>USUARIO</strong> del sistema. <br>
                Por favor, selecciona la opción <strong>"Usuario del Sistema"</strong> en lugar de "Trabajador".
            </p>

            <div class="flex justify-end">
                <x-primary-button x-on:click="$dispatch('close')"
                    class="bg-red-600 hover:bg-red-700 border-transparent">
                    {{ __('Entendido') }}
                </x-primary-button>
            </div>
        </div>
    </x-modal>
    </div>
</x-app-layout>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('rutValidation', () => ({
            // Lógica auxiliar si fuera necesario, pero lo manejamos directo en los inputs o con JS vainilla abajo para accesar al DOM facilmente
        }));
    });

    // Función formato RUT
    function formatRut(rut) {
        // Eliminar todo lo que no sea ni número ni k/K
        let value = rut.replace(/[^0-9kK]/g, '');

        if (value.length < 2) return value;

        // Separar cuerpo y dígito verificador
        let body = value.slice(0, -1);
        let dv = value.slice(-1).toUpperCase();

        // Formatear cuerpo con puntos
        rut = body.replace(/\B(?=(\d{3})+(?!\d))/g, ".") + '-' + dv;
        return rut;
    }

    // Listener para el input de RUT
    document.addEventListener('input', function (e) {
        if (e.target && e.target.id === 'trabajador_rut') {
            let input = e.target;
            let originalValue = input.value;
            let formatted = formatRut(originalValue);

            // Solo actualizar si cambió para evitar saltos de cursor extraños (aunque simple replace funciona bien al final)
            if (originalValue !== formatted) {
                input.value = formatted;
                // Disparar evento input para que Alpine lo note si estuviera bindeado con x-model (en este caso es name directo, pero por si acaso)
                input.dispatchEvent(new Event('input'));
            }
        }
    });

    // Listener para verificación al perder foco
    document.addEventListener('focusout', function (e) {
        if (e.target && e.target.id === 'trabajador_rut') {
            let rut = e.target.value;
            if (rut.length < 3) return; // Muy corto para validar

            fetch(`{{ route('workers.check-rut') }}?rut=${encodeURIComponent(rut)}`)
                .then(response => response.json())
                .then(data => {
                    const errorContainer = document.getElementById('rut-error-message'); // Necesitamos agregar este contenedor
                    const btnSubmit = document.querySelector('button[type="submit"]'); // O el botón de confirmar

                    // Limpiar errores previos
                    if (errorContainer) {
                        errorContainer.textContent = '';
                        errorContainer.classList.add('hidden');
                    }

                    if (data.exists_in_users) {
                        // Mostrar modal de error
                        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'rut-error-modal' }));
                        e.target.value = ''; // Limpiar RUT
                    } else if (data.exists_in_conductores) {
                        // Autocompletar
                        document.getElementById('trabajador_nombre').value = data.data.nombre || '';
                        document.getElementById('trabajador_departamento').value = data.data.departamento || '';
                        document.getElementById('trabajador_cargo').value = data.data.cargo || '';

                        // Notificar visualmente
                        // alert('Conductor encontrado. Datos cargados automáticamente.');
                    }
                })
                .catch(error => console.error('Error validando RUT:', error));
        }
    });
</script>