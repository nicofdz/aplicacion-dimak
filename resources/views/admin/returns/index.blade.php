<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Historial de Entregas') }}
        </h2>
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
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Vehículo</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Usuario</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado Entrega</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Detalles</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($returns as $return)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $return->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                <div class="flex items-center">
                                                    @if($return->request->vehicle->image_path)
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
                                                            {{ $return->request->vehicle->brand }} {{ $return->request->vehicle->model }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $return->request->vehicle->plate }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $return->request->user->name }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                <div class="flex flex-col space-y-1">
                                                    <span class="text-xs">
                                                        ⛽ Combustible: 
                                                        <span class="font-bold {{ $return->fuel_level == 'full' ? 'text-green-500' : 'text-yellow-500' }}">
                                                            {{ $return->fuel_level }}
                                                        </span>
                                                    </span>
                                                    <span class="text-xs">
                                                        🧼 Limpieza: 
                                                        <span class="font-bold {{ $return->cleanliness == 'clean' ? 'text-green-500' : 'text-red-500' }}">
                                                            {{ $return->cleanliness == 'clean' ? 'Limpio' : ($return->cleanliness == 'dirty' ? 'Sucio' : 'Muy Sucio') }}
                                                        </span>
                                                    </span>
                                                    <span class="text-xs">
                                                        🛞 Neumáticos: 
                                                        <span class="font-bold {{ ($return->tire_status_front == 'good' && $return->tire_status_rear == 'good') ? 'text-green-500' : 'text-red-500' }}">
                                                            {{ ($return->tire_status_front == 'good' && $return->tire_status_rear == 'good') ? 'OK' : 'Revisar' }}
                                                        </span>
                                                    </span>
                                                    @if($return->body_damage_reported)
                                                        <span class="text-xs font-bold text-red-500 bg-red-100 px-1 rounded w-max">
                                                            ⚠️ Daño Carrocería
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div x-data="{ 
                                                    open: false, 
                                                    carouselOpen: false, 
                                                    images: [], 
                                                    currentImage: '', 
                                                    currentIndex: 0,
                                                    openCarousel(imgs, index) {
                                                        this.images = imgs;
                                                        this.currentIndex = index;
                                                        this.currentImage = this.images[index];
                                                        this.carouselOpen = true;
                                                    },
                                                    next() {
                                                        this.currentIndex = (this.currentIndex + 1) % this.images.length;
                                                        this.currentImage = this.images[this.currentIndex];
                                                    },
                                                    prev() {
                                                        this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
                                                        this.currentImage = this.images[this.currentIndex];
                                                    }
                                                }">
                                                    <button @click="open = true" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">Ver Ficha</button>

                                                    <!-- Modal Detalle -->
                                                    <template x-teleport="body">
                                                        <div x-show="open" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-transition>
                                                            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                                                                <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="open = false">
                                                                    <div class="absolute inset-0 bg-black bg-opacity-60"></div>
                                                                </div>

                                                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                                                                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full relative z-50">
                                                                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                                        <div class="sm:flex sm:items-start">
                                                                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                                                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mb-4" id="modal-title">
                                                                                    Detalle de Entrega #{{ $return->id }}
                                                                                </h3>
                                                                                
                                                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-500 dark:text-gray-300">
                                                                                    <div>
                                                                                        <h4 class="font-bold mb-2">Vehículo</h4>
                                                                                        <p>Modelo: {{ $return->request->vehicle->brand }} {{ $return->request->vehicle->model }}</p>
                                                                                        <p>Patente: {{ $return->request->vehicle->plate }}</p>
                                                                                        <p>Kms Devolución: {{ number_format($return->return_mileage, 0, '', '.') }} km</p>
                                                                                    </div>
                                                                                    <div>
                                                                                        <h4 class="font-bold mb-2">Estado Reportado</h4>
                                                                                        @php
                                                                                            $tireMap = ['good' => 'Bueno', 'fair' => 'Regular', 'poor' => 'Malo'];
                                                                                            $cleanMap = ['clean' => 'Limpio', 'dirty' => 'Sucio', 'very_dirty' => 'Muy Sucio'];
                                                                                        @endphp
                                                                                        <ul class="list-disc pl-5 space-y-1">
                                                                                            <li>Combustible: {{ $return->fuel_level }}</li>
                                                                                            <li>Neum. Delanteros: {{ $tireMap[$return->tire_status_front] ?? $return->tire_status_front }}</li>
                                                                                            <li>Neum. Traseros: {{ $tireMap[$return->tire_status_rear] ?? $return->tire_status_rear }}</li>
                                                                                            <li>Limpieza: {{ $cleanMap[$return->cleanliness] ?? $return->cleanliness }}</li>
                                                                                            <li>Daños: {{ $return->body_damage_reported ? 'SÍ' : 'No' }}</li>
                                                                                        </ul>
                                                                                    </div>
                                                                                    
                                                                                    <div class="col-span-2 mt-4">
                                                                                        <h4 class="font-bold mb-2">Comentarios</h4>
                                                                                        <p class="bg-gray-100 dark:bg-gray-700 p-2 rounded">{{ $return->comments ?: 'Sin comentarios' }}</p>
                                                                                    </div>

                                                                                    @if($return->photos_paths && count($return->photos_paths) > 0)
                                                                                        <div class="col-span-2 mt-4">
                                                                                            <h4 class="font-bold mb-2">Fotos Adjuntas</h4>
                                                                                            @php
                                                                                                $gallery = collect($return->photos_paths)->map(fn($p) => Storage::url($p))->values();
                                                                                            @endphp
                                                                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                                                                                @foreach($gallery as $index => $photoUrl)
                                                                                                    <button @click="openCarousel({{ $gallery->toJson() }}, {{ $index }})" class="block group relative w-full h-24 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 rounded">
                                                                                                        <img src="{{ $photoUrl }}" class="w-full h-full object-cover rounded border hover:opacity-75 transition" alt="Foto Entrega">
                                                                                                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                                                                                                            <svg class="w-8 h-8 text-white drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                                                                                        </div>
                                                                                                    </button>
                                                                                                @endforeach
                                                                                            </div>
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                                        <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" @click="open = false">
                                                                            Cerrar
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>

                                                    <!-- Modal Carousel (Lightbox) -->
                                                    <template x-teleport="body">
                                                        <div x-show="carouselOpen" class="fixed inset-0 z-[70] overflow-y-auto" style="display: none;" x-transition>
                                                            <!-- Backdrop -->
                                                            <div class="fixed inset-0 bg-black bg-opacity-95 transition-opacity" @click="carouselOpen = false"></div>

                                                            <!-- Content -->
                                                            <div class="flex items-center justify-center min-h-screen p-4 pointer-events-none">
                                                                <div class="relative w-full h-full flex flex-col items-center justify-center pointer-events-auto">
                                                                    
                                                                    <!-- Close Button -->
                                                                    <button @click="carouselOpen = false" class="absolute top-4 right-4 text-white hover:text-gray-300 z-[80] focus:outline-none p-2 rounded-full bg-black/50">
                                                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                                    </button>

                                                                    <!-- Main Container for Image + Nav -->
                                                                    <div class="relative flex items-center justify-center w-full max-w-6xl">
                                                                        <!-- Previous Button -->
                                                                        <button x-show="images.length > 1" @click.stop="prev()" class="absolute left-2 md:-left-12 p-3 text-white hover:text-gray-300 focus:outline-none bg-black/50 hover:bg-black/70 rounded-full z-[80] transition">
                                                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                                                        </button>

                                                                        <!-- Image -->
                                                                        <div class="relative">
                                                                            <img :src="currentImage" class="max-w-full max-h-[85vh] object-contain rounded shadow-2xl" @click.stop="">
                                                                            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-black/60 px-3 py-1 rounded-full text-white text-sm font-mono" x-show="images.length > 1">
                                                                                <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Next Button -->
                                                                        <button x-show="images.length > 1" @click.stop="next()" class="absolute right-2 md:-right-12 p-3 text-white hover:text-gray-300 focus:outline-none bg-black/50 hover:bg-black/70 rounded-full z-[80] transition">
                                                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
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
                        <p class="text-center text-gray-500 dark:text-gray-400">No hay registros de entregas aún.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
