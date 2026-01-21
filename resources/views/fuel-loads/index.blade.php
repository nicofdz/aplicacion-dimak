<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Historial de Cargas de Combustible') }}
            @if(isset($vehicle))
                - <span class="text-indigo-600 dark:text-indigo-400">{{ $vehicle->brand }} {{ $vehicle->model }}
                    ({{ $vehicle->plate }})</span>
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100" x-data="{ receiptUrl: '' }">

                    @if(isset($vehicle))
                        <div class="mb-4">
                            <a href="{{ route('vehicles.index') }}"
                                class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                &larr; Volver a Vehículos
                            </a>
                        </div>
                    @endif

                    @if($fuelLoads->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Fecha</th>
                                        @if(!isset($vehicle))
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Vehículo</th>
                                        @endif
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Usuario</th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Kilometraje</th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Litros</th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Costo</th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Eficiencia</th>
                                        <th
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Boleta</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($fuelLoads as $load)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $load->date->format('d/m/Y H:i') }}
                                            </td>
                                            @if(!isset($vehicle))
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-medium">
                                                    {{ $load->vehicle->brand }} {{ $load->vehicle->model }} <br>
                                                    <span class="text-xs text-gray-500">{{ $load->vehicle->plate }}</span>
                                                </td>
                                            @endif
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $load->user->name }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-right">
                                                {{ number_format($load->mileage, 0, '', '.') }} km
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-right">
                                                {{ number_format($load->liters, 2, ',', '.') }} L
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-right font-bold text-green-600 dark:text-green-400">
                                                ${{ number_format($load->total_cost, 0, '', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                @if($load->efficiency_km_l)
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                        {{ number_format($load->efficiency_km_l, 1, ',', '.') }} km/L
                                                    </span>
                                                @else
                                                    <span class="text-xs text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                                @if($load->receipt_photo_path)
                                                    <button
                                                        @click="receiptUrl = '{{ Storage::url($load->receipt_photo_path) }}'; $dispatch('open-modal', 'view-receipt-modal')"
                                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mx-auto" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </button>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-4">
                                {{ $fuelLoads->appends(request()->query())->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400 mb-4">No hay registros de carga de combustible para
                                este criterio.</p>
                            @if(isset($vehicle))
                                <p class="text-sm text-gray-400">Las cargas se registran durante los viajes activos desde "Mis
                                    Reservas".</p>
                            @endif
                        </div>
                    @endif

                    <!-- Modal Foto Boleta -->
                    <x-modal name="view-receipt-modal" :show="false" focusable>
                        <div class="p-6 bg-gray-800 text-gray-100">
                            <h2 class="text-lg font-medium text-gray-100 mb-4">
                                {{ __('Boleta de Combustible') }}
                            </h2>

                            <div class="flex justify-center">
                                <img :src="receiptUrl" alt="Boleta" class="max-w-full max-h-[80vh] rounded shadow-lg">
                            </div>

                            <div class="mt-6 flex justify-end">
                                <x-secondary-button @click="$dispatch('close')"
                                    class="bg-gray-700 text-gray-300 hover:bg-gray-600 border-gray-600">
                                    {{ __('Cerrar') }}
                                </x-secondary-button>
                            </div>
                        </div>
                    </x-modal>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>