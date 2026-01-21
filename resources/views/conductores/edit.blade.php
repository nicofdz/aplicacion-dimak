<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Editar Conductor: ') . $conductor->nombre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 border border-stone-200 dark:border-gray-700 rounded-xl shadow-sm p-8">

                <div class="mb-8 border-b border-white/20 pb-4">
                    <p class="font-sans antialiased text-2xl text-white font-bold mb-1">
                        Actualizar Información
                    </p>
                    <p class="font-sans antialiased text-sm text-gray-400">
                        Modifique los campos necesarios para actualizar el perfil del conductor.
                    </p>
                </div>

                <form action="{{ route('conductores.update', $conductor) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                        <div class="space-y-1">
                            <label for="nombre" class="block mb-1 text-sm font-semibold text-white">Nombre
                                Completo</label>
                            <input id="nombre" name="nombre" type="text" value="{{ $conductor->nombre }}" required
                                class="w-full outline-none text-white placeholder:text-gray-600 border border-white bg-gray-900 transition-all text-sm py-2.5 px-3 rounded-lg focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm" />
                        </div>

                        <div class="space-y-1">
                            <label for="cargo" class="block mb-1 text-sm font-semibold text-white">Cargo</label>
                            <input id="cargo" name="cargo" type="text" value="{{ $conductor->cargo }}" required
                                class="w-full outline-none text-white placeholder:text-gray-600 border border-white bg-gray-900 transition-all text-sm py-2.5 px-3 rounded-lg focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm" />
                        </div>

                        <div class="space-y-1">
                            <label for="departamento"
                                class="block mb-1 text-sm font-semibold text-white">Departamento</label>
                            <input id="departamento" name="departamento" type="text"
                                value="{{ $conductor->departamento }}" required
                                class="w-full outline-none text-white placeholder:text-gray-600 border border-white bg-gray-900 transition-all text-sm py-2.5 px-3 rounded-lg focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm" />
                        </div>

                        <div class="space-y-1">
                            <label for="fecha_licencia" class="block mb-1 text-sm font-semibold text-white">Vencimiento
                                Licencia</label>
                            <input id="fecha_licencia" name="fecha_licencia" type="date"
                                value="{{ $conductor->fecha_licencia->format('Y-m-d') }}" required
                                class="w-full outline-none text-gray-500 border border-white bg-gray-900 transition-all text-sm py-2.5 px-3 rounded-lg focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm [&::-webkit-calendar-picker-indicator]:invert" />
                        </div>

                        <div class="space-y-1">
                            <label for="fotografia" class="block mb-1 text-sm font-semibold text-white">Cambiar
                                Fotografía (Opcional)</label>
                            <input id="fotografia" name="fotografia" type="file" accept="image/*"
                                class="w-full outline-none text-gray-500 border border-white bg-gray-900 text-sm py-[7px] px-3 rounded-lg focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm file:mr-4 file:py-0.5 file:px-2 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-gray-800 file:text-gray-400 hover:file:bg-gray-700 cursor-pointer" />
                            @if($conductor->fotografia)
                                <p class="text-[10px] text-gray-400 mt-1 italic">Ya existe una foto cargada. Sube una nueva
                                    para reemplazarla.</p>
                            @endif
                        </div>

                    </div>

                    <div class="mt-10 pt-6 border-t border-gray-700 flex justify-end gap-4">
                        <a href="{{ route('conductores.index') }}"
                            class="px-6 py-2.5 text-sm font-semibold text-gray-400 hover:text-white transition-all">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-lg transition-all shadow-md">
                            Actualizar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>