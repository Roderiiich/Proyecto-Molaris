<nav
    x-data="{ open: false }"
    class="border-b border-molaris-accent/20 bg-molaris-dark shadow-md"
>
    <!-- Primary Navigation Menu -->
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <!-- Contenedor Izquierdo: Logo + Links -->
            <div class="flex items-center space-x-8">
                <!-- Logo Molaris (Software Dental) -->
                <div class="flex shrink-0 items-center">
                    <a
                        href="{{ route('dashboard') }}"
                        class="group flex items-center gap-2.5"
                    >
                        <!-- Nombre de la Clínica y Subtítulo -->
                        <div class="flex flex-col justify-center">
                            <!-- MOLARIS: Grande y en negrita -->
                            <span
                                class="font-bold leading-none tracking-wider text-white transition group-hover:text-cyan-400"
                                style="font-size: 1.45rem"
                            >
                                MOLARIS
                            </span>
                            <!-- Software Dental: Fino, pequeño y en minúsculas/capitalizado -->
                            <span
                                class="font-normal leading-none tracking-normal text-slate-300"
                                style="font-size: 0.75rem"
                            >
                                Software Dental
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links (Escritorio) -->
                <div class="hidden space-x-8 sm:flex">
                    <!-- Dashboard -->
                    <x-nav-link
                        :href="route('dashboard')"
                        :active="request()->routeIs('dashboard')"
                        class="font-semibold text-white transition hover:text-molaris-accent"
                    >
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <!-- Pacientes -->
                    <x-nav-link
                        :href="route('pacientes.index')"
                        :active="request()->routeIs('pacientes.*')"
                        class="font-semibold text-white transition hover:text-molaris-accent"
                    >
                        {{ __('Pacientes') }}
                    </x-nav-link>

                    <!-- Odontólogos -->
                    <x-nav-link
                        :href="route('doctores.index')"
                        :active="request()->routeIs('doctores.*')"
                        class="font-semibold text-white transition hover:text-molaris-accent"
                    >
                        {{ __('Odontólogos') }}
                    </x-nav-link>

                    <!-- Boxes de Atención -->
                    <x-nav-link
                        :href="route('boxes.index')"
                        :active="request()->routeIs('boxes.*')"
                        class="font-semibold text-white transition hover:text-molaris-accent"
                    >
                        {{ __('Boxes') }}
                    </x-nav-link>

                    <!-- Agenda / Citas -->
                    <x-nav-link
                        :href="url('/agenda')"
                        :active="request()->is('agenda*')"
                        class="font-semibold text-white transition hover:text-molaris-accent"
                    >
                        {{ __('Agenda') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown (Empujado a la esquina derecha con ícono de usuario) -->
            <div class="ms-auto hidden sm:ms-6 sm:flex sm:items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center gap-2 rounded-md border border-transparent bg-molaris-primary px-3 py-2 text-sm font-medium leading-4 text-white transition duration-150 ease-in-out hover:bg-molaris-accent/20 focus:outline-none"
                        >
                            <!-- Ícono de Usuario/Avatar -->
                            <svg class="h-4 w-4 shrink-0 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>

                            <!-- Nombre del Usuario -->
                            <div>
                                {{ Auth::user()->name ?? Auth::user()->nombre }}
                            </div>

                            <!-- Flecha Desplegable -->
                            <div class="ms-1">
                                <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Perfil') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link
                                :href="route('logout')"
                                onclick="
                                    event.preventDefault();
                                    this.closest('form').submit();
                                "
                            >
                                {{ __('Cerrar Sesión') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Botón Menú Hamburguesa (Móvil) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button
                    @click="open = !open"
                    class="inline-flex items-center justify-center rounded-md p-2 text-slate-300 transition hover:bg-molaris-primary hover:text-white focus:outline-none"
                >
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{
                                hidden: open,
                                'inline-flex': !open,
                            }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{
                                hidden: !open,
                                'inline-flex': open,
                            }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Menú Desplegable Responsivo (Móvil) -->
    <div
        :class="{ block: open, hidden: !open }"
        class="hidden border-t border-molaris-accent/20 bg-molaris-dark sm:hidden"
    >
        <div class="space-y-1 pb-3 pt-2">
            <x-responsive-nav-link
                :href="route('dashboard')"
                :active="request()->routeIs('dashboard')"
                class="text-white hover:text-molaris-accent"
            >
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link
                :href="route('pacientes.index')"
                :active="request()->routeIs('pacientes.*')"
                class="text-white hover:text-molaris-accent"
            >
                {{ __('Pacientes') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link
                :href="route('doctores.index')"
                :active="request()->routeIs('doctores.*')"
                class="text-white hover:text-molaris-accent"
            >
                {{ __('Odontólogos') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link
                :href="route('boxes.index')"
                :active="request()->routeIs('boxes.*')"
                class="text-white hover:text-molaris-accent"
            >
                {{ __('Boxes') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link
                :href="url('/agenda')"
                :active="request()->is('agenda*')"
                class="text-white hover:text-molaris-accent"
            >
                {{ __('Agenda') }}
            </x-responsive-nav-link>
        </div>

        <!-- Opciones de Usuario (Móvil) -->
        <div class="border-t border-molaris-accent/20 pb-1 pt-4">
            <div class="mb-2 flex items-center gap-2 px-4">
                <svg class="h-4 w-4 shrink-0 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <div>
                    <div class="text-base font-medium text-white">
                        {{ Auth::user()->name ?? Auth::user()->nombre }}
                    </div>
                    <div class="text-sm font-medium text-slate-400">
                        {{ Auth::user()->email ?? '' }}
                    </div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link
                    :href="route('profile.edit')"
                    class="text-slate-300 hover:text-white"
                >
                    {{ __('Perfil') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link
                        :href="route('logout')"
                        onclick="
                            event.preventDefault();
                            this.closest('form').submit();
                        "
                        class="text-slate-300 hover:text-white"
                    >
                        {{ __('Cerrar Sesión') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
