<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ config('app.name', 'Molaris - Software Dental') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-100 text-slate-800">
    <div x-data="{ sidebarOpen: true, mobileSidebarOpen: false }" class="min-h-screen flex">

        <!-- SIDEBAR LATERAL -->
        @include('layouts.sidebar')

        <!-- CONTENIDO PRINCIPAL -->
        <div class="flex-1 flex flex-col min-w-0 min-h-screen transition-all duration-300"
             :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-20'">
            
            <!-- HEADER SUPERIOR -->
            <header class="bg-molaris-dark border-b border-slate-700/60 sticky top-0 z-30 shadow-md h-16 flex items-center px-4 sm:px-6 justify-between text-white">
                <div class="flex items-center gap-3">
                    <!-- Toggle Sidebar Desktop -->
                    <button @click="sidebarOpen = !sidebarOpen" class="hidden lg:flex p-2 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white focus:outline-none transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <!-- Toggle Sidebar Móvil -->
                    <button @click="mobileSidebarOpen = !mobileSidebarOpen" class="lg:hidden p-2 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white focus:outline-none transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    @isset ($header)
                        <div class="text-white font-bold text-lg leading-tight">
                            {{ $header }}
                        </div>
                    @endisset
                </div>

                <!-- MENU DE USUARIO DERECHA -->
                <div class="flex items-center gap-4">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-3 p-1.5 rounded-xl hover:bg-slate-800 transition text-left">
                                <div class="w-8 h-8 rounded-lg overflow-hidden bg-emerald-100 text-emerald-800 font-bold flex items-center justify-center shrink-0 border border-emerald-300">
                                    @if(auth()->user()->avatar)
                                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-full h-full object-cover">
                                    @else
                                        {{ substr(auth()->user()->name ?? auth()->user()->nombre ?? 'U', 0, 2) }}
                                    @endif
                                </div>
                                <div class="hidden sm:block">
                                    <div class="text-xs font-bold text-white leading-tight">
                                        {{ Auth::user()->name ?? Auth::user()->nombre }}
                                    </div>
                                    <div class="text-[10px] text-emerald-400 font-semibold">
                                        {{ Auth::user()->rol?->nombre ?? 'Usuario' }}
                                    </div>
                                </div>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')" class="hover:bg-emerald-50 hover:text-emerald-700">
                                {{ __('Mi Perfil') }}
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="hover:bg-emerald-50 hover:text-emerald-700">
                                    {{ __('Cerrar Sesión') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </header>

            <!-- VISTA DEL CONTENIDO (Slot o Yield) -->
            <main class="flex-1">
                {{ $slot ?? '' }}
                @yield('contenido')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>