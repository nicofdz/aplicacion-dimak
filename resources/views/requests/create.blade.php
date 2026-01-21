<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Solicitar Vehículo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">



                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('requests.store') }}" class="space-y-6">
                        @csrf

                        <div x-data="{ 
                            open: false, 
                            selectedId: '{{ old('vehicle_id') }}', 
                            vehicles: {{ $vehicles->map(function ($vehicle) {
    return [
        'id' => $vehicle->id,
        'label' => $vehicle->brand . ' ' . $vehicle->model . ' (' . $vehicle->plate . ')',
        'image' => $vehicle->image_path ? asset('storage/' . $vehicle->image_path) : null,
    ];
})->toJson() }},
                            get selected() {
                                return this.vehicles.find(v => v.id == this.selectedId) || null;
                            }
                        }" class="relative">
                            <x-input-label for="vehicle_id" :value="__('Seleccionar Vehículo')" class="mb-1" />

                            <!-- Hidden Input -->
                            <input type="hidden" name="vehicle_id" :value="selectedId">

                            <!-- Trigger Button -->
                            <button @click="open = !open" @click.away="open = false" type="button"
                                class="relative w-full cursor-default rounded-md bg-white dark:bg-gray-900 py-3 pl-3 pr-10 text-left text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm sm:leading-6"
                                aria-haspopup="listbox" :aria-expanded="open" aria-labelledby="listbox-label">
                                <span class="flex items-center">
                                    <!-- Selected Image -->
                                    <template x-if="selected && selected.image">
                                        <img :src="selected.image" alt=""
                                            class="h-10 w-10 flex-shrink-0 rounded-full object-cover">
                                    </template>
                                    <template x-if="selected && !selected.image">
                                        <div
                                            class="h-10 w-10 flex-shrink-0 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                            </svg>
                                        </div>
                                    </template>

                                    <span class="ml-3 block truncate"
                                        x-text="selected ? selected.label : '-- Seleccione una camioneta --'"></span>
                                </span>
                                <span
                                    class="pointer-events-none absolute inset-y-0 right-0 ml-3 flex items-center pr-2">
                                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                                        aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </button>

                            <!-- Dropdown List -->
                            <ul x-show="open" x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class="absolute z-10 mt-1 max-h-56 w-full overflow-auto rounded-md bg-white dark:bg-gray-800 py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                                tabindex="-1" role="listbox" aria-labelledby="listbox-label">

                                <template x-for="vehicle in vehicles" :key="vehicle.id">
                                    <li class="relative cursor-default select-none py-2 pl-3 pr-9 text-gray-900 dark:text-gray-100 hover:bg-indigo-600 hover:text-white"
                                        id="listbox-option-0" role="option"
                                        @click="selectedId = vehicle.id; open = false">
                                        <div class="flex items-center">
                                            <!-- Option Image -->
                                            <template x-if="vehicle.image">
                                                <img :src="vehicle.image" alt=""
                                                    class="h-12 w-12 flex-shrink-0 rounded-full object-cover border border-gray-200 dark:border-gray-600">
                                            </template>
                                            <template x-if="!vehicle.image">
                                                <div
                                                    class="h-12 w-12 flex-shrink-0 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                                                    <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                    </svg>
                                                </div>
                                            </template>

                                            <!-- Option Text -->
                                            <span class="ml-3 block font-normal truncate" x-text="vehicle.label"
                                                :class="{ 'font-semibold': selectedId == vehicle.id, 'font-normal': selectedId != vehicle.id }">
                                            </span>
                                        </div>

                                        <!-- Checkmark for selected item -->
                                        <span x-show="selectedId == vehicle.id"
                                            class="absolute inset-y-0 right-0 flex items-center pr-4 text-indigo-600 hover:text-white">
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"
                                                aria-hidden="true">
                                                <path fill-rule="evenodd"
                                                    d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </li>
                                </template>
                            </ul>
                        </div>

                        <!-- Tipo de Destino -->
                        <div class="mt-4">
                            <x-input-label for="destination_type" :value="__('Tipo de Destino')" class="mb-1" />
                            <select id="destination_type" name="destination_type"
                                class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                required>
                                <option value="" disabled selected>-- Seleccione Tipo de Viaje --</option>
                                <option value="local">🏙️ Local (Dentro de la ciudad)</option>
                                <option value="outside">🛣️ Fuera de la ciudad</option>
                            </select>
                            <x-input-error :messages="$errors->get('destination_type')" class="mt-2" />
                        </div>

                        @if(Auth::user()->role === 'admin')
                                                <div x-data="{ isThirdParty: false, conductorDropdown: false, selectedConductorId: '', conductors: {{ $conductors->map(function ($conductor) {
                                return [
                                    'id' => $conductor->id,
                                    'label' => $conductor->nombre . ' (' . ($conductor->cargo ?? 'N/A') . ')',
                                    'image' => $conductor->fotografia ? asset('storage/' . $conductor->fotografia) : null,
                                ];
                            })->toJson() }},
                                                        get selectedConductor() {
                                                            return this.conductors.find(c => c.id == this.selectedConductorId) || null;
                                                        }
                                                    }" class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
                                                    <label class="inline-flex items-center cursor-pointer">
                                                        <input type="checkbox" name="is_third_party" x-model="isThirdParty" value="1"
                                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400 font-bold">Esta solicitud es
                                                            para un tercero (Conductor externo)</span>
                                                    </label>

                                                    <div x-show="isThirdParty" class="mt-4 animate-fade-in-down">
                                                        <div class="relative">
                                                            <x-input-label for="conductor_id" :value="__('Seleccionar Conductor')"
                                                                class="mb-1" />

                                                            <!-- Hidden Input -->
                                                            <input type="hidden" name="conductor_id" :value="selectedConductorId">

                                                            <!-- Trigger Button -->
                                                            <button @click="conductorDropdown = !conductorDropdown"
                                                                @click.away="conductorDropdown = false" type="button"
                                                                class="relative w-full cursor-default rounded-md bg-white dark:bg-gray-900 py-3 pl-3 pr-10 text-left text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm sm:leading-6"
                                                                aria-haspopup="listbox" :aria-expanded="conductorDropdown">
                                                                <span class="flex items-center">
                                                                    <!-- Selected Image -->
                                                                    <template x-if="selectedConductor && selectedConductor.image">
                                                                        <img :src="selectedConductor.image" alt=""
                                                                            class="h-10 w-10 flex-shrink-0 rounded-full object-cover border border-gray-300 dark:border-gray-600">
                                                                    </template>
                                                                    <template x-if="selectedConductor && !selectedConductor.image">
                                                                        <div
                                                                            class="h-10 w-10 flex-shrink-0 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center border border-gray-300 dark:border-gray-600">
                                                                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                                                stroke="currentColor">
                                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                            </svg>
                                                                        </div>
                                                                    </template>

                                                                    <span class="ml-3 block truncate"
                                                                        x-text="selectedConductor ? selectedConductor.label : '-- Seleccione un Conductor --'"></span>
                                                                </span>
                                                                <span
                                                                    class="pointer-events-none absolute inset-y-0 right-0 ml-3 flex items-center pr-2">
                                                                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                                                                        aria-hidden="true">
                                                                        <path fill-rule="evenodd"
                                                                            d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                                            clip-rule="evenodd" />
                                                                    </svg>
                                                                </span>
                                                            </button>

                                                            <!-- Dropdown List -->
                                                            <ul x-show="conductorDropdown" x-transition:leave="transition ease-in duration-100"
                                                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                                                class="absolute z-10 mt-1 max-h-56 w-full overflow-auto rounded-md bg-white dark:bg-gray-800 py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                                                                tabindex="-1" role="listbox">

                                                                <template x-for="conductor in conductors" :key="conductor.id">
                                                                    <li class="relative cursor-default select-none py-2 pl-3 pr-9 text-gray-900 dark:text-gray-100 hover:bg-indigo-600 hover:text-white"
                                                                        role="option"
                                                                        @click="selectedConductorId = conductor.id; conductorDropdown = false">
                                                                        <div class="flex items-center">
                                                                            <!-- Option Image -->
                                                                            <template x-if="conductor.image">
                                                                                <img :src="conductor.image" alt=""
                                                                                    class="h-12 w-12 flex-shrink-0 rounded-full object-cover border border-gray-200 dark:border-gray-600">
                                                                            </template>
                                                                            <template x-if="!conductor.image">
                                                                                <div
                                                                                    class="h-12 w-12 flex-shrink-0 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                                                                                    <svg class="h-6 w-6 text-gray-400" fill="none"
                                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                                            stroke-width="2"
                                                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                                    </svg>
                                                                                </div>
                                                                            </template>

                                                                            <!-- Option Text -->
                                                                            <span class="ml-3 block font-normal truncate"
                                                                                x-text="conductor.label"
                                                                                :class="{ 'font-semibold': selectedConductorId == conductor.id, 'font-normal': selectedConductorId != conductor.id }">
                                                                            </span>
                                                                        </div>

                                                                        <!-- Checkmark for selected item -->
                                                                        <span x-show="selectedConductorId == conductor.id"
                                                                            class="absolute inset-y-0 right-0 flex items-center pr-4 text-indigo-600 hover:text-white">
                                                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"
                                                                                aria-hidden="true">
                                                                                <path fill-rule="evenodd"
                                                                                    d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                                                                    clip-rule="evenodd" />
                                                                            </svg>
                                                                        </span>
                                                                    </li>
                                                                </template>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="start_date" :value="__('Fecha y Hora Inicio')" />
                                <x-text-input id="start_date" class="block mt-1 w-full" type="datetime-local"
                                    name="start_date" :value="old('start_date')" required />
                            </div>

                            <div>
                                <x-input-label for="end_date" :value="__('Fecha y Hora Fin')" />
                                <x-text-input id="end_date" class="block mt-1 w-full" type="datetime-local"
                                    name="end_date" :value="old('end_date')" required />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Enviar Solicitud') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>