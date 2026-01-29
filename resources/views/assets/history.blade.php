<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Historial de Asignaciones') }}: {{ $asset->nombre }} ({{ $asset->codigo_interno }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100" x-data="{ activeTab: 'assignments' }">

                    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
                        <div class="mb-2 sm:mb-0">
                            <a href="{{ route('assets.index') }}" class="text-blue-500 hover:text-blue-700">
                                &larr; {{ __('Volver a Activos') }}
                            </a>
                        </div>

                        <form method="GET" action="{{ route('assets.history', $asset->id) }}"
                            class="flex flex-wrap gap-4 items-end">
                            <div>
                                <x-input-label for="start_date" :value="__('Desde')" />
                                <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}"
                                    class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <x-input-label for="end_date" :value="__('Hasta')" />
                                <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}"
                                    class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div class="flex gap-2">
                                <x-primary-button>
                                    {{ __('Filtrar') }}
                                </x-primary-button>
                                <a href="{{ route('assets.history', $asset->id) }}"
                                    class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:bg-gray-300 dark:focus:bg-gray-600 active:bg-gray-300 dark:active:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    {{ __('Limpiar') }}
                                </a>
                                <a :href="activeTab === 'assignments' ? '{{ route('assets.history.pdf', ['id' => $asset->id, 'start_date' => request('start_date'), 'end_date' => request('end_date'), 'type' => 'assignments']) }}' : '{{ route('assets.history.pdf', ['id' => $asset->id, 'start_date' => request('start_date'), 'end_date' => request('end_date'), 'type' => 'maintenances']) }}'"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    {{ __('PDF') }}
                                </a>
                            </div>
                        </form>
                    </div>

                    <div>
                        <!-- Tabs -->
                        <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
                            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                                <button @click="activeTab = 'assignments'"
                                    :class="activeTab === 'assignments' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300'"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                        </path>
                                    </svg>
                                    {{ __('Asignaciones') }}
                                </button>

                                <button @click="activeTab = 'maintenances'"
                                    :class="activeTab === 'maintenances' ? 'border-orange-500 text-orange-600 dark:text-orange-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300'"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ __('Mantenciones') }}
                                </button>
                            </nav>
                        </div>

                        <!-- Tabla Asignaciones -->
                        <div x-show="activeTab === 'assignments'" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0" class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Asignado A') }}
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Fecha Entrega') }}
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Fecha Devolución') }}
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Estado Devolución') }}
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Comentarios / Incidentes') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($assignments as $assignment)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    @if($assignment->user)
                                                        {{ $assignment->user->name }} (Usuario)
                                                    @elseif($assignment->worker)
                                                        {{ $assignment->worker->nombre }} (Externo)
                                                    @else
                                                        {{ $assignment->trabajador_nombre ?? 'N/A' }}
                                                    @endif
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $assignment->user ? $assignment->user->rut : ($assignment->worker ? $assignment->worker->rut : $assignment->trabajador_rut) }}
                                                </div>
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ $assignment->fecha_entrega ? $assignment->fecha_entrega->format('d/m/Y') : '-' }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ $assignment->fecha_devolucion ? $assignment->fecha_devolucion->format('d/m/Y H:i') : 'En curso' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($assignment->fecha_devolucion)
                                                                                        <span
                                                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                                                                                                                                                        @if($assignment->estado_devolucion == 'good') bg-green-100 text-green-800 
                                                                                                                                                                                                        @elseif($assignment->estado_devolucion == 'regular') bg-yellow-100 text-yellow-800 
                                                                                                                                                                                                        @elseif($assignment->estado_devolucion == 'bad') bg-orange-100 text-orange-800 
                                                                                                                                                                                                        @elseif($assignment->estado_devolucion == 'damaged') bg-red-100 text-red-800 
                                                                                                                                                                                                        @else bg-gray-100 text-gray-800 @endif">
                                                                                            {{ match ($assignment->estado_devolucion) {
                                                        'good' => 'Bueno',
                                                        'regular' => 'Regular',
                                                        'bad' => 'Malo',
                                                        'damaged' => 'Dañado',
                                                        default => $assignment->estado_devolucion ?? 'Desconocido'
                                                    } }}
                                                                                        </span>
                                                @else
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        Activo
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">
                                                <div class="max-w-xs overflow-hidden text-ellipsis">
                                                    @if($assignment->comentarios_devolucion)
                                                        <div class="font-semibold text-xs text-gray-400">Devolución:</div>
                                                        {{ $assignment->comentarios_devolucion }}
                                                    @endif
                                                    @if($assignment->observaciones)
                                                        <div class="mt-1 border-t border-gray-700 pt-1">
                                                            <span class="font-semibold text-xs text-gray-400">Inicio:</span>
                                                            {{ $assignment->observaciones }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                                {{ __('No hay historial de asignaciones para este activo.') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Tabla Mantenciones -->
                        <div x-show="activeTab === 'maintenances'" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0" class="overflow-x-auto"
                            style="display: none;">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Tipo') }}
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Fecha Inicio') }}
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Fecha Término') }}
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Detalles del Problema') }}
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Solución / Resultado') }}
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('Costo') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($maintenances as $maintenance)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                            {{ $maintenance->tipo === 'preventiva' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ ucfirst($maintenance->tipo) }}
                                                </span>
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ $maintenance->fecha ? $maintenance->fecha->format('d/m/Y') : '-' }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                @if($maintenance->fecha_termino)
                                                    {{ $maintenance->fecha_termino->format('d/m/Y') }}
                                                @else
                                                    <span class="text-yellow-500 flex items-center">
                                                        <svg class="w-4 h-4 mr-1 animate-pulse" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        En Proceso
                                                    </span>
                                                @endif
                                            </td>
                                            <td
                                                class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300 max-w-xs overflow-hidden text-ellipsis">
                                                {{ $maintenance->descripcion }}
                                            </td>
                                            <td
                                                class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300 max-w-xs overflow-hidden text-ellipsis">
                                                @if($maintenance->detalles_solucion)
                                                    <span class="text-green-400 block mb-1 font-medium">Resuelto:</span>
                                                    {{ $maintenance->detalles_solucion }}
                                                @else
                                                    <span class="text-gray-500 italic">Sin detalles aún</span>
                                                @endif
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                @if($maintenance->costo)
                                                    ${{ number_format($maintenance->costo, 0, ',', '.') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                                {{ __('No hay historial de mantenciones para este activo.') }}
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
    </div>
</x-app-layout>