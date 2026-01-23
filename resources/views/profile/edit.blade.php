<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Perfil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Información del Perfil - Ancho Completo -->
            <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                @include('profile.partials.update-profile-information-form')
            </div>

            <!-- Grid de 2 columnas para Seguridad -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Cambiar Contraseña -->
                <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    @include('profile.partials.update-password-form')
                </div>

                <!-- Eliminar Cuenta -->
                <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>