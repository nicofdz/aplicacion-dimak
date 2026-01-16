<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Agregar Nuevo Vehículo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('vehicles.store') }}">
                        @csrf

                        <!-- Patente -->
                        <div>
                            <x-input-label for="plate" :value="__('Patente')" />
                            <x-text-input id="plate" class="block mt-1 w-full" type="text" name="plate"
                                :value="old('plate')" required autofocus />
                            <x-input-error :messages="$errors->get('plate')" class="mt-2" />
                        </div>

                        <!-- Marca -->
                        <div class="mt-4">
                            <x-input-label for="brand" :value="__('Marca')" />
                            <x-text-input id="brand" class="block mt-1 w-full" type="text" name="brand"
                                :value="old('brand')" required />
                            <x-input-error :messages="$errors->get('brand')" class="mt-2" />
                        </div>

                        <!-- Modelo -->
                        <div class="mt-4">
                            <x-input-label for="model" :value="__('Modelo')" />
                            <x-text-input id="model" class="block mt-1 w-full" type="text" name="model"
                                :value="old('model')" required />
                            <x-input-error :messages="$errors->get('model')" class="mt-2" />
                        </div>

                        <!-- Año -->
                        <div class="mt-4">
                            <x-input-label for="year" :value="__('Año')" />
                            <x-text-input id="year" class="block mt-1 w-full" type="number" name="year"
                                :value="old('year')" required />
                            <x-input-error :messages="$errors->get('year')" class="mt-2" />
                        </div>

                        <!-- Kilometraje -->
                        <div class="mt-4">
                            <x-input-label for="mileage" :value="__('Kilometraje')" />
                            <x-text-input id="mileage" class="block mt-1 w-full" type="number" name="mileage"
                                :value="old('mileage')" required />
                            <x-input-error :messages="$errors->get('mileage')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('vehicles.index') }}"
                                class="mr-4 text-gray-600 hover:text-gray-900">Cancelar</a>
                            <x-primary-button class="ml-4">
                                {{ __('Guardar Vehículo') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>