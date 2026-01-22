<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Mis Reservas de Salas') }}
            </h2>
            <a href="{{ route('reservations.catalog') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-500 text-sm font-medium">
                ← Volver al Catálogo
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-900 border border-green-500 text-green-200 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if($reservations->isEmpty())
                        <div class="text-center py-10">
                            <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <p class="text-xl text-gray-400 font-semibold">No tienes reservas registradas.</p>
                            <a href="{{ route('reservations.catalog') }}" class="mt-4 inline-block text-blue-400 hover:text-blue-300 underline">Ir a reservar una sala</a>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full leading-normal">
                                <thead class="bg-gray-800 text-gray-300">
                                    <tr>
                                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">Sala</th>
                                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">Fecha y Hora</th>
                                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">Propósito</th>
                                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider">Estado</th>
                                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-700 bg-gray-900 text-gray-300">
                                    @foreach($reservations as $res)
                                        <tr class="hover:bg-gray-800 transition">
                                            <td class="px-5 py-4 whitespace-nowrap">
                                                <div class="text-sm font-bold text-white">{{ $res->meetingRoom->name ?? 'Sala eliminada' }}</div>
                                                <div class="text-xs text-gray-500">{{ $res->meetingRoom->location ?? '' }}</div>
                                            </td>

                                            <td class="px-5 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-200">
                                                    {{ \Carbon\Carbon::parse($res->start_time)->translatedFormat('D d M, Y') }}
                                                </div>
                                                <div class="text-xs text-gray-400 font-mono mt-1">
                                                    {{ \Carbon\Carbon::parse($res->start_time)->format('H:i') }} - 
                                                    {{ \Carbon\Carbon::parse($res->end_time)->format('H:i') }}
                                                </div>
                                            </td>

                                            <td class="px-5 py-4">
                                                <div class="text-sm italic">"{{ $res->purpose }}"</div>
                                                <div class="flex gap-2 mt-1">
                                                    <span class="text-xs bg-gray-700 px-2 py-0.5 rounded border border-gray-600">👥 {{ $res->attendees }}</span>
                                                    @if($res->resources)
                                                        <span class="text-xs bg-indigo-900/50 text-indigo-300 px-2 py-0.5 rounded border border-indigo-800" title="{{ $res->resources }}">🛠️ Recursos</span>
                                                    @endif
                                                </div>
                                            </td>

                                            <td class="px-5 py-4 text-center">
                                                @php
                                                    $colors = [
                                                        'pending' => 'bg-yellow-900 text-yellow-200 border-yellow-700',
                                                        'approved' => 'bg-green-900 text-green-200 border-green-700',
                                                        'rejected' => 'bg-red-900 text-red-200 border-red-700',
                                                        'cancelled' => 'bg-gray-700 text-gray-400 border-gray-600',
                                                    ];
                                                    $labels = [
                                                        'pending' => 'PENDIENTE',
                                                        'approved' => 'APROBADA',
                                                        'rejected' => 'RECHAZADA',
                                                        'cancelled' => 'CANCELADA',
                                                    ];
                                                @endphp
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $colors[$res->status] ?? 'bg-gray-700 text-gray-400' }}">
                                                    {{ $labels[$res->status] ?? strtoupper($res->status) }}
                                                </span>
                                            </td>

                                            <td class="px-5 py-4 text-right">
                                                @if($res->status === 'pending' || $res->status === 'approved')
                                                    <form action="{{ route('reservations.cancel', $res->id) }}" method="POST" 
                                                          onsubmit="return confirm('¿Estás seguro de que quieres cancelar esta reserva?');">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="text-red-400 hover:text-red-300 text-sm font-medium hover:underline transition">
                                                            Cancelar
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-gray-600 text-xs">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>