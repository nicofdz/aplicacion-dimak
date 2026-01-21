<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Papelera - Historial de Entregas') }}
            </h2>
            <a href="{{ route('admin.returns.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                {{ __('Volver al Historial') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    @if($returns->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha Eliminación</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Vehículo</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Usuario</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($returns as $return)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $return->deleted_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                <div class="flex items-center">
                                                    @if($return->request->vehicle && $return->request->vehicle->image_path)
                                                        <div class="flex-shrink-0 h-10 w-10">
                                                            <img class="h-10 w-10 rounded-full object-cover" 
                                                                 src="{{ Storage::url($return->request->vehicle->image_path) }}" 
                                                                 alt="{{ $return->request->vehicle->plate }}">
                                                        </div>
                                                    @else
                                                        <div class="flex-shrink-0 h-10 w-10 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                        </div>
                                                    @endif
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                            @if($return->request->vehicle)
                                                                {{ $return->request->vehicle->brand }} {{ $return->request->vehicle->model }}
                                                            @else
                                                                <span class="text-red-500 italic">Vehículo Eliminado</span>
                                                            @endif
                                                        </div>
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">
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
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium" x-data>
                                                <div class="flex justify-end space-x-3">
                                                    <button @click="$dispatch('open-modal', 'restore-modal-{{ $return->id }}')" 
                                                            class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 font-bold" title="Restaurar">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                        </svg>
                                                    </button>
                                                    
                                                    <button @click="$dispatch('open-modal', 'force-delete-modal-{{ $return->id }}')" 
                                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 font-bold" title="Eliminar Permanentemente">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </div>

                                                <!-- Restore Modal -->
                                                <template x-teleport="body">
                                                    <x-modal name="restore-modal-{{ $return->id }}" :show="false" focusable>
                                                        <form method="POST" action="{{ route('admin.returns.restore', $return->id) }}" class="p-6">
                                                            @csrf
                                                            @method('PUT')
                                                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                                                {{ __('¿Restaurar esta entrega?') }}
                                                            </h2>
                                                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 whitespace-normal">
                                                                {{ __('La entrega volverá a estar visible en el historial principal.') }}
                                                            </p>
                                                            <div class="mt-6 flex justify-end">
                                                                <x-secondary-button x-on:click="$dispatch('close')">
                                                                    {{ __('Cancelar') }}
                                                                </x-secondary-button>
                                                                <x-primary-button class="ml-3 bg-green-600 hover:bg-green-700">
                                                                    {{ __('Restaurar') }}
                                                                </x-primary-button>
                                                            </div>
                                                        </form>
                                                    </x-modal>
                                                </template>

                                                <!-- Force Delete Modal -->
                                                <template x-teleport="body">
                                                    <x-modal name="force-delete-modal-{{ $return->id }}" :show="false" focusable>
                                                        <form method="POST" action="{{ route('admin.returns.force-delete', $return->id) }}" class="p-6">
                                                            @csrf
                                                            @method('DELETE')
                                                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                                                {{ __('¿Eliminar permanentemente?') }}
                                                            </h2>
                                                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 whitespace-normal">
                                                                {{ __('Esta acción no se puede deshacer. La entrega y sus datos asociados serán eliminados definitivamente.') }}
                                                            </p>
                                                            <div class="mt-6 flex justify-end">
                                                                <x-secondary-button x-on:click="$dispatch('close')">
                                                                    {{ __('Cancelar') }}
                                                                </x-secondary-button>
                                                                <x-danger-button class="ml-3">
                                                                    {{ __('Eliminar Permanentemente') }}
                                                                </x-danger-button>
                                                            </div>
                                                        </form>
                                                    </x-modal>
                                                </template>
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
                        <p class="text-center text-gray-500 dark:text-gray-400">La papelera está vacía.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
