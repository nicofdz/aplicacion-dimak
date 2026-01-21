<!-- Mobile Backdrop -->
<div x-show="mobileSidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-black/50 md:hidden"
    @click="mobileSidebarOpen = false"></div>

<aside x-data="{ open: true }" :class="{
           'w-64': open, 
           'w-20': !open,
           '-translate-x-full': !mobileSidebarOpen,
           'translate-x-0': mobileSidebarOpen
       }"
    class="fixed inset-y-0 left-0 z-50 flex-shrink-0 min-h-screen bg-gray-900 border-r border-gray-800 transition-all duration-300 ease-in-out flex flex-col pt-0 md:relative md:translate-x-0">

    <!-- Toggle Button & Logo Area -->
    <div class="h-16 flex items-center justify-between px-4 bg-gray-900 border-b border-gray-800">
        <div class="flex items-center space-x-2" :class="{'justify-center w-full': !open}">
            <!-- Logo (Optional) -->
            <!-- Logo -->
            <a href="{{ route('dashboard') }}" class="flex items-center justify-center overflow-hidden">
                <img src="{{ asset('images/dimak-logo.png') }}" alt="Dimak Logo"
                    class="object-contain transition-all duration-300" :class="open ? 'h-10' : 'h-8'" />
            </a>
        </div>
        <button @click="open = !open" x-show="open" class="text-gray-400 hover:text-white focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
        </button>
    </div>

    <!-- Re-open button when closed -->
    <div x-show="!open" class="flex justify-center py-4 border-b border-gray-800">
        <button @click="open = !open" class="text-gray-400 hover:text-white focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
            </svg>
        </button>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-2 py-4 space-y-2 overflow-y-auto">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
            class="flex items-center px-2 py-2 text-gray-300 rounded-md hover:bg-gray-800 hover:text-white group"
            :class="{'justify-center': !open, 'bg-gray-800 text-white': {{ request()->routeIs('dashboard') ? 'true' : 'false' }}}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
            <span x-show="open" class="ml-3 whitespace-nowrap" x-transition:enter="delay-75">Dashboard</span>
        </a>

        @if(Auth::user()->role === 'admin')
            <!-- Gestión de Usuarios -->
            <a href="{{ route('users.index') }}"
                class="flex items-center px-2 py-2 text-gray-300 rounded-md hover:bg-gray-800 hover:text-white group"
                :class="{'justify-center': !open, 'bg-gray-800 text-white': {{ request()->routeIs('users.*') ? 'true' : 'false' }}}">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span x-show="open" class="ml-3 whitespace-nowrap" x-transition:enter="delay-75">Gestión de Usuarios</span>
            </a>

            <!-- Historial Entregas -->
            <a href="{{ route('admin.returns.index') }}"
                class="flex items-center px-2 py-2 text-gray-300 rounded-md hover:bg-gray-800 hover:text-white group"
                :class="{'justify-center': !open, 'bg-gray-800 text-white': {{ request()->routeIs('admin.returns.*') ? 'true' : 'false' }}}">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span x-show="open" class="ml-3 whitespace-nowrap" x-transition:enter="delay-75">Historial Entregas</span>
            </a>
        @endif
        <!-- Gestion de Salas -->
        <a href="{{ route('rooms.index') }}"
            class="flex items-center px-2 py-2 text-gray-300 rounded-md hover:bg-gray-800 hover:text-white group"
            :class="{'justify-center': !open, 'bg-gray-800 text-white': {{ request()->routeIs('rooms.*') ? 'true' : 'false' }}}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 9h6m-6 3h6m-6 3h6M6.996 9h.01m-.01 3h.01m-.01 3h.01M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z"/>
            </svg>
            <span x-show="open" class="ml-3 whitespace-nowrap" x-transition:enter="delay-75">Gestión de Salas</span>
        </a>

        <!-- Vehículos -->
        <a href="{{ route('vehicles.index') }}"
            class="flex items-center px-2 py-2 text-gray-300 rounded-md hover:bg-gray-800 hover:text-white group"
            :class="{'justify-center': !open, 'bg-gray-800 text-white': {{ request()->routeIs('vehicles.*') ? 'true' : 'false' }}}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 012-2h5a2 2 0 012 2m0 0h2a2 2 0 012 2v3a2 2 0 01-2 2H5a2 2 0 01-2-2v-3a2 2 0 012-2z" />
            </svg>
            <span x-show="open" class="ml-3 whitespace-nowrap" x-transition:enter="delay-75">Gestión de Vehículos</span>
        </a>

        <!-- Conductores -->
        <a href="{{ route('conductores.index') }}"
            class="flex items-center px-2 py-2 text-gray-300 rounded-md hover:bg-gray-800 hover:text-white group"
            :class="{'justify-center': !open, 'bg-gray-800 text-white': {{ request()->routeIs('conductores.*') ? 'true' : 'false' }}}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span x-show="open" class="ml-3 whitespace-nowrap" x-transition:enter="delay-75">Conductores</span>
        </a>

        <!-- Solicitar Vehículo -->
        <a href="{{ route('requests.create') }}"
            class="flex items-center px-2 py-2 text-gray-300 rounded-md hover:bg-gray-800 hover:text-white group"
            :class="{'justify-center': !open, 'bg-gray-800 text-white': {{ request()->routeIs('requests.create') ? 'true' : 'false' }}}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2h-2a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span x-show="open" class="ml-3 whitespace-nowrap" x-transition:enter="delay-75">Solicitar Vehículo</span>
        </a>

        <!-- Mis Reservas -->
        <a href="{{ route('requests.index') }}"
            class="flex items-center px-2 py-2 text-gray-300 rounded-md hover:bg-gray-800 hover:text-white group"
            :class="{'justify-center': !open, 'bg-gray-800 text-white': {{ request()->routeIs('requests.index') ? 'true' : 'false' }}}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span x-show="open" class="ml-3 whitespace-nowrap" x-transition:enter="delay-75">Mis Reservas</span>
        </a>
    </nav>
</aside>