<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Gestión de Vehículos') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('vehicles.trash') }}"
                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                    {{ __('Papelera') }}
                </a>
                <!-- Botón Solicitudes Pendientes -->
                <div class="relative">
                    <button x-data="" @click="$dispatch('open-modal', 'maintenance-requests-modal')"
                        class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-400 active:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 relative">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                            </path>
                        </svg>
                        Solicitudes
                        @if($pendingRequests->count() > 0)
                            <span class="absolute -top-1 -right-1 flex h-4 w-4">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span
                                    class="relative inline-flex rounded-full h-4 w-4 bg-red-500 text-white text-[10px] items-center justify-center font-bold">
                                    {{ $pendingRequests->count() }}
                                </span>
                            </span>
                        @endif
                    </button>
                </div>

                <!-- Botón Agregar Vehículo -->
                <button x-data="" @click="$dispatch('open-modal', 'create-vehicle-modal')"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    + {{ __('Nuevo Vehículo') }}
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12"
        x-data="{ openModal: {{ $errors->any() ? 'true' : 'false' }}, deleteAction: '', editingVehicle: {}, editAction: '', viewingVehicle: {}, maintenanceVehicle: {} }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">



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
                                        Patente
                                    </th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                        Marca / Modelo
                                    </th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                        Año
                                    </th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                        Kilometraje
                                    </th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700 bg-gray-900 text-gray-300">
                                @forelse($vehicles as $vehicle)
                                    <tr class="hover:bg-gray-800 transition duration-150">
                                        <td class="px-5 py-4 text-sm">
                                            @if($vehicle->image_path)
                                                <div class="h-10 w-10 flex-shrink-0">
                                                    <img class="h-10 w-10 rounded-full object-cover border border-gray-600"
                                                        src="{{ Storage::url($vehicle->image_path) }}"
                                                        alt="{{ $vehicle->plate }}">
                                                </div>
                                            @else
                                                <div
                                                    class="h-10 w-10 rounded-full bg-gray-700 flex items-center justify-center text-xs font-bold text-gray-400 border border-gray-600">
                                                    N/A
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-5 py-4 text-sm font-bold">
                                            {{ $vehicle->plate }}
                                        </td>
                                        <td class="px-5 py-4 text-sm">
                                            {{ $vehicle->brand }} {{ $vehicle->model }}
                                        </td>
                                        <td class="px-5 py-4 text-sm">
                                            {{ $vehicle->year }}
                                        </td>
                                        <td class="px-5 py-4 text-sm">
                                            @php
                                                $statusClasses = [
                                                    'available' => 'text-green-400 bg-green-900/30 border border-green-900',
                                                    'workshop' => 'text-red-400 bg-red-900/30 border border-red-900',
                                                    'maintenance' => 'text-yellow-400 bg-yellow-900/30 border border-yellow-900',
                                                ];
                                                $statusLabel = [
                                                    'available' => 'DISPONIBLE',
                                                    'workshop' => 'EN TALLER',
                                                    'maintenance' => 'MANTENIMIENTO',
                                                ];
                                            @endphp
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-md {{ $statusClasses[$vehicle->status] ?? 'text-gray-400 bg-gray-800' }}">
                                                {{ $statusLabel[$vehicle->status] ?? strtoupper($vehicle->status) }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 text-sm">
                                            <div class="flex items-center">
                                                <p class="text-gray-900 dark:text-gray-100 whitespace-no-wrap mr-2">
                                                    {{ number_format($vehicle->mileage, 0, '', '.') }} km
                                                </p>
                                                @if($vehicle->currentMaintenanceState && $vehicle->currentMaintenanceState->next_oil_change_km && $vehicle->mileage >= $vehicle->currentMaintenanceState->next_oil_change_km)
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 animate-pulse">
                                                        ⚠️ MANTENCIÓN
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 text-sm font-medium">
                                            <div class="flex items-center space-x-4">
                                                <button @click="
                                                                maintenanceVehicle = {
                                                                    id: {{ $vehicle->id }},
                                                                    status: '{{ $vehicle->status }}',
                                                                    updateStateAction: '{{ route('vehicles.maintenance.state', $vehicle) }}',
                                                                    storeRequestAction: '{{ route('vehicles.maintenance.request', $vehicle) }}',
                                                                    completeAction: '{{ route('vehicles.maintenance.complete', $vehicle) }}',
                                                                    last_oil_change_km: '{{ isset($vehicle->currentMaintenanceState->last_oil_change_km) ? number_format($vehicle->currentMaintenanceState->last_oil_change_km, 0, '', '.') : '' }}',
                                                                    next_oil_change_km: '{{ isset($vehicle->currentMaintenanceState->next_oil_change_km) ? number_format($vehicle->currentMaintenanceState->next_oil_change_km, 0, '', '.') : '' }}',
                                                                    tire_status_front: '{{ $vehicle->currentMaintenanceState->tire_status_front ?? 'good' }}',
                                                                    tire_status_rear: '{{ $vehicle->currentMaintenanceState->tire_status_rear ?? 'good' }}',
                                                                    last_service_date: '{{ $vehicle->currentMaintenanceState->last_service_date ?? '' }}',
                                                                    oil_change_due: {{ ($vehicle->currentMaintenanceState && $vehicle->currentMaintenanceState->next_oil_change_km && $vehicle->mileage >= $vehicle->currentMaintenanceState->next_oil_change_km) ? 'true' : 'false' }}
                                                                };
                                                                $dispatch('open-modal', 'maintenance-vehicle-modal');
                                                            "
                                                    class="{{ ($vehicle->currentMaintenanceState && $vehicle->currentMaintenanceState->next_oil_change_km && $vehicle->mileage >= $vehicle->currentMaintenanceState->next_oil_change_km) ? 'text-red-500 hover:text-red-400 animate-pulse' : 'text-yellow-400 hover:text-yellow-300' }} transition duration-150"
                                                    title="Mantenimiento">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                </button>
                                                <button @click="
                                                                                                        viewingVehicle = {
                                                                                                            plate: '{{ $vehicle->plate }}',
                                                                                                            brand: '{{ $vehicle->brand }}',
                                                                                                            model: '{{ $vehicle->model }}',
                                                                                                            year: {{ $vehicle->year }},
                                                                                                            mileage: {{ $vehicle->mileage }},
                                                                                                            status: '{{ $vehicle->status }}',
                                                                                                            imageUrl: '{{ $vehicle->image_path ? Storage::url($vehicle->image_path) : '' }}'
                                                                                                        };
                                                                                                        $dispatch('open-modal', 'view-vehicle-modal');
                                                                                                    "
                                                    class="text-green-400 hover:text-green-300 transition duration-150"
                                                    title="Ver Detalle">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                </button>
                                                <button @click="
                                                                                                                editingVehicle = {
                                                                                                                    id: {{ $vehicle->id }},
                                                                                                                    plate: '{{ $vehicle->plate }}',
                                                                                                                    brand: '{{ $vehicle->brand }}',
                                                                                                                    model: '{{ $vehicle->model }}',
                                                                                                                    year: {{ $vehicle->year }},
                                                                                                                    mileage: {{ $vehicle->mileage }},
                                                                                                                    status: '{{ $vehicle->status }}'
                                                                                                                };
                                                                                                                editAction = '{{ route('vehicles.update', $vehicle) }}';
                                                                                                                $dispatch('open-modal', 'edit-vehicle-modal');
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
                                                    <button
                                                        @click="$dispatch('open-modal', 'confirm-delete-modal'); deleteAction = '{{ route('vehicles.destroy', $vehicle) }}'"
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
                                        <td colspan="6" class="px-5 py-5 text-sm text-center text-gray-500">
                                            No hay vehículos registrados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Agregar Vehículo -->
        <x-modal name="create-vehicle-modal" :show="$errors->any()" focusable>
            <form method="POST" action="{{ route('vehicles.store') }}" class="p-6 bg-gray-800 text-gray-100"
                enctype="multipart/form-data">
                @csrf

                <h2 class="text-lg font-medium text-gray-100 mb-4">
                    {{ __('Nuevo Vehículo') }}
                </h2>

                <!-- Foto -->
                <div class="mb-4">
                    <x-input-label for="image" :value="__('Foto del Vehículo')" class="text-gray-300" />
                    <input id="image" type="file" name="image" class="mt-1 block w-full text-sm text-gray-400
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-600 file:text-white
                        hover:file:bg-blue-700
                        cursor-pointer focus:outline-none" accept="image/*" />
                    <x-input-error :messages="$errors->get('image')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Patente -->
                    <div>
                        <x-input-label for="plate" :value="__('Patente')" class="text-gray-300" />
                        <x-text-input id="plate"
                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                            type="text" name="plate" :value="old('plate')" required autofocus
                            placeholder="Ej: AB123CD" />
                        <x-input-error :messages="$errors->get('plate')" class="mt-2" />
                    </div>

                    <!-- Marca -->
                    <div>
                        <x-input-label for="brand" :value="__('Marca')" class="text-gray-300" />
                        <x-text-input id="brand"
                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                            type="text" name="brand" :value="old('brand')" required placeholder="Toyota" />
                        <x-input-error :messages="$errors->get('brand')" class="mt-2" />
                    </div>

                    <!-- Modelo -->
                    <div>
                        <x-input-label for="model" :value="__('Modelo')" class="text-gray-300" />
                        <x-text-input id="model"
                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                            type="text" name="model" :value="old('model')" required placeholder="Hilux" />
                        <x-input-error :messages="$errors->get('model')" class="mt-2" />
                    </div>

                    <!-- Año -->
                    <div>
                        <x-input-label for="year" :value="__('Año')" class="text-gray-300" />
                        <x-text-input id="year"
                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                            type="number" name="year" :value="old('year')" required placeholder="2023" />
                        <x-input-error :messages="$errors->get('year')" class="mt-2" />
                    </div>

                    <!-- Kilometraje -->
                    <div class="md:col-span-2">
                        <x-input-label for="mileage" :value="__('Kilometraje')" class="text-gray-300" />
                        <x-text-input id="mileage"
                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                            type="number" name="mileage" :value="old('mileage')" required placeholder="0" />
                        <x-input-error :messages="$errors->get('mileage')" class="mt-2" />
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <x-secondary-button x-on:click="$dispatch('close')"
                        class="bg-gray-700 text-gray-300 hover:bg-gray-600 border-gray-600">
                        {{ __('Cancelar') }}
                    </x-secondary-button>

                    <x-primary-button class="bg-blue-600 hover:bg-blue-700 border-transparent">
                        {{ __('Guardar Vehículo') }}
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
                    {{ __('El vehículo se moverá a la papelera. Podrás restaurarlo después si lo necesitas.') }}
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

        <!-- Modal Editar Vehículo -->
        <x-modal name="edit-vehicle-modal" :show="false" focusable>
            <form method="POST" :action="editAction" enctype="multipart/form-data"
                class="p-6 bg-gray-800 text-gray-100">
                @csrf
                @method('PUT')

                <h2 class="text-lg font-medium text-gray-100 mb-4">
                    {{ __('Editar Vehículo') }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Patente -->
                    <div>
                        <x-input-label for="edit_plate" :value="__('Patente')" class="text-gray-300" />
                        <x-text-input id="edit_plate"
                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                            type="text" name="plate" x-model="editingVehicle.plate" required placeholder="AA123BB" />
                        <x-input-error :messages="$errors->get('plate')" class="mt-2" />
                    </div>

                    <!-- Marca -->
                    <div>
                        <x-input-label for="edit_brand" :value="__('Marca')" class="text-gray-300" />
                        <x-text-input id="edit_brand"
                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                            type="text" name="brand" x-model="editingVehicle.brand" required placeholder="Toyota" />
                        <x-input-error :messages="$errors->get('brand')" class="mt-2" />
                    </div>

                    <!-- Modelo -->
                    <div>
                        <x-input-label for="edit_model" :value="__('Modelo')" class="text-gray-300" />
                        <x-text-input id="edit_model"
                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                            type="text" name="model" x-model="editingVehicle.model" required placeholder="Hilux" />
                        <x-input-error :messages="$errors->get('model')" class="mt-2" />
                    </div>

                    <!-- Año -->
                    <div>
                        <x-input-label for="edit_year" :value="__('Año')" class="text-gray-300" />
                        <x-text-input id="edit_year"
                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                            type="number" name="year" x-model="editingVehicle.year" required placeholder="2023" />
                        <x-input-error :messages="$errors->get('year')" class="mt-2" />
                    </div>

                    <!-- Kilometraje -->
                    <div class="md:col-span-2">
                        <x-input-label for="edit_mileage" :value="__('Kilometraje')" class="text-gray-300" />
                        <x-text-input id="edit_mileage"
                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                            type="number" name="mileage" x-model="editingVehicle.mileage" required placeholder="0" />
                        <x-input-error :messages="$errors->get('mileage')" class="mt-2" />
                    </div>

                    <!-- Estado -->
                    <div class="md:col-span-2">
                        <x-input-label for="edit_status" :value="__('Estado')" class="text-gray-300" />
                        <select id="edit_status" name="status" x-model="editingVehicle.status"
                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                            <option value="available">{{ __('Disponible') }}</option>
                            <option value="workshop">{{ __('En Taller') }}</option>
                            <option value="maintenance">{{ __('En Mantenimiento') }}</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>

                    <!-- Foto (Opcional) -->
                    <div class="md:col-span-2">
                        <x-input-label for="edit_image" :value="__('Actualizar Foto (Opcional)')"
                            class="text-gray-300" />
                        <input id="edit_image" type="file" name="image" accept="image/*"
                            class="block w-full text-sm text-gray-400
                             file:mr-4 file:py-2 file:px-4
                             file:rounded-md file:border-0
                             file:text-sm file:font-semibold
                             file:bg-blue-600 file:text-white
                             hover:file:bg-blue-700
                             cursor-pointer bg-gray-900 border border-gray-700 rounded-md focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
                        <p class="mt-1 text-xs text-gray-500">Dejar vacío para mantener la actual.</p>
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <x-secondary-button @click="$dispatch('close')"
                        class="bg-gray-700 text-gray-300 hover:bg-gray-600 border-gray-600">
                        {{ __('Cancelar') }}
                    </x-secondary-button>

                    <x-primary-button class="bg-blue-600 hover:bg-blue-700 border-transparent">
                        {{ __('Actualizar Vehículo') }}
                    </x-primary-button>
                </div>
            </form>
        </x-modal>

        <!-- Modal Ver Detalle Vehículo -->
        <x-modal name="view-vehicle-modal" :show="false" focusable>
            <div class="p-6 bg-gray-800 text-gray-100">
                <h2 class="text-xl font-bold text-gray-100 mb-6 border-b border-gray-700 pb-2">
                    {{ __('Detalle del Vehículo') }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Foto Grande -->
                    <div
                        class="flex flex-col items-center justify-center bg-gray-900 rounded-lg p-4 border border-gray-700">
                        <template x-if="viewingVehicle.imageUrl">
                            <img :src="viewingVehicle.imageUrl" alt="Foto Vehículo"
                                class="w-full h-64 object-cover rounded-md shadow-lg">
                        </template>
                        <template x-if="!viewingVehicle.imageUrl">
                            <div
                                class="w-full h-64 flex items-center justify-center bg-gray-800 text-gray-500 rounded-md">
                                <span class="text-sm">Sin imagen disponible</span>
                            </div>
                        </template>
                    </div>

                    <!-- Datos -->
                    <div class="space-y-4">
                        <div>
                            <span class="block text-xs text-gray-400 uppercase tracking-widest">Patente</span>
                            <span class="text-2xl font-bold text-white tracking-wider"
                                x-text="viewingVehicle.plate"></span>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="block text-xs text-gray-400 uppercase tracking-widest">Marca</span>
                                <span class="text-lg text-gray-200" x-text="viewingVehicle.brand"></span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-400 uppercase tracking-widest">Modelo</span>
                                <span class="text-lg text-gray-200" x-text="viewingVehicle.model"></span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="block text-xs text-gray-400 uppercase tracking-widest">Año</span>
                                <span class="text-lg text-gray-200" x-text="viewingVehicle.year"></span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-400 uppercase tracking-widest">Kilometraje</span>
                                <span class="text-lg text-gray-200" x-text="viewingVehicle.mileage + ' km'"></span>
                            </div>
                        </div>

                        <div>
                            <span class="block text-xs text-gray-400 uppercase tracking-widest mb-1">Estado</span>
                            <span
                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-gray-700 text-gray-200"
                                x-text="viewingVehicle.status === 'available' ? 'DISPONIBLE' : (viewingVehicle.status === 'workshop' ? 'EN TALLER' : 'MANTENIMIENTO')">
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <x-secondary-button @click="$dispatch('close')"
                        class="bg-gray-700 text-gray-300 hover:bg-gray-600 border-gray-600 w-full md:w-auto justify-center">
                        {{ __('Cerrar') }}
                    </x-secondary-button>
                </div>
            </div>
        </x-modal>

        <!-- Modal Mantenimiento -->
        <x-modal name="maintenance-vehicle-modal" :show="false" focusable>
            <div class="p-6 bg-gray-800 text-gray-100" x-data="{ tab: 'status' }">
                <h2 class="text-xl font-bold text-gray-100 mb-6 border-b border-gray-700 pb-2">
                    {{ __('Gestión de Mantenimiento') }}
                </h2>

                <!-- Tabs Navigation -->
                <div class="flex space-x-4 mb-6 border-b border-gray-700">
                    <button @click="tab = 'status'"
                        :class="{ 'border-b-2 border-blue-500 text-blue-400': tab === 'status', 'text-gray-400 hover:text-gray-200': tab !== 'status' }"
                        class="pb-2 text-sm font-medium transition-colors duration-150">
                        Estado Técnico
                    </button>
                    <button @click="tab = 'request'"
                        :class="{ 'border-b-2 border-blue-500 text-blue-400': tab === 'request', 'text-gray-400 hover:text-gray-200': tab !== 'request' }"
                        class="pb-2 text-sm font-medium transition-colors duration-150">
                        Solicitar Mantención
                    </button>
                </div>

                <!-- Tab: Estado Técnico -->
                <div x-show="tab === 'status'">
                    <form id="update-maintenance-state-form" method="POST" :action="maintenanceVehicle.updateStateAction">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-300 mb-4">Aceite y Servicios</h3>
                                <div class="space-y-4">
                                    <div>
                                        <x-input-label for="last_oil_change" :value="__('Último Cambio Aceite (km)')"
                                            class="text-gray-400" />
                                        <x-text-input id="last_oil_change"
                                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100"
                                            type="text" name="last_oil_change_km"
                                            x-model="maintenanceVehicle.last_oil_change_km"
                                            x-on:input="$el.value = $el.value.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')"
                                            placeholder="0" />
                                    </div>
                                    <div>
                                        <x-input-label for="next_oil_change" :value="__('Próximo Cambio (km)')"
                                            class="text-gray-400"
                                            ::class="{ 'text-red-500 font-bold': maintenanceVehicle.oil_change_due }" />
                                        <div class="relative">
                                            <x-text-input id="next_oil_change"
                                                class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100"
                                                ::class="{ 'border-red-500 ring-1 ring-red-500': maintenanceVehicle.oil_change_due }"
                                                type="text" name="next_oil_change_km"
                                                x-model="maintenanceVehicle.next_oil_change_km"
                                                x-on:input="$el.value = $el.value.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')"
                                                placeholder="10.000" />
                                            <template x-if="maintenanceVehicle.oil_change_due">
                                                <span
                                                    class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                    <svg class="h-5 w-5 text-red-500" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </template>
                                        </div>
                                    </div>
                                    <div>
                                        <x-input-label for="last_service_date" :value="__('Fecha Última Revisión')"
                                            class="text-gray-400" />
                                        <x-text-input id="last_service_date"
                                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100"
                                            type="date" name="last_service_date"
                                            x-model="maintenanceVehicle.last_service_date" />
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-sm font-semibold text-gray-300 mb-4">Estado Neumáticos</h3>
                                <div class="space-y-4">
                                    <div>
                                        <x-input-label for="tire_front" :value="__('Delanteros')"
                                            class="text-gray-400" />
                                        <select id="tire_front" name="tire_status_front"
                                            x-model="maintenanceVehicle.tire_status_front"
                                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="good">🟢 Bueno</option>
                                            <option value="fair">🟡 Regular</option>
                                            <option value="poor">🔴 Malo (Cambiar)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <x-input-label for="tire_rear" :value="__('Traseros')" class="text-gray-400" />
                                        <select id="tire_rear" name="tire_status_rear"
                                            x-model="maintenanceVehicle.tire_status_rear"
                                            class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="good">🟢 Bueno</option>
                                            <option value="fair">🟡 Regular</option>
                                            <option value="poor">🔴 Malo (Cambiar)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="mt-8 flex justify-end space-x-3">
                        <form x-show="maintenanceVehicle.status === 'maintenance' || maintenanceVehicle.status === 'workshop'" 
                            method="POST" :action="maintenanceVehicle.completeAction" class="mr-auto">
                            @csrf
                            <x-primary-button class="bg-green-600 hover:bg-green-700 border-transparent">
                                ✅ {{ __('Finalizar Mantenimiento') }}
                            </x-primary-button>
                        </form>

                        <x-secondary-button @click="$dispatch('close')"
                            class="bg-gray-700 text-gray-300 hover:bg-gray-600 border-gray-600">
                            {{ __('Cancelar') }}
                        </x-secondary-button>
                        <x-primary-button form="update-maintenance-state-form" class="bg-blue-600 hover:bg-blue-700 border-transparent">
                            {{ __('Actualizar Estado') }}
                        </x-primary-button>
                    </div>
                </div>

                <!-- Tab: Solicitar Mantención -->
                <div x-show="tab === 'request'">
                    <form method="POST" :action="maintenanceVehicle.storeRequestAction">
                        @csrf
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <x-input-label for="req_type" :value="__('Tipo de Solicitud')" class="text-gray-400" />
                                <select id="req_type" name="type" required
                                    class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="oil">🛢️ Cambio de Aceite</option>
                                    <option value="tires">🛞 Cambio de Neumáticos</option>
                                    <option value="mechanics">🔧 Mecánica General</option>
                                    <option value="general">📋 Otro / Inspección</option>
                                </select>
                            </div>

                            <div>
                                <x-input-label for="req_desc" :value="__('Descripción Detallada')"
                                    class="text-gray-400" />
                                <textarea id="req_desc" name="description" rows="4" required
                                    class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Describe el problema o los detalles del servicio requerido..."></textarea>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end space-x-3">
                            <x-secondary-button @click="$dispatch('close')"
                                class="bg-gray-700 text-gray-300 hover:bg-gray-600 border-gray-600">
                                {{ __('Cancelar') }}
                            </x-secondary-button>
                            <x-primary-button class="bg-yellow-600 hover:bg-yellow-700 border-transparent">
                                {{ __('Enviar Solicitud') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </x-modal>
        <!-- Modal Solicitudes Pendientes -->
        <x-modal name="maintenance-requests-modal" :show="false" focusable>
            <div class="p-6 bg-gray-800 text-gray-100">
                <h2 class="text-lg font-medium text-gray-100 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    {{ __('Solicitudes Pendientes') }}
                </h2>

                @if($pendingRequests->isEmpty())
                    <p class="text-gray-400 text-center py-8">No hay solicitudes pendientes.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 bg-gray-900 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Vehículo</th>
                                    <th class="px-4 py-3 bg-gray-900 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Tipo</th>
                                    <th class="px-4 py-3 bg-gray-900 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Descripción</th>
                                    <th class="px-4 py-3 bg-gray-900 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-800 divide-y divide-gray-700">
                                @foreach($pendingRequests as $req)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-100">
                                            {{ $req->vehicle->brand }} {{ $req->vehicle->model }} <br>
                                            <span class="text-xs text-gray-500">{{ $req->vehicle->plate }}</span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-300">
                                            @switch($req->type)
                                                @case('oil') <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Aceite</span> @break
                                                @case('tires') <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Neumáticos</span> @break
                                                @case('mechanics') <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Mecánica</span> @break
                                                @default <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">General</span>
                                            @endswitch
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-300 max-w-xs truncate" title="{{ $req->description }}">
                                            {{ $req->description }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                            <form method="POST" action="/maintenance/requests/{{ $req->id }}/accept">
                                                @csrf
                                                <button type="submit" class="text-green-500 hover:text-green-400 font-bold hover:underline">
                                                    ACEPTAR
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <div class="mt-6 flex justify-end">
                    <x-secondary-button @click="$dispatch('close')" class="bg-gray-700 text-gray-300 hover:bg-gray-600 border-gray-600">
                        {{ __('Cerrar') }}
                    </x-secondary-button>
                </div>
            </div>
        </x-modal>
    </div>
</x-app-layout>