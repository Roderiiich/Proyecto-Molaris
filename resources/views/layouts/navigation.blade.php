<nav x-data="{ open: false }" class="bg-molaris-dark border-b border-molaris-accent/20 shadow-md">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
               
                     <!-- Logo Molaris (Software Dental) -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
                        
                        <!-- Nombre de la Clínica y Subtítulo -->
                        <div class="flex flex-col justify-center">
                            <!-- MOLARIS: Grande y en negrita -->
                            <span class="text-white font-bold tracking-wider leading-none group-hover:text-cyan-400 transition" style="font-size: 1.45rem;">
                                MOLARIS
                            </span>
                            <!-- Software Dental: Fino, pequeño y en minúsculas/capitalizado -->
                            <span class="text-slate-300 font-normal tracking-normal leading-none" style="font-size: 0.75rem;">
                                Software Dental
                            </span>
                        </div>

                    </a>
                </div>
                
                <!-- Navigation Links (Escritorio) -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <!-- Dashboard -->
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white hover:text-molaris-accent font-semibold transition">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <!-- Pacientes -->
                    <x-nav-link :href="route('pacientes.index')" :active="request()->routeIs('pacientes.*')" class="text-white hover:text-molaris-accent font-semibold transition">
                        {{ __('Pacientes') }}
                    </x-nav-link>

                    <!-- Odontólogos -->
                    <x-nav-link :href="route('doctores.index')" :active="request()->routeIs('doctores.*')" class="text-white hover:text-molaris-accent font-semibold transition">
                        {{ __('Odontólogos') }}
                    </x-nav-link>

                    <!-- Boxes de Atención -->
                    <x-nav-link :href="route('boxes.index')" :active="request()->routeIs('boxes.*')" class="text-white hover:text-molaris-accent font-semibold transition">
                        {{ __('Boxes') }}
                    </x-nav-link>

                    <!-- Agenda / Citas -->
                    <x-nav-link :href="url('/agenda')" :active="request()->is('agenda*')" class="text-white hover:text-molaris-accent font-semibold transition">
                        {{ __('Agenda') }}
                    </x-nav-link>
                </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-molaris-primary hover:bg-molaris-accent/20 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name ?? Auth::user()->nombre }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
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
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Cerrar Sesión') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            
        </div>
    </div>
</nav>