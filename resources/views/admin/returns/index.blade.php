<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Historial de Entregas') }}
            </h2>
            <a href="{{ route('admin.returns.trash') }}"
                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                    </path>
                </svg>
                {{ __('Papelera') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ 
        filtersOpen: false,
        searchQuery: '{{ request('search') }}',
        filterType: '{{ request('filter_type', 'day') }}',
        updateInputType() {
            const input = this.$refs.dateInput;
            if (this.filterType === 'day') {
                input.type = 'date';
            } else if (this.filterType === 'month') {
                input.type = 'month';
            } else if (this.filterType === 'year') {
                input.type = 'number';
                input.min = '2020';
                input.max = new Date().getFullYear();
            }
        }
    }" x-init="updateInputType()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Barra de Búsqueda y Filtros -->
            <div class="mb-6 flex flex-col sm:flex-row gap-4 items-center">
                <form method="GET" action="{{ route('admin.returns.index') }}" class="relative w-full sm:max-w-md">
                    <input type="text" name="search" x-model="searchQuery" placeholder="Buscar por usuario o patente..."
                        class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-lg pl-10 pr-4 py-2 focus:ring-2 focus:ring-indigo-500 transition-shadow">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </form>

                <div class="flex gap-2 w-full sm:w-auto">
                    <button type="button" @click="filtersOpen = true"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 font-bold text-sm transition-colors flex items-center gap-2 border border-gray-300 dark:border-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                            </path>
                        </svg>
                        Filtros
                        @if(request('filter_type'))
                            <span class="bg-indigo-500 text-white text-[10px] px-1.5 py-0.5 rounded-full">1</span>
                        @endif
                    </button>

                    <template x-if="searchQuery || '{{ request('filter_type') }}' || '{{ request('request_id') }}'">
                        <a href="{{ route('admin.returns.index') }}"
                            class="px-3 py-2 text-gray-500 hover:text-red-500 transition-colors"
                            title="Limpiar Filtros">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </a>
                    </template>
                </div>
            </div>

            <!-- Filter Sidebar (Off-canvas) -->
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
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                    <form method="GET" action="{{ route('admin.returns.index') }}">
                        <input type="hidden" name="search" :value="searchQuery">

                        <!-- Filtro de Fecha -->
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-400 uppercase tracking-wider mb-3">Filtrar
                                por Fecha</label>
                            <div class="space-y-3">
                                <select name="filter_type" x-model="filterType" @change="updateInputType()"
                                    class="w-full rounded-lg bg-gray-700 border border-gray-600 text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-3">
                                    <option value="day">Día</option>
                                    <option value="month">Mes</option>
                                    <option value="year">Año</option>
                                </select>
                                <input type="date" name="filter_value" x-ref="dateInput"
                                    value="{{ request('filter_value') }}"
                                    class="w-full rounded-lg bg-gray-700 border border-gray-600 text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-3">
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-700 flex flex-col gap-3">
                            <button type="submit"
                                class="w-full py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-lg transition-all shadow-lg shadow-indigo-500/30">
                                Aplicar Filtros
                            </button>
                            @if(request('filter_type'))
                                <a href="{{ route('admin.returns.index', ['search' => request('search')]) }}"
                                    class="w-full py-3 text-center text-gray-400 hover:text-white font-medium hover:bg-gray-700 rounded-lg transition-colors">
                                    Limpiar Fecha
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if($returns->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Fecha</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Vehículo</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Usuario</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Estado Entrega</th>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($returns as $return)
                                                        <tr>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                                {{ $return->created_at->format('d/m/Y H:i') }}
                                                            </td>
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                                <div class="flex items-center">
                                                                    @if($return->request->vehicle && $return->request->vehicle->image_path)
                                                                        <div class="flex-shrink-0 h-10 w-10">
                                                                            <img class="h-10 w-10 rounded-full object-cover"
                                                                                src="{{ Storage::url($return->request->vehicle->image_path) }}"
                                                                                alt="{{ $return->request->vehicle->plate }}">
                                                                        </div>
                                                                    @else
                                                                        <div
                                                                            class="flex-shrink-0 h-10 w-10 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                                                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                                                stroke="currentColor">
                                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                            </svg>
                                                                        </div>
                                                                    @endif
                                                                    <div class="ml-4">
                                                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                                            @if($return->request->vehicle)
                                                                                {{ $return->request->vehicle->brand }}
                                                                                {{ $return->request->vehicle->model }}
                                                                            @else
                                                                                <span class="text-red-500 italic">Vehículo Eliminado</span>
                                                                            @endif
                                                                        </div>
                                                                        @if($return->request->vehicle)
                                                                            {{ $return->request->vehicle->plate }}
                                                                        @endif
                                                                    </div>
                                                                </div>
                                            </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $return->request->user->name }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                <div class="flex flex-col space-y-1">
                                                    <span class="text-xs">
                                                        ⛽ Combustible:
                                                        <span
                                                            class="font-bold {{ $return->fuel_level == 'full' ? 'text-green-500' : 'text-yellow-500' }}">
                                                            {{ $return->fuel_level }}
                                                        </span>
                                                    </span>
                                                    <span class="text-xs">
                                                        🧼 Limpieza:
                                                        <span
                                                            class="font-bold {{ $return->cleanliness == 'clean' ? 'text-green-500' : 'text-red-500' }}">
                                                            {{ $return->cleanliness == 'clean' ? 'Limpio' : ($return->cleanliness == 'dirty' ? 'Sucio' : 'Muy Sucio') }}
                                                        </span>
                                                    </span>
                                                    <span class="text-xs">
                                                        🛞 Neumáticos:
                                                        <span
                                                            class="font-bold {{ ($return->tire_status_front == 'good' && $return->tire_status_rear == 'good') ? 'text-green-500' : 'text-red-500' }}">
                                                            {{ ($return->tire_status_front == 'good' && $return->tire_status_rear == 'good') ? 'OK' : 'Revisar' }}
                                                        </span>
                                                    </span>
                                                    @if($return->body_damage_reported)
                                                        <span class="text-xs font-bold text-red-500 bg-red-100 px-1 rounded w-max">
                                                            ⚠️ Daño Carrocería
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div x-data="{ 
                                                                                                                            carouselOpen: false, 
                                                                                                                            images: [], 
                                                                                                                            currentImage: '', 
                                                                                                                            currentIndex: 0,
                                                                                                                            openCarousel(imgs, index) {
                                                                                                                                this.images = imgs;
                                                                                                                                this.currentIndex = index;
                                                                                                                                this.currentImage = this.images[index];
                                                                                                                                this.carouselOpen = true;
                                                                                                                            },
                                                                                                                            next() {
                                                                                                                                this.currentIndex = (this.currentIndex + 1) % this.images.length;
                                                                                                                                this.currentImage = this.images[this.currentIndex];
                                                                                                                            },
                                                                                                                            prev() {
                                                                                                                                this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
                                                                                                                                this.currentImage = this.images[this.currentIndex];
                                                                                                                            }
                                                                                                                        }">
                                                    <div class="flex justify-end items-center space-x-2">
                                                        <button @click="$dispatch('open-modal', 'view-return-{{ $return->id }}')"
                                                            class="p-2 text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-colors"
                                                            title="Ver Ficha">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                                </path>
                                                            </svg>
                                                        </button>

                                                        <button @click="$dispatch('open-modal', 'delete-return-modal-{{ $return->id }}')"
                                                            class="p-2 text-red-600 hover:text-red-900 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full transition-colors"
                                                            title="Eliminar">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <!-- Modal Confirm Delete -->
                                                    <!-- Modal Confirm Delete -->
                                                    <template x-teleport="body">
                                                        <x-modal name="delete-return-modal-{{ $return->id }}" :show="false" focusable>
                                                            <form method="POST" action="{{ route('admin.returns.destroy', $return->id) }}"
                                                                class="p-6">
                                                                @csrf
                                                                @method('DELETE')
                                                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                                                    {{ __('¿Mover a la papelera?') }}
                                                                </h2>
                                                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 whitespace-normal">
                                                                    {{ __('La entrega se moverá a la papelera de reciclaje. Podrás restaurarla o eliminarla permanentemente desde allí.') }}
                                                                </p>
                                                                <div class="mt-6 flex justify-end">
                                                                    <x-secondary-button x-on:click="$dispatch('close')">
                                                                        {{ __('Cancelar') }}
                                                                    </x-secondary-button>
                                                                    <x-danger-button class="ml-3">
                                                                        {{ __('Mover a Papelera') }}
                                                                    </x-danger-button>
                                                                </div>
                                                            </form>
                                                        </x-modal>
                                                    </template>

                                                    <!-- Modal Detalle Mejorado -->
                                                    <template x-teleport="body">
                                                        <x-modal name="view-return-{{ $return->id }}" :show="false" focusable maxWidth="5xl">
                                                            <div class="p-8 bg-white dark:bg-gray-800 rounded-lg">

                                                                <!-- Header -->
                                                                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
                                                                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                                                        Detalle de Entrega #{{ $return->id }}
                                                                    </h2>
                                                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                                        Registrada el {{ $return->created_at->format('d/m/Y H:i') }}
                                                                    </p>
                                                                </div>

                                                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                                                                    <!-- Columna Izquierda -->
                                                                    <div class="space-y-6">

                                                                        <!-- Información del Vehículo -->
                                                                        <div
                                                                            class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                                                            <h3
                                                                                class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
                                                                                <svg class="w-5 h-5 text-indigo-500" fill="none"
                                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                                </svg>
                                                                                Información del Vehículo
                                                                            </h3>
                                                                            @if($return->request->vehicle)
                                                                                <!-- Imagen del Vehículo -->
                                                                                @if($return->request->vehicle->image_path)
                                                                                    <div
                                                                                        class="mb-4 rounded-lg overflow-hidden border border-gray-300 dark:border-gray-600">
                                                                                        <img src="{{ Storage::url($return->request->vehicle->image_path) }}"
                                                                                            class="w-full h-32 object-cover"
                                                                                            alt="{{ $return->request->vehicle->plate }}">
                                                                                    </div>
                                                                                @else
                                                                                    <div
                                                                                        class="mb-4 rounded-lg overflow-hidden border border-gray-300 dark:border-gray-600 bg-gray-200 dark:bg-gray-700 flex items-center justify-center h-32">
                                                                                        <svg class="w-16 h-16 text-gray-400" fill="none"
                                                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                                stroke-width="2"
                                                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                                        </svg>
                                                                                    </div>
                                                                                @endif

                                                                                <div class="space-y-2 text-sm">
                                                                                    <div class="flex justify-between">
                                                                                        <span
                                                                                            class="text-gray-600 dark:text-gray-400">Modelo:</span>
                                                                                        <span class="font-medium text-gray-900 dark:text-gray-100">
                                                                                            {{ $return->request->vehicle->brand }}
                                                                                            {{ $return->request->vehicle->model }}
                                                                                        </span>
                                                                                    </div>
                                                                                    <div class="flex justify-between">
                                                                                        <span
                                                                                            class="text-gray-600 dark:text-gray-400">Patente:</span>
                                                                                        <span
                                                                                            class="font-bold text-indigo-600 dark:text-indigo-400">
                                                                                            {{ $return->request->vehicle->plate }}
                                                                                        </span>
                                                                                    </div>
                                                                                    @php
                                                                                        $kmInicial = $return->request->vehicle->mileage ?? 0;
                                                                                        $kmFinal = $return->return_mileage;
                                                                                        $kmRecorridos = $kmFinal - $kmInicial;
                                                                                    @endphp
                                                                                    <div class="flex justify-between">
                                                                                        <span class="text-gray-600 dark:text-gray-400">KM
                                                                                            Inicial:</span>
                                                                                        <span class="font-medium text-gray-900 dark:text-gray-100">
                                                                                            {{ number_format($kmInicial, 0, '', '.') }} km
                                                                                        </span>
                                                                                    </div>
                                                                                    <div class="flex justify-between">
                                                                                        <span class="text-gray-600 dark:text-gray-400">KM
                                                                                            Devolución:</span>
                                                                                        <span class="font-medium text-gray-900 dark:text-gray-100">
                                                                                            {{ number_format($kmFinal, 0, '', '.') }} km
                                                                                        </span>
                                                                                    </div>
                                                                                    <div
                                                                                        class="flex justify-between pt-2 border-t border-gray-300 dark:border-gray-600">
                                                                                        <span
                                                                                            class="text-gray-900 dark:text-gray-100 font-semibold">KM
                                                                                            Recorridos:</span>
                                                                                        <span
                                                                                            class="font-bold text-green-600 dark:text-green-400 text-lg">
                                                                                            {{ number_format($kmRecorridos, 0, '', '.') }} km
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            @else
                                                                                <p class="text-red-500 italic">Vehículo eliminado</p>
                                                                            @endif
                                                                        </div>

                                                                        <!-- Combustible Cargado -->
                                                                        <div
                                                                            class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                                                            <h3
                                                                                class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
                                                                                <svg class="w-5 h-5 text-yellow-500" fill="none"
                                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                                        stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                                                </svg>
                                                                                Combustible Cargado
                                                                            </h3>
                                                                            @php
                                                                                $fuelLoads = $return->request->fuelLoads ?? collect();
                                                                                $totalLiters = $fuelLoads->sum('liters');
                                                                                $totalCost = $fuelLoads->sum('total_cost');
                                                                            @endphp

                                                                            @if($fuelLoads->count() > 0)
                                                                                <div class="space-y-3">
                                                                                    @foreach($fuelLoads as $fuel)
                                                                                        <div
                                                                                            class="bg-white dark:bg-gray-800 rounded p-3 border border-gray-200 dark:border-gray-600">
                                                                                            <div class="flex justify-between items-start mb-2">
                                                                                                <div>
                                                                                                    <p
                                                                                                        class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                                                                        {{ $fuel->date->format('d/m/Y') }}
                                                                                                    </p>
                                                                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                                                                        {{ number_format($fuel->mileage, 0, '', '.') }}
                                                                                                        km
                                                                                                    </p>
                                                                                                </div>
                                                                                                <div class="text-right">
                                                                                                    <p
                                                                                                        class="text-lg font-bold text-yellow-600 dark:text-yellow-400">
                                                                                                        {{ number_format($fuel->liters, 2, ',', '.') }}
                                                                                                        L
                                                                                                    </p>
                                                                                                    <p class="text-xs text-gray-600 dark:text-gray-400">
                                                                                                        ${{ number_format($fuel->total_cost, 0, '', '.') }}
                                                                                                    </p>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    @endforeach

                                                                                    <!-- Total -->
                                                                                    <div
                                                                                        class="bg-yellow-50 dark:bg-yellow-900/20 rounded p-3 border border-yellow-300 dark:border-yellow-700">
                                                                                        <div class="flex justify-between items-center">
                                                                                            <span
                                                                                                class="font-semibold text-gray-900 dark:text-gray-100">Total:</span>
                                                                                            <div class="text-right">
                                                                                                <p
                                                                                                    class="text-xl font-bold text-yellow-700 dark:text-yellow-400">
                                                                                                    {{ number_format($totalLiters, 2, ',', '.') }} L
                                                                                                </p>
                                                                                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                                                                                    ${{ number_format($totalCost, 0, '', '.') }}
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @else
                                                                                <p class="text-gray-500 dark:text-gray-400 italic text-sm">
                                                                                    No se registraron cargas de combustible
                                                                                </p>
                                                                            @endif
                                                                        </div>

                                                                        <!-- Estado del Vehículo -->
                                                                        <div
                                                                            class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                                                            <h3
                                                                                class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
                                                                                <svg class="w-5 h-5 text-green-500" fill="none"
                                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                                </svg>
                                                                                Estado Reportado
                                                                            </h3>
                                                                            @php
                                                                                $tireMap = ['good' => 'Bueno', 'fair' => 'Regular', 'poor' => 'Malo'];
                                                                                $cleanMap = ['clean' => 'Limpio', 'dirty' => 'Sucio', 'very_dirty' => 'Muy Sucio'];
                                                                            @endphp
                                                                            <div class="space-y-2 text-sm">
                                                                                <div class="flex justify-between">
                                                                                    <span class="text-gray-600 dark:text-gray-400">⛽
                                                                                        Combustible:</span>
                                                                                    <span
                                                                                        class="font-medium text-gray-900 dark:text-gray-100">{{ $return->fuel_level }}</span>
                                                                                </div>
                                                                                <div class="flex justify-between">
                                                                                    <span class="text-gray-600 dark:text-gray-400">🛞 Neum.
                                                                                        Delanteros:</span>
                                                                                    <span
                                                                                        class="font-medium text-gray-900 dark:text-gray-100">{{ $tireMap[$return->tire_status_front] ?? $return->tire_status_front }}</span>
                                                                                </div>
                                                                                <div class="flex justify-between">
                                                                                    <span class="text-gray-600 dark:text-gray-400">🛞 Neum.
                                                                                        Traseros:</span>
                                                                                    <span
                                                                                        class="font-medium text-gray-900 dark:text-gray-100">{{ $tireMap[$return->tire_status_rear] ?? $return->tire_status_rear }}</span>
                                                                                </div>
                                                                                <div class="flex justify-between">
                                                                                    <span class="text-gray-600 dark:text-gray-400">🧼
                                                                                        Limpieza:</span>
                                                                                    <span
                                                                                        class="font-medium text-gray-900 dark:text-gray-100">{{ $cleanMap[$return->cleanliness] ?? $return->cleanliness }}</span>
                                                                                </div>
                                                                                <div class="flex justify-between">
                                                                                    <span class="text-gray-600 dark:text-gray-400">⚠️ Daños
                                                                                        Carrocería:</span>
                                                                                    <span
                                                                                        class="font-medium {{ $return->body_damage_reported ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                                                                        {{ $return->body_damage_reported ? 'SÍ' : 'No' }}
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                    </div>

                                                                    <!-- Columna Derecha -->
                                                                    <div class="space-y-6">

                                                                        <!-- Información del Usuario -->
                                                                        <div
                                                                            class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                                                            <h3
                                                                                class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
                                                                                <svg class="w-5 h-5 text-blue-500" fill="none"
                                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                                </svg>
                                                                                Datos del Usuario
                                                                            </h3>
                                                                            <div class="space-y-3">
                                                                                <div class="flex items-center gap-3">
                                                                                    @if($return->request->user->profile_photo_path)
                                                                                        <img class="h-12 w-12 rounded-full object-cover border-2 border-gray-300 dark:border-gray-600"
                                                                                            src="{{ asset('storage/' . $return->request->user->profile_photo_path) }}"
                                                                                            alt="{{ $return->request->user->name }}">
                                                                                    @else
                                                                                        <div
                                                                                            class="h-12 w-12 rounded-full bg-indigo-600 flex items-center justify-center text-white font-semibold text-lg">
                                                                                            {{ strtoupper(substr($return->request->user->name, 0, 2)) }}
                                                                                        </div>
                                                                                    @endif
                                                                                    <div>
                                                                                        <p
                                                                                            class="font-semibold text-gray-900 dark:text-gray-100">
                                                                                            {{ $return->request->user->name }}
                                                                                        </p>
                                                                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                                                                            {{ $return->request->user->cargo ?? 'Sin cargo' }}
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                                <div
                                                                                    class="space-y-2 text-sm pt-3 border-t border-gray-300 dark:border-gray-600">
                                                                                    <div class="flex items-center gap-2">
                                                                                        <svg class="w-4 h-4 text-gray-400" fill="none"
                                                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                                stroke-width="2"
                                                                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                                                        </svg>
                                                                                        <span
                                                                                            class="text-gray-600 dark:text-gray-400">Email:</span>
                                                                                        <a href="mailto:{{ $return->request->user->email }}"
                                                                                            class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                                                                            {{ $return->request->user->email }}
                                                                                        </a>
                                                                                    </div>
                                                                                    @if($return->request->user->phone)
                                                                                        <div class="flex items-center gap-2">
                                                                                            <svg class="w-4 h-4 text-gray-400" fill="none"
                                                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                                                    stroke-width="2"
                                                                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                                                            </svg>
                                                                                            <span
                                                                                                class="text-gray-600 dark:text-gray-400">Teléfono:</span>
                                                                                            <a href="tel:{{ $return->request->user->phone }}"
                                                                                                class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                                                                                {{ $return->request->user->phone }}
                                                                                            </a>
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Comentarios -->
                                                                        @if($return->comments)
                                                                            <div
                                                                                class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                                                                <h3
                                                                                    class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
                                                                                    <svg class="w-5 h-5 text-gray-500" fill="none"
                                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                                            stroke-width="2"
                                                                                            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                                                                    </svg>
                                                                                    Comentarios
                                                                                </h3>
                                                                                <p
                                                                                    class="text-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 p-3 rounded border border-gray-200 dark:border-gray-600">
                                                                                    {{ $return->comments }}
                                                                                </p>
                                                                            </div>
                                                                        @endif

                                                                        <!-- Fotos -->
                                                                        @if($return->photos_paths && count($return->photos_paths) > 0)
                                                                            <div
                                                                                class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                                                                <h3
                                                                                    class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
                                                                                    <svg class="w-5 h-5 text-purple-500" fill="none"
                                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                                            stroke-width="2"
                                                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                                    </svg>
                                                                                    Fotos de la Entrega ({{ count($return->photos_paths) }})
                                                                                </h3>
                                                                                @php
                                                                                    $gallery = collect($return->photos_paths)->map(fn($p) => Storage::url($p))->values();
                                                                                @endphp
                                                                                <div class="grid grid-cols-3 gap-2">
                                                                                    @foreach($gallery as $index => $photoUrl)
                                                                                        <button
                                                                                            @click="openCarousel({{ $gallery->toJson() }}, {{ $index }})"
                                                                                            class="block group relative aspect-square focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 rounded-lg overflow-hidden">
                                                                                            <img src="{{ $photoUrl }}"
                                                                                                class="w-full h-full object-cover group-hover:opacity-75 transition"
                                                                                                alt="Foto {{ $index + 1 }}">
                                                                                            <div
                                                                                                class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 bg-black/30 transition duration-300">
                                                                                                <svg class="w-8 h-8 text-white drop-shadow-md"
                                                                                                    fill="none" stroke="currentColor"
                                                                                                    viewBox="0 0 24 24">
                                                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                                                        stroke-width="2"
                                                                                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7">
                                                                                                    </path>
                                                                                                </svg>
                                                                                            </div>
                                                                                        </button>
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    </div>

                                                                </div>

                                                                <!-- Footer -->
                                                                <div
                                                                    class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                                                                    <button type="button"
                                                                        class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition shadow-sm"
                                                                        @click="$dispatch('close')">
                                                                        Cerrar
                                                                    </button>
                                                                </div>

                                                            </div>
                                                        </x-modal>
                                                    </template>

                                                    <!-- Modal Carousel (Lightbox) -->
                                                    <template x-teleport="body">
                                                        <div x-show="carouselOpen" class="fixed inset-0 z-[70] overflow-y-auto"
                                                            style="display: none;" x-transition>
                                                            <!-- Backdrop -->
                                                            <div class="fixed inset-0 bg-black bg-opacity-95 transition-opacity"
                                                                @click="carouselOpen = false"></div>

                                                            <!-- Content -->
                                                            <div class="flex items-center justify-center min-h-screen p-4 pointer-events-none">
                                                                <div
                                                                    class="relative w-full h-full flex flex-col items-center justify-center pointer-events-auto">

                                                                    <!-- Close Button -->
                                                                    <button @click="carouselOpen = false"
                                                                        class="absolute top-4 right-4 text-white hover:text-gray-300 z-[80] focus:outline-none p-2 rounded-full bg-black/50">
                                                                        <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                        </svg>
                                                                    </button>

                                                                    <!-- Main Container for Image + Nav -->
                                                                    <div class="relative flex items-center justify-center w-full max-w-6xl">
                                                                        <!-- Previous Button -->
                                                                        <button x-show="images.length > 1" @click.stop="prev()"
                                                                            class="absolute left-2 md:-left-12 p-3 text-white hover:text-gray-300 focus:outline-none bg-black/50 hover:bg-black/70 rounded-full z-[80] transition">
                                                                            <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                                    stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                                                            </svg>
                                                                        </button>

                                                                        <!-- Image -->
                                                                        <div class="relative">
                                                                            <img :src="currentImage"
                                                                                class="max-w-full max-h-[85vh] object-contain rounded shadow-2xl"
                                                                                @click.stop="">
                                                                            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-black/60 px-3 py-1 rounded-full text-white text-sm font-mono"
                                                                                x-show="images.length > 1">
                                                                                <span x-text="currentIndex + 1"></span> / <span
                                                                                    x-text="images.length"></span>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Next Button -->
                                                                        <button x-show="images.length > 1" @click.stop="next()"
                                                                            class="absolute right-2 md:-right-12 p-3 text-white hover:text-gray-300 focus:outline-none bg-black/50 hover:bg-black/70 rounded-full z-[80] transition">
                                                                            <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                                    stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                                            </svg>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </td>
                                            </tr>
                                        @endforeach
                            </tbody>
                            </table>
                            <div class="mt-4">
                                {{ $returns->links() }}
                            </div>
                        </div>
                    @else
                    <p class="text-center text-gray-500 dark:text-gray-400">No hay registros de entregas aún.</p>
                @endif
            </div>
        </div>
    </div>
    </div>
</x-app-layout>