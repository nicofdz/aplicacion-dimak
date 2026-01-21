<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Gestión de Salas de Reuniones') }}
            </h2>
            
            <div class="flex space-x-2">
                <a href="{{ route('rooms.trash') }}"
                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    {{ __('Papelera') }}
                </a>

                <button x-data="" @click="$dispatch('open-modal', 'create-room-modal')"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    + {{ __('Nueva Sala') }}
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12"
        x-data="{ 
            openModal: {{ $errors->any() ? 'true' : 'false' }}, 
            deleteAction: '', 
            editingRoom: {}, 
            editAction: '', 
            viewingRoom: {} 
        }">
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="overflow-x-auto">
                        <table class="min-w-full leading-normal">
                            <thead class="bg-gray-800 text-gray-300">
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">Foto</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">Nombre</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">Capacidad</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">Ubicación</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">Estado</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700 bg-gray-900 text-gray-300">
                                @forelse($rooms as $room)
                                    <tr class="hover:bg-gray-800 transition duration-150">
                                        <td class="px-5 py-4 text-sm">
                                            @if($room->image_path)
                                                <div class="h-10 w-10 flex-shrink-0">
                                                    <img class="h-10 w-10 rounded-full object-cover border border-gray-600"
                                                        src="{{ Storage::url($room->image_path) }}"
                                                        alt="{{ $room->name }}">
                                                </div>
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gray-700 flex items-center justify-center text-xs font-bold text-gray-400 border border-gray-600">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                                </div>
                                            @endif
                                        </td>

                                        <td class="px-5 py-4 text-sm font-bold">
                                            {{ $room->name }}
                                        </td>

                                        <td class="px-5 py-4 text-sm">
                                            {{ $room->capacity }} Personas
                                        </td>

                                        <td class="px-5 py-4 text-sm text-gray-400">
                                            {{ $room->location ?? 'No especificada' }}
                                        </td>

                                        <td class="px-5 py-4 text-sm">
                                            @php
                                                $statusClasses = [
                                                    'active' => 'text-green-400 bg-green-900/30 border border-green-900',
                                                    'maintenance' => 'text-red-400 bg-red-900/30 border border-red-900',
                                                ];
                                                $statusLabel = [
                                                    'active' => 'DISPONIBLE',
                                                    'maintenance' => 'MANTENIMIENTO',
                                                ];
                                            @endphp
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-md {{ $statusClasses[$room->status] ?? 'text-gray-400' }}">
                                                {{ $statusLabel[$room->status] ?? strtoupper($room->status) }}
                                            </span>
                                        </td>

                                        <td class="px-5 py-4 text-sm font-medium">
                                            @php
                                                // Preparamos el objeto JSON para pasarlo a Alpine
                                                $jsRoom = [
                                                    'id' => $room->id,
                                                    'name' => $room->name,
                                                    'capacity' => $room->capacity,
                                                    'location' => $room->location,
                                                    'description' => $room->description,
                                                    'status' => $room->status,
                                                    'image_url' => $room->image_path ? Storage::url($room->image_path) : null,
                                                ];
                                                $jsonRoom = json_encode($jsRoom);
                                            @endphp

                                            <div class="flex items-center space-x-4">
                                                <button @click="viewingRoom = {{ $jsonRoom }}; $dispatch('open-modal', 'view-room-modal')"
                                                    class="text-green-400 hover:text-green-300 transition duration-150" title="Ver Detalle">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </button>

                                                <button @click="editingRoom = {{ $jsonRoom }}; editAction = '{{ route('rooms.update', $room->id) }}'; $dispatch('open-modal', 'edit-room-modal')"
                                                    class="text-blue-400 hover:text-blue-300 transition duration-150" title="Editar">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </button>

                                                <button @click="deleteAction = '{{ route('rooms.destroy', $room) }}'; $dispatch('open-modal', 'confirm-delete-modal')"
                                                    class="text-red-400 hover:text-red-300 transition duration-150" title="Eliminar">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-5 py-5 text-sm text-center text-gray-500">
                                            No hay salas registradas.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <x-modal name="create-room-modal" :show="$errors->any()" focusable>
            <form method="POST" action="{{ route('rooms.store') }}" class="p-6 bg-gray-800 text-gray-100" enctype="multipart/form-data">
                @csrf
                <h2 class="text-lg font-medium text-gray-100 mb-4">
                    {{ __('Nueva Sala') }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="name" :value="__('Nombre de la Sala')" class="text-gray-300" />
                        <x-text-input id="name" class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100" type="text" name="name" :value="old('name')" required autofocus placeholder="Ej: Sala Gerencia" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="capacity" :value="__('Capacidad (Personas)')" class="text-gray-300" />
                        <x-text-input id="capacity" class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100" type="number" name="capacity" :value="old('capacity')" required min="1" />
                        <x-input-error :messages="$errors->get('capacity')" class="mt-2" />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="location" :value="__('Ubicación')" class="text-gray-300" />
                        <x-text-input id="location" class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100" type="text" name="location" :value="old('location')" placeholder="Ej: Piso 2, Ala Norte" />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="description" :value="__('Descripción / Recursos')" class="text-gray-300" />
                        <textarea id="description" name="description" rows="3" class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Ej: Proyector, Aire Acondicionado...">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <x-input-label for="status" :value="__('Estado Inicial')" class="text-gray-300" />
                        <select name="status" class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 rounded-md shadow-sm">
                            <option value="active">Activa (Disponible)</option>
                            <option value="maintenance">Mantenimiento</option>
                        </select>
                    </div>

                    <div>
                        <x-input-label for="image" :value="__('Foto (Opcional)')" class="text-gray-300" />
                        <input type="file" name="image" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer bg-gray-900 border border-gray-700 rounded-md mt-1" accept="image/*">
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <x-secondary-button x-on:click="$dispatch('close')" class="bg-gray-700 text-gray-300 hover:bg-gray-600 border-gray-600">
                        {{ __('Cancelar') }}
                    </x-secondary-button>
                    <x-primary-button class="bg-blue-600 hover:bg-blue-700 border-transparent">
                        {{ __('Guardar Sala') }}
                    </x-primary-button>
                </div>
            </form>
        </x-modal>

        <x-modal name="edit-room-modal" :show="false" focusable>
            <form method="POST" :action="editAction" enctype="multipart/form-data" class="p-6 bg-gray-800 text-gray-100">
                @csrf
                @method('PUT')

                <h2 class="text-lg font-medium text-gray-100 mb-4">
                    {{ __('Editar Sala') }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="edit_name" :value="__('Nombre')" class="text-gray-300" />
                        <x-text-input id="edit_name" class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100" type="text" name="name" x-model="editingRoom.name" required />
                    </div>

                    <div>
                        <x-input-label for="edit_capacity" :value="__('Capacidad')" class="text-gray-300" />
                        <x-text-input id="edit_capacity" class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100" type="number" name="capacity" x-model="editingRoom.capacity" required />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="edit_location" :value="__('Ubicación')" class="text-gray-300" />
                        <x-text-input id="edit_location" class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100" type="text" name="location" x-model="editingRoom.location" />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="edit_description" :value="__('Descripción')" class="text-gray-300" />
                        <textarea id="edit_description" name="description" rows="3" class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 rounded-md shadow-sm" x-model="editingRoom.description"></textarea>
                    </div>

                    <div>
                        <x-input-label for="edit_status" :value="__('Estado')" class="text-gray-300" />
                        <select name="status" x-model="editingRoom.status" class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-100 rounded-md shadow-sm">
                            <option value="active">Activa</option>
                            <option value="maintenance">Mantenimiento</option>
                        </select>
                    </div>

                    <div>
                        <x-input-label for="edit_image" :value="__('Actualizar Foto (Opcional)')" class="text-gray-300" />
                        <input type="file" name="image" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer bg-gray-900 border border-gray-700 rounded-md mt-1" accept="image/*">
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <x-secondary-button @click="$dispatch('close')" class="bg-gray-700 text-gray-300 hover:bg-gray-600 border-gray-600">
                        {{ __('Cancelar') }}
                    </x-secondary-button>
                    <x-primary-button class="bg-blue-600 hover:bg-blue-700 border-transparent">
                        {{ __('Actualizar') }}
                    </x-primary-button>
                </div>
            </form>
        </x-modal>

        <x-modal name="view-room-modal" :show="false" focusable>
            <div class="p-6 bg-gray-800 text-gray-100">
                <h2 class="text-xl font-bold text-gray-100 mb-6 border-b border-gray-700 pb-2">
                    {{ __('Detalle de la Sala') }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="flex flex-col items-center justify-center bg-gray-900 rounded-lg p-4 border border-gray-700">
                        <template x-if="viewingRoom.image_url">
                            <img :src="viewingRoom.image_url" class="w-full h-64 object-cover rounded-md shadow-lg">
                        </template>
                        <template x-if="!viewingRoom.image_url">
                            <div class="w-full h-64 flex items-center justify-center bg-gray-800 text-gray-500 rounded-md">
                                <span class="text-sm">Sin imagen disponible</span>
                            </div>
                        </template>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <span class="block text-xs text-gray-400 uppercase tracking-widest">Nombre</span>
                            <span class="text-2xl font-bold text-white tracking-wider" x-text="viewingRoom.name"></span>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="block text-xs text-gray-400 uppercase tracking-widest">Capacidad</span>
                                <span class="text-lg text-gray-200" x-text="viewingRoom.capacity + ' Personas'"></span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-400 uppercase tracking-widest">Estado</span>
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-md"
                                    :class="viewingRoom.status === 'active' ? 'bg-green-900 text-green-200' : 'bg-red-900 text-red-200'"
                                    x-text="viewingRoom.status === 'active' ? 'DISPONIBLE' : 'MANTENIMIENTO'">
                                </span>
                            </div>
                        </div>

                        <div>
                            <span class="block text-xs text-gray-400 uppercase tracking-widest">Ubicación</span>
                            <span class="text-lg text-gray-200" x-text="viewingRoom.location || 'No especificada'"></span>
                        </div>

                        <div>
                            <span class="block text-xs text-gray-400 uppercase tracking-widest">Descripción / Recursos</span>
                            <p class="text-sm text-gray-300 mt-1" x-text="viewingRoom.description || 'Sin descripción'"></p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <x-secondary-button @click="$dispatch('close')" class="bg-gray-700 text-gray-300 hover:bg-gray-600 border-gray-600 w-full md:w-auto justify-center">
                        {{ __('Cerrar') }}
                    </x-secondary-button>
                </div>
            </div>
        </x-modal>

        <x-modal name="confirm-delete-modal" :show="false" focusable>
            <div class="p-6 bg-gray-800 text-gray-100">
                <h2 class="text-lg font-medium text-gray-100">
                    {{ __('¿Estás seguro?') }}
                </h2>
                <p class="mt-1 text-sm text-gray-400">
                    {{ __('La sala se movera a la papelera. Podras restaurarla despues si lo necesitas') }}
                </p>
                <div class="mt-6 flex justify-end space-x-3">
                    <x-secondary-button @click="$dispatch('close')" class="bg-gray-700 text-gray-300 hover:bg-gray-600 border-gray-600">
                        {{ __('Cancelar') }}
                    </x-secondary-button>
                    <form method="POST" :action="deleteAction">
                        @csrf
                        @method('DELETE')
                        <x-danger-button class="ml-3">
                            {{ __('Sí, Eliminar') }}
                        </x-danger-button>
                    </form>
                </div>
            </div>
        </x-modal>

    </div>
</x-app-layout>