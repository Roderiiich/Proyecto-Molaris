<nav
    x-data="{ open: false }"
    class="border-b border-molaris-accent/20 bg-molaris-dark shadow-md"
>
    <!-- Primary Navigation Menu -->
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <!-- Contenedor Izquierdo: Logo + Links -->
            <div class="flex items-center space-x-8">
                <!-- Logo Molaris -->
                <div class="flex shrink-0 items-center">
                    <a
                        href="{{ auth()->user()->rol?->nombre === 'Dentista' ? route('agenda.personal') : route('dashboard') }}"
                        class="group flex items-center gap-2.5"
                    >
                        <div class="flex flex-col justify-center">
                            <span
                                class="font-bold leading-none tracking-wider text-white transition group-hover:text-cyan-400"
                                style="font-size: 1.45rem"
                            >
                                MOLARIS
                            </span>
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
                <div class="hidden space-x-8 sm:flex sm:items-center">
                    {{-- ------------------------------------------------------------- --}}
                    {{-- RUTA SOLO ADMINISTRADOR                                        --}}
                    {{-- ------------------------------------------------------------- --}}
                    @if (auth()->user()->rol?->nombre === 'Administrador')
                        <!-- Dashboard -->
                        <x-nav-link
                            :href="route('dashboard')"
                            :active="request()->routeIs('dashboard')"
                            class="font-semibold text-white transition hover:text-molaris-accent"
                        >
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <!-- Agenda General / Citas -->
                        <x-nav-link
                            :href="route('agenda.index')"
                            :active="request()->routeIs('agenda.index')"
                            class="font-semibold text-white transition hover:text-molaris-accent"
                        >
                            {{ __('Agenda ') }}
                        </x-nav-link>
                    @endif

                    {{-- ------------------------------------------------------------- --}}
                    {{-- RUTA EXCLUSIVA SOLO PARA DENTISTA                              --}}
                    {{-- ------------------------------------------------------------- --}}
                    @if (auth()->user()->rol?->nombre === 'Dentista')
                        <!-- Mi Agenda (Atención Personal) -->
                        <x-nav-link
                            :href="route('agenda.personal')"
                            :active="request()->routeIs('agenda.personal')"
                            class="font-semibold text-white transition hover:text-cyan-300"
                        >
                            {{ __('Mi Agenda') }}
                        </x-nav-link>
                        <x-nav-link
                            :href="route('financiero.presupuestos.index')"
                            :active="request()->routeIs('financiero.presupuestos.index')"
                            class="font-semibold text-white transition hover:text-cyan-300"
                        >
                            {{ __('Presupuesto') }}
                        </x-nav-link>

                        <x-nav-link
                            :href="route('agenda.general')"
                            :active="request()->routeIs('agenda.general')"
                            class="font-semibold text-white transition hover:text-cyan-300"
                        >
                            {{ __('Agenda General') }}
                        </x-nav-link>
                    @endif

                    {{-- ------------------------------------------------------------- --}}
                    {{-- RUTAS COMPARTIDAS (Administrador y Dentista)                 --}}
                    {{-- ------------------------------------------------------------- --}}
                    <!-- Pacientes -->
                    <x-nav-link
                        :href="route('pacientes.index')"
                        :active="request()->routeIs('pacientes.*')"
                        class="font-semibold text-white transition hover:text-molaris-accent"
                    >
                        {{ __('Pacientes') }}
                    </x-nav-link>

                    {{-- ------------------------------------------------------------- --}}
                    {{-- RUTAS SOLO ADMINISTRADOR                                       --}}
                    {{-- ------------------------------------------------------------- --}}
                    @if (auth()->user()->rol?->nombre === 'Administrador')
                        <!-- Inventario -->
                        <x-nav-link
                            :href="route('inventario.index')"
                            :active="request()->routeIs('inventario.*')"
                            class="font-semibold text-white transition hover:text-molaris-accent"
                        >
                            {{ __('Inventario') }}
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

                        <!-- Categoría Desplegable: Financiero -->
                        <div
                            x-data="{ dropdownOpen: false }"
                            @click.outside="dropdownOpen = false"
                            class="relative"
                        >
                            <button
                                @click="dropdownOpen = !dropdownOpen"
                                class="inline-flex items-center gap-1.5 py-2 font-semibold text-white transition hover:text-molaris-accent focus:outline-none"
                                :class="{ 'text-cyan-400': {{ request()->is('financiero*') ? 'true' : 'false' }} }"
                            >
                                <span>{{ __('Financiero') }}</span>
                                <svg
                                    class="h-4 w-4 transition-transform duration-200"
                                    :class="{ 'rotate-180': dropdownOpen }"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Menú Flotante -->
                            <div
                                x-show="dropdownOpen"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute left-0 z-50 mt-2 w-56 overflow-hidden rounded-xl border border-slate-200/80 bg-white py-2 shadow-lg"
                                style="display: none"
                            >
                                <div
                                    class="px-4 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400"
                                >
                                    Módulo Financiero
                                </div>

                                <a
                                    href="{{ route('financiero.comisiones.index') }}"
                                    class="group flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-slate-700 transition-colors duration-150 hover:bg-molaris-dark hover:bg-slate-800"
                                >
                                    <svg class="h-4 w-4 text-slate-400 transition-colors duration-150 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    </svg>
                                    <span
                                        class="transition-colors duration-150 group-hover:text-white"
                                        >{{ __('Tasas de Comisión') }}</span
                                    >
                                </a>

                                <a
                                    href="{{ route('financiero.comisiones.liquidaciones.index') }}"
                                    class="group flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-slate-700 transition-colors duration-150 hover:bg-molaris-dark hover:bg-slate-800"
                                >
                                    <svg class="h-4 w-4 text-slate-400 transition-colors duration-150 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span
                                        class="transition-colors duration-150 group-hover:text-white"
                                        >{{ __('Liquidaciones') }}</span
                                    >
                                </a>

                                <a
                                    href="{{ route('financiero.pagos.cierre') }}"
                                    class="group flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-slate-700 transition-colors duration-150 hover:bg-molaris-dark hover:bg-slate-800"
                                >
                                    <svg class="h-4 w-4 text-slate-400 transition-colors duration-150 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    <span
                                        class="transition-colors duration-150 group-hover:text-white"
                                        >{{ __('Cierre de Caja') }}</span
                                    >
                                </a>

                                <div
                                    class="my-1 border-t border-slate-100"
                                ></div>

                                <a
                                    href="{{ route('financiero.presupuestos.index') }}"
                                    class="group flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-slate-700 transition-colors duration-150 hover:bg-molaris-dark"
                                >
                                    <svg class="h-4 w-4 text-slate-400 transition-colors duration-150 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span
                                        class="text-slate-700 transition-colors duration-150 group-hover:text-white"
                                        >{{ __('Presupuestos') }}</span
                                    >
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="ms-auto hidden sm:ms-6 sm:flex sm:items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center gap-2 rounded-md border border-transparent bg-molaris-primary px-3 py-2 text-sm font-medium leading-4 text-white transition duration-150 ease-in-out hover:bg-molaris-accent/20 focus:outline-none"
                        >
                            <svg class="h-4 w-4 shrink-0 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <div>
                                {{ Auth::user()->name ?? Auth::user()->nombre }}
                            </div>
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
            @if (auth()->user()->rol?->nombre === 'Administrador')
                <x-responsive-nav-link
                    :href="route('dashboard')"
                    :active="request()->routeIs('dashboard')"
                    class="text-white hover:text-molaris-accent"
                >
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            @endif

            @if (auth()->user()->rol?->nombre === 'Dentista')
                <x-responsive-nav-link
                    :href="route('agenda.personal')"
                    :active="request()->routeIs('agenda.personal')"
                    class="text-cyan-400 hover:text-cyan-300"
                >
                    {{ __('Mi Agenda') }}
                </x-responsive-nav-link>
            @endif

            <x-responsive-nav-link
                :href="route('pacientes.index')"
                :active="request()->routeIs('pacientes.*')"
                class="text-white hover:text-molaris-accent"
            >
                {{ __('Pacientes') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link
                :href="route('agenda.index')"
                :active="request()->routeIs('agenda.index')"
                class="text-white hover:text-molaris-accent"
            >
                {{ __('Agenda General') }}
            </x-responsive-nav-link>

            @if (auth()->user()->rol?->nombre === 'Administrador')
                <x-responsive-nav-link
                    :href="route('doctores.index')"
                    :active="request()->routeIs('doctores.*')"
                    class="text-white hover:text-molaris-accent"
                >
                    {{ __('Odontólogos') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link
                    :href="route('inventario.index')"
                    :active="request()->routeIs('inventario.*')"
                    class="text-white hover:text-molaris-accent"
                >
                    {{ __('Inventario') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link
                    :href="route('boxes.index')"
                    :active="request()->routeIs('boxes.*')"
                    class="text-white hover:text-molaris-accent"
                >
                    {{ __('Boxes') }}
                </x-responsive-nav-link>

                <!-- Menú Desplegable Financiero en Móvil -->
                <div x-data="{ mobileFinanciero: false }" class="space-y-1">
                    <button
                        @click="mobileFinanciero = !mobileFinanciero"
                        class="flex w-full items-center justify-between py-2 pe-4 ps-3 text-base font-medium text-white transition hover:bg-molaris-primary hover:text-molaris-accent"
                    >
                        <span>{{ __('Financiero') }}</span>
                        <svg
                            class="h-4 w-4 transition-transform duration-200"
                            :class="{ 'rotate-180': mobileFinanciero }"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div
                        x-show="mobileFinanciero"
                        class="space-y-1 bg-black/20 py-1 ps-4"
                        style="display: none"
                    >
                        <x-responsive-nav-link
                            :href="route('financiero.comisiones.index')"
                            :active="request()->routeIs('financiero.comisiones.index')"
                            class="text-slate-300 hover:bg-molaris-dark"
                        >
                            {{ __('Tasas de Comisión') }}
                        </x-responsive-nav-link>

                        <x-responsive-nav-link
                            :href="route('financiero.comisiones.liquidaciones.index')"
                            :active="request()->routeIs('financiero.comisiones.liquidaciones.*')"
                            class="text-slate-300 hover:bg-molaris-dark"
                        >
                            {{ __('Liquidaciones') }}
                        </x-responsive-nav-link>

                        <x-responsive-nav-link
                            :href="route('financiero.pagos.cierre')"
                            :active="request()->routeIs('financiero.pagos.cierre')"
                            class="text-slate-300 hover:bg-molaris-dark"
                        >
                            {{ __('Cierre de Caja') }}
                        </x-responsive-nav-link>

                        <x-responsive-nav-link
                            :href="route('financiero.presupuestos.index')"
                            :active="request()->routeIs('financiero.presupuestos.*')"
                            class="text-slate-300 hover:bg-molaris-dark"
                        >
                            {{ __('Presupuestos') }}
                        </x-responsive-nav-link>
                    </div>
                </div>
            @endif
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
