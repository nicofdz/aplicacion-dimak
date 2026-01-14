<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Panel de Vehiculos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-6 mb-12">
                <div class="flex flex-row justify-around items-center divide-x divide-gray-100 dark:divide-gray-700">
                    
                    <div class="flex-1 text-center">
                        <span class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Disponibles</span>
                        <span class="block text-4xl font-black text-emerald-500">0</span>
                    </div>

                    <div class="flex-1 text-center">
                        <span class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Asignados</span>
                        <span class="block text-4xl font-black text-blue-500">0</span>
                    </div>

                    <div class="flex-1 text-center">
                        <span class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">En Mantenimiento</span>
                        <span class="block text-4xl font-black text-amber-500">0</span>
                    </div>

                    <div class="flex-1 text-center">
                        <span class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Fuera de Servicio</span>
                        <span class="block text-4xl font-black text-rose-500">0</span>
                    </div>

                </div>
            </div>

            <div class="mt-8">
                <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-6 px-2">
                    Vehículos Disponibles
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="col-span-full py-20 bg-gray-50 dark:bg-gray-900/40 rounded-3xl border-2 border-dashed border-gray-100 dark:border-gray-800 flex flex-col items-center">
                        <p class="text-gray-400 text-xs uppercase tracking-[0.2em]">...</p>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>