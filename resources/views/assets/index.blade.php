<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Inventario de Activos') }}
            </h2>
            <button @click="$dispatch('open-modal', 'create-asset-modal')"
                class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-bold hover:bg-blue-500 transition duration-150 shadow-md">
                + Nuevo Activo
            </button>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ 
            filtersOpen: false, 
            searchQuery: '{{ request('search') }}',
            submitSearch() {
                let url = new URL(window.location.href);
                url.searchParams.set('search', this.searchQuery);
                url.searchParams.set('page', 1); // Reset page on search
                window.location.href = url.toString();
            }
        }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative dark:bg-green-900/30 dark:border-green-600 dark:text-green-300 shadow-sm"
                    role="alert">
                    <span class="block sm:inline font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Top Controls: Search & Filter Toggle -->
            <div class="mb-6 flex flex-col sm:flex-row gap-4 items-center">
                <!-- Search Bar -->
                <div class="relative w-full sm:max-w-md">
                    <input type="text" x-model="searchQuery" @keydown.enter="submitSearch()"
                        placeholder="Buscar por nombre, código, serie..."
                        class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-lg pl-10 pr-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Filters Button -->
                <div class="flex gap-2 w-full sm:w-auto">
                    <button type="button" @click="filtersOpen = true"
                        class="px-4 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 font-bold text-sm transition-colors flex items-center gap-2 border border-gray-300 dark:border-gray-600 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                            </path>
                        </svg>
                        Filtros
                        @if(request()->anyFilled(['category_id', 'status']))
                            <span class="bg-blue-600 text-white text-[10px] px-1.5 py-0.5 rounded-full ml-1">
                                {{ collect([request('category_id'), request('status')])->filter()->count() }}
                            </span>
                        @endif
                    </button>

                    @if(request()->anyFilled(['search', 'category_id', 'status']))
                        <a href="{{ route('assets.index') }}"
                            class="px-3 py-2 bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 hover:text-red-500 hover:bg-gray-300 dark:hover:bg-gray-600 rounded-md transition-colors flex items-center justify-center border border-transparent"
                            title="Limpiar Filtros">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Filter Sidebar (Off-canvas Right) -->
            <div x-show="filtersOpen" class="fixed inset-0 z-50 flex justify-end" style="display: none;">
                <!-- Backdrop -->
                <div @click="filtersOpen = false" x-show="filtersOpen"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>

                <!-- Sidebar Content -->
                <div x-show="filtersOpen" x-transition:enter="transition transform ease-out duration-300"
                    x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                    x-transition:leave="transition transform ease-in duration-300"
                    x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                    class="relative w-80 bg-white dark:bg-gray-800 h-full shadow-2xl p-6 overflow-y-auto border-l border-gray-700">

                    <div class="flex justify-between items-center mb-8">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                                </path>
                            </svg>
                            Filtros
                        </h3>
                        <button @click="filtersOpen = false" class="text-gray-400 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form method="GET" action="{{ route('assets.index') }}">
                        <input type="hidden" name="search" :value="searchQuery">

                        <!-- Filter: Category -->
                        <div class="mb-6">
                            <label
                                class="block text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">Categoría</label>
                            <select name="category_id"
                                class="w-full bg-gray-900 border-gray-700 text-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                <option value="">Todas</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter: Status -->
                        <div class="mb-6">
                            <label
                                class="block text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">Estado</label>
                            <div class="space-y-2">
                                <label
                                    class="flex items-center space-x-3 p-3 rounded-lg border border-gray-700 hover:bg-gray-700/50 cursor-pointer transition-colors {{ request('status') === 'available' ? 'bg-blue-900/30 border-blue-500' : '' }}">
                                    <input type="radio" name="status" value="available" class="hidden" {{ request('status') === 'available' ? 'checked' : '' }}>
                                    <span
                                        class="w-3 h-3 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.6)]"></span>
                                    <span class="text-gray-200">Disponible</span>
                                </label>
                                <label
                                    class="flex items-center space-x-3 p-3 rounded-lg border border-gray-700 hover:bg-gray-700/50 cursor-pointer transition-colors {{ request('status') === 'assigned' ? 'bg-blue-900/30 border-blue-500' : '' }}">
                                    <input type="radio" name="status" value="assigned" class="hidden" {{ request('status') === 'assigned' ? 'checked' : '' }}>
                                    <span
                                        class="w-3 h-3 rounded-full bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.6)]"></span>
                                    <span class="text-gray-200">Asignado</span>
                                </label>
                                <label
                                    class="flex items-center space-x-3 p-3 rounded-lg border border-gray-700 hover:bg-gray-700/50 cursor-pointer transition-colors {{ request('status') === 'maintenance' ? 'bg-blue-900/30 border-blue-500' : '' }}">
                                    <input type="radio" name="status" value="maintenance" class="hidden" {{ request('status') === 'maintenance' ? 'checked' : '' }}>
                                    <span
                                        class="w-3 h-3 rounded-full bg-yellow-500 shadow-[0_0_8px_rgba(234,179,8,0.6)]"></span>
                                    <span class="text-gray-200">En Mantención</span>
                                </label>
                                <label
                                    class="flex items-center space-x-3 p-3 rounded-lg border border-gray-700 hover:bg-gray-700/50 cursor-pointer transition-colors {{ request('status') === 'written_off' ? 'bg-blue-900/30 border-blue-500' : '' }}">
                                    <input type="radio" name="status" value="written_off" class="hidden" {{ request('status') === 'written_off' ? 'checked' : '' }}>
                                    <span
                                        class="w-3 h-3 rounded-full bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.6)]"></span>
                                    <span class="text-gray-200">De Baja</span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-700 flex flex-col gap-3">
                            <button type="submit"
                                class="w-full py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-lg transition-all shadow-lg shadow-blue-500/30">
                                Aplicar Filtros
                            </button>
                            @if(request()->anyFilled(['category_id', 'status']))
                                <a href="{{ route('assets.index', ['search' => request('search')]) }}"
                                    class="w-full py-3 text-center text-gray-400 hover:text-white font-medium hover:bg-gray-700 rounded-lg transition-colors">
                                    Limpiar Filtros
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Activo</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Categoría</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Estado</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Ubicación / Asignado</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($assets as $asset)
                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition duration-150">
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <div class="flex items-center">
                                                                    <div
                                                                        class="flex-shrink-0 h-10 w-10 bg-gray-100 dark:bg-gray-900 rounded-md overflow-hidden flex items-center justify-center border border-gray-200 dark:border-gray-600">
                                                                        @if($asset->image_path)
                                                                            <img class="h-10 w-10 object-cover"
                                                                                src="{{ asset('storage/' . $asset->image_path) }}"
                                                                                alt="{{ $asset->name }}">
                                                                        @else
                                                                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                                </path>
                                                                            </svg>
                                                                        @endif
                                                                    </div>
                                                                    <div class="ml-4">
                                                                        <div class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                                                            {{ $asset->name }}</div>
                                                                        <div class="text-xs text-gray-500 dark:text-gray-400 font-mono">
                                                                            {{ $asset->internal_code }}</div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                {{ $asset->category->name }}
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <span
                                                                    class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-md 
                                                                        {{ $asset->status === 'available' ? 'text-green-400 bg-green-900/30 border border-green-900' : '' }}
                                                                        {{ $asset->status === 'assigned' ? 'text-blue-400 bg-blue-900/30 border border-blue-900' : '' }}
                                                                        {{ $asset->status === 'maintenance' ? 'text-yellow-400 bg-yellow-900/30 border border-yellow-900' : '' }}
                                                                        {{ $asset->status === 'written_off' ? 'text-red-400 bg-red-900/30 border border-red-900' : '' }}">
                                                                    {{ match ($asset->status) {
                                    'available' => 'DISPONIBLE',
                                    'assigned' => 'ASIGNADO',
                                    'maintenance' => 'EN MANTENCIÓN',
                                    'written_off' => 'DE BAJA',
                                    default => strtoupper($asset->status)
                                } }}
                                                                </span>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                @if($asset->status === 'assigned' && $asset->active_assignment)
                                                                    <div class="flex items-center">
                                                                        <div
                                                                            class="h-6 w-6 rounded-full bg-blue-900 flex items-center justify-center text-xs font-bold text-blue-300 mr-2 border border-blue-700">
                                                                            {{ substr($asset->active_assignment->user->name ?? 'U', 0, 1) }}
                                                                        </div>
                                                                        <div>
                                                                            <div class="font-medium text-gray-200">
                                                                                {{ $asset->active_assignment->user->short_name ?? 'Usuario' }}</div>
                                                                            <div class="text-xs text-gray-500">
                                                                                {{ $asset->active_assignment->assignment_details ?? '' }}</div>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <div class="flex items-center text-gray-500">
                                                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                                            </path>
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                        </svg>
                                                                        {{ $asset->location ?? 'Sin ubicación' }}
                                                                    </div>
                                                                @endif
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                                <a href="{{ route('assets.show', $asset) }}"
                                                                    class="text-blue-400 hover:text-blue-300 font-bold hover:underline">Ver</a>
                                                            </td>
                                                        </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="h-12 w-12 text-gray-600 mb-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                                                </path>
                                            </svg>
                                            <p class="text-lg font-medium">No se encontraron activos</p>
                                            <p class="text-sm">Intenta ajustar los filtros o agrega un nuevo activo.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                    {{ $assets->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nuevo Activo -->
    <x-modal name="create-asset-modal" focusable>
        <form method="POST" action="{{ route('assets.store') }}" enctype="multipart/form-data"
            class="p-6 bg-gray-800 text-gray-100">
            @csrf

            <h2 class="text-lg font-medium text-gray-100 mb-4 border-b border-gray-700 pb-2">
                {{ __('Registrar Nuevo Activo') }}
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Código Interno -->
                <div>
                    <x-input-label for="internal_code" value="Código Interno *" class="text-gray-400" />
                    <x-text-input id="internal_code" name="internal_code" type="text"
                        class="mt-1 block w-full bg-gray-900 border-gray-700 text-gray-100" required
                        placeholder="Ej: NB-001" />
                    <x-input-error :messages="$errors->get('internal_code')" class="mt-2" />
                </div>

                <!-- Nombre -->
                <div>
                    <x-input-label for="name" value="Nombre del Activo *" class="text-gray-400" />
                    <x-text-input id="name" name="name" type="text"
                        class="mt-1 block w-full bg-gray-900 border-gray-700 text-gray-100" required
                        placeholder="Ej: Notebook Dell Latitude" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Categoría -->
                <div>
                    <x-input-label for="asset_category_id" value="Categoría *" class="text-gray-400" />
                    <select id="asset_category_id" name="asset_category_id"
                        class="mt-1 block w-full border-gray-700 bg-gray-900 text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        required>
                        <option value="">Seleccionar...</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('asset_category_id')" class="mt-2" />
                </div>

                <!-- Estado Inicial -->
                <div>
                    <x-input-label for="status" value="Estado Inicial *" class="text-gray-400" />
                    <select id="status" name="status"
                        class="mt-1 block w-full border-gray-700 bg-gray-900 text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        required>
                        <option value="available">Disponible</option>
                        <option value="maintenance">En Mantención</option>
                        <option value="assigned">Asignado (Requiere asignar luego)</option>
                    </select>
                </div>

                <!-- Marca -->
                <div>
                    <x-input-label for="brand" value="Marca" class="text-gray-400" />
                    <x-text-input id="brand" name="brand" type="text"
                        class="mt-1 block w-full bg-gray-900 border-gray-700 text-gray-100" placeholder="Ej: Dell" />
                </div>

                <!-- Modelo -->
                <div>
                    <x-input-label for="model" value="Modelo" class="text-gray-400" />
                    <x-text-input id="model" name="model" type="text"
                        class="mt-1 block w-full bg-gray-900 border-gray-700 text-gray-100"
                        placeholder="Ej: Latitude 5420" />
                </div>

                <!-- Ubicación -->
                <div>
                    <x-input-label for="location" value="Ubicación Física" class="text-gray-400" />
                    <x-text-input id="location" name="location" type="text"
                        class="mt-1 block w-full bg-gray-900 border-gray-700 text-gray-100"
                        placeholder="Ej: Bodega Central" />
                </div>

                <!-- Costo -->
                <div>
                    <x-input-label for="cost" value="Valor Referencial ($)" class="text-gray-400" />
                    <x-text-input id="cost" name="cost" type="number"
                        class="mt-1 block w-full bg-gray-900 border-gray-700 text-gray-100" placeholder="Ej: 500000" />
                </div>

                <!-- Fecha Adquisición -->
                <div>
                    <x-input-label for="acquisition_date" value="Fecha Adquisición" class="text-gray-400" />
                    <x-text-input id="acquisition_date" name="acquisition_date" type="date"
                        class="mt-1 block w-full bg-gray-900 border-gray-700 text-gray-100" />
                </div>

                <!-- Código Barras / Serie -->
                <div>
                    <x-input-label for="barcode" value="Nº Serie / Código Barra" class="text-gray-400" />
                    <x-text-input id="barcode" name="barcode" type="text"
                        class="mt-1 block w-full bg-gray-900 border-gray-700 text-gray-100" />
                </div>
            </div>

            <!-- Imagen -->
            <div class="mt-4">
                <x-input-label for="image" value="Fotografía" class="text-gray-400" />
                <input id="image" name="image" type="file" accept="image/*" class="mt-1 block w-full text-sm text-gray-400
                file:mr-4 file:py-2 file:px-4
                file:rounded-md file:border-0
                file:text-sm file:font-semibold
                file:bg-blue-600 file:text-white
                hover:file:bg-blue-700 cursor-pointer bg-gray-900 border border-gray-700 rounded-md" />
            </div>

            <!-- Observaciones -->
            <div class="mt-4">
                <x-input-label for="observations" value="Observaciones" class="text-gray-400" />
                <textarea id="observations" name="observations" rows="3"
                    class="mt-1 block w-full border-gray-700 bg-gray-900 text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')"
                    class="px-4 py-2 bg-gray-700 text-gray-300 rounded-md hover:bg-gray-600 transition border border-gray-600">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500 transition font-bold shadow-lg shadow-blue-500/30">
                    Guardar Activo
                </button>
            </div>
        </form>
    </x-modal>

</x-app-layout>