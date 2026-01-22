<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Historial de Reservas Aprobadas') }}
            </h2>
            <a href="{{ route('rooms.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md text-sm font-bold hover:bg-gray-500 transition">
                ← Volver a Salas
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    @if($reservations->isEmpty())
                        <div class="text-center py-10 text-gray-500">
                            No hay historial de reservas aprobadas aún.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full leading-normal">
                                <thead class="bg-gray-800 text-gray-300">
                                    <tr>
                                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">Fecha</th>
                                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">Sala</th>
                                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">Solicitante</th>
                                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">Propósito</th>
                                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider">Estado Tiempo</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-700 bg-gray-900 text-gray-300">
                                    @foreach($reservations as $res)
                                        <tr class="hover:bg-gray-800 transition duration-150">
                                            <td class="px-5 py-4 text-sm">
                                                <div class="font-bold text-white">{{ $res->start_time->format('d/m/Y') }}</div>
                                                <div class="text-xs text-gray-400">
                                                    {{ $res->start_time->format('H:i') }} - {{ $res->end_time->format('H:i') }}
                                                </div>
                                            </td>

                                            <td class="px-5 py-4 text-sm font-medium text-blue-400">
                                                {{ $res->meetingRoom->name ?? 'Sala Eliminada' }}
                                            </td>

                                            <td class="px-5 py-4 text-sm">
                                                <div class="flex items-center">
                                                    <div class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center text-xs font-bold text-white mr-2">
                                                        {{ substr($res->user->name ?? 'X', 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="font-medium text-white">{{ $res->user->name ?? 'Usuario Eliminado' }}</div>
                                                        <div class="text-xs text-gray-500">{{ $res->user->email ?? '' }}</div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-5 py-4 text-sm">
                                                <div class="text-white italic">"{{ $res->purpose }}"</div>
                                                @if($res->resources)
                                                    <div class="mt-1 text-xs text-indigo-300">
                                                        <span class="font-bold">Recursos:</span> {{ $res->resources }}
                                                    </div>
                                                @endif
                                            </td>

                                            <td class="px-5 py-4 text-center">

                                                @if($res->status === 'cancelled')
                                                   
                                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">
                                                        CANCELADA
                                                    </span>

                                                @elseif($res->end_time->isPast())
                                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-700 text-gray-300 border border-gray-600">
                                                        FINALIZADA
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-900 text-green-200 border border-green-700 animate-pulse">
                                                        PROGRAMADA
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $reservations->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>