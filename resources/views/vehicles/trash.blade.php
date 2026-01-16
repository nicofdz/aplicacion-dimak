<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Papelera de Vehículos') }}
            </h2>
            <a href="{{ route('vehicles.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md text-xs font-bold uppercase tracking-widest transition">
                {{ __('Volver a la Lista') }}
            </a>
        </div>
    </x-slot>

    <div x-data="{ deleteAction: '' }">
        <div class="py-12">
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
                                            Patente</th>
                                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                            Marca
                                            / Modelo</th>
                                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                            Eliminado</th>
                                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                            Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-700 bg-gray-900 text-gray-300">
                                    @forelse($vehicles as $vehicle)
                                        <tr class="hover:bg-gray-800 transition duration-150 opacity-75">
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
                                                        N/A</div>
                                                @endif
                                            </td>
                                            <td class="px-5 py-4 text-sm font-bold">{{ $vehicle->plate }}</td>
                                            <td class="px-5 py-4 text-sm">{{ $vehicle->brand }} {{ $vehicle->model }}</td>
                                            <td class="px-5 py-4 text-sm">{{ $vehicle->deleted_at->diffForHumans() }}</td>
                                            <td class="px-5 py-4 text-sm font-medium">
                                                <div class="flex items-center space-x-4">
                                                    <!-- Restore -->
                                                    <form action="{{ route('vehicles.restore', $vehicle->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit"
                                                            class="text-green-400 hover:text-green-300 transition duration-150"
                                                            title="Restaurar">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                    </form>

                                                    <!-- Force Delete -->
                                                    <button
                                                        @click="$dispatch('open-modal', 'confirm-delete-modal'); deleteAction = '{{ route('vehicles.force-delete', $vehicle->id) }}'"
                                                        class="text-red-500 hover:text-red-400 transition duration-150"
                                                        title="Eliminar Permanentemente">
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
                                            <td colspan="5" class="px-5 py-5 text-sm text-center text-gray-500">
                                                La papelera está vacía.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Confirmación Eliminar Permanente -->
        <x-modal name="confirm-delete-modal" :show="false" focusable>
            <div class="p-6 bg-gray-800 text-gray-100">
                <h2 class="text-lg font-medium text-red-400">
                    {{ __('¿Eliminar permanentemente?') }}
                </h2>

                <p class="mt-1 text-sm text-gray-400">
                    {{ __('Esta acción NO se puede deshacer. Se eliminará el vehículo y su foto del sistema.') }}
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
                            {{ __('Eliminar Definitivamente') }}
                        </x-danger-button>
                    </form>
                </div>
            </div>
        </x-modal>
    </div>
</x-app-layout>