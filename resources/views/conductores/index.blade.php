<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Gestión de Conductores') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('conductores.trash') }}" 
                   class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    {{ __('Papelera') }}
                </a>
                <a href="{{ route('conductores.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition ease-in-out duration-150">
                    + Nuevo Conductor
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ openDeleteModal: false, openViewModal: false, deleteAction: '', viewingConductor: {} }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl overflow-hidden border border-gray-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Foto</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Cargo / Depto</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Estado Licencia</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-400 uppercase tracking-widest">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($conductores as $conductor)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="h-10 w-10 rounded-full overflow-hidden border border-gray-500">
                                        @if($conductor->fotografia)
                                            <img src="{{ asset('storage/' . $conductor->fotografia) }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="h-full w-full bg-gray-700 flex items-center justify-center text-[10px] text-gray-400 font-bold">N/A</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-100">{{ $conductor->nombre }}</td>
                                <td class="px-6 py-4 text-sm text-gray-300">{{ $conductor->cargo }} / {{ $conductor->departamento }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @php $vencida = $conductor->fecha_licencia->isPast(); @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-lg {{ $vencida ? 'bg-red-900/30 text-red-400' : 'bg-emerald-900/30 text-emerald-400' }}">
                                        {{ $vencida ? 'VENCIDA' : 'VENCE: ' . $conductor->fecha_licencia->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end items-center gap-4">
                                        <button @click="viewingConductor = {
                                            nombre: '{{ $conductor->nombre }}',
                                            cargo: '{{ $conductor->cargo }}',
                                            depto: '{{ $conductor->departamento }}',
                                            vencimiento: '{{ $conductor->fecha_licencia->format('d/m/Y') }}',
                                            foto: '{{ $conductor->fotografia ? asset('storage/' . $conductor->fotografia) : '' }}'
                                        }; openViewModal = true" class="text-green-400 hover:text-green-300 transition" title="Ver Detalle">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>

                                        <a href="{{ route('conductores.edit', $conductor) }}" class="text-blue-400 hover:text-blue-300" title="Editar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        
                                        <button @click="deleteAction = '{{ route('conductores.destroy', $conductor) }}'; openDeleteModal = true" class="text-red-400 hover:text-red-300" title="Eliminar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="openViewModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto" style="display: none;">
            <div x-show="openViewModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="openViewModal = false"></div>
            
            <div x-show="openViewModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative bg-gray-800 border border-gray-700 rounded-xl shadow-2xl max-w-2xl w-full overflow-hidden z-50">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-white mb-6 border-b border-gray-700 pb-2">Detalle del Conductor</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="bg-gray-900 rounded-lg p-4 border border-gray-700 flex items-center justify-center h-64">
                            <template x-if="viewingConductor.foto">
                                <img :src="viewingConductor.foto" class="h-full w-full object-cover rounded-md shadow-lg">
                            </template>
                            <template x-if="!viewingConductor.foto">
                                <span class="text-gray-500 italic">Sin fotografía</span>
                            </template>
                        </div>
                        <div class="space-y-4">
                            <div><span class="block text-xs text-gray-400 uppercase tracking-widest">Nombre</span><span class="text-2xl font-bold text-white" x-text="viewingConductor.nombre"></span></div>
                            <div class="grid grid-cols-2 gap-4">
                                <div><span class="block text-xs text-gray-400 uppercase tracking-widest">Cargo</span><span class="text-lg text-gray-200" x-text="viewingConductor.cargo"></span></div>
                                <div><span class="block text-xs text-gray-400 uppercase tracking-widest">Depto</span><span class="text-lg text-gray-200" x-text="viewingConductor.depto"></span></div>
                            </div>
                            <div><span class="block text-xs text-gray-400 uppercase tracking-widest">Vencimiento Licencia</span><span class="text-lg text-emerald-400 font-bold" x-text="viewingConductor.vencimiento"></span></div>
                        </div>
                    </div>
                    <div class="mt-8 flex justify-end"><button @click="openViewModal = false" class="px-6 py-2 bg-gray-700 text-gray-300 font-bold rounded-lg hover:bg-gray-600 transition uppercase tracking-widest text-xs">Cerrar</button></div>
                </div>
            </div>
        </div>

        </div>
</x-app-layout>