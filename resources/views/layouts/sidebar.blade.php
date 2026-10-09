<!-- BACKDROP MÓVIL -->
<div x-show="mobileSidebarOpen" 
     @click="mobileSidebarOpen = false"
     x-transition:enter="transition-opacity ease-linear duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden" 
     style="display: none;"></div>

<!-- SIDEBAR MAESTRA -->
<aside class="fixed top-0 bottom-0 left-0 z-50 bg-molaris-dark text-slate-300 flex flex-col transition-all duration-300 shadow-xl border-r border-slate-800"
       :class="{
           'w-64': sidebarOpen,
           'w-20': !sidebarOpen,
           'translate-x-0': mobileSidebarOpen,
           '-translate-x-full lg:translate-x-0': !mobileSidebarOpen
       }">

    <!-- LOGO Y BRANDING -->
    <div class="h-16 flex items-center px-4 border-b border-slate-800/80 bg-slate-900/50 justify-between shrink-0">
        <a href="{{ auth()->user()->rol?->nombre === 'Dentista' ? route('agenda.personal') : route('dashboard') }}" class="flex items-center gap-3 overflow-hidden">
            <div class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center shrink-0 shadow-lg text-white font-black text-xl">
                M
            </div>
            <div class="flex flex-col whitespace-nowrap transition-opacity duration-200" x-show="sidebarOpen">
                <span class="font-extrabold text-white text-base tracking-wider leading-none">MOLARIS</span>
                <span class="text-[10px] text-emerald-400 font-semibold tracking-normal mt-0.5">Software Dental</span>
            </div>
        </a>
    </div>

    <!-- MENÚ DE NAVEGACIÓN PRINCIPAL -->
    <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1 custom-scrollbar">

        <!-- 1. PRINCIPAL -->
        <div class="px-3 pb-2 text-[10px] font-bold uppercase tracking-wider text-slate-500" x-show="sidebarOpen">
            Principal
        </div>

        @if (in_array(auth()->user()->rol?->nombre, ['Administrador', 'Recepción', 'Recepcionista']))
            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-xs transition {{ request()->routeIs('dashboard') ? 'bg-emerald-600 text-white font-bold shadow-md' : 'hover:bg-slate-800/80 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Dashboard</span>
            </a>

            <a href="{{ route('agenda.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-xs transition {{ request()->routeIs('agenda.index') ? 'bg-emerald-600 text-white font-bold shadow-md' : 'hover:bg-slate-800/80 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Agenda General</span>
            </a>
        @endif

        @if (auth()->user()->rol?->nombre === 'Dentista')
            <a href="{{ route('agenda.personal') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-xs transition {{ request()->routeIs('agenda.personal') ? 'bg-emerald-600 text-white font-bold shadow-md' : 'hover:bg-slate-800/80 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Mi Agenda</span>
            </a>

            <a href="{{ route('financiero.presupuestos.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-xs transition {{ request()->routeIs('financiero.presupuestos.*') ? 'bg-emerald-600 text-white font-bold shadow-md' : 'hover:bg-slate-800/80 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Presupuestos</span>
            </a>
        @endif

        <a href="{{ route('pacientes.index') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-xs transition {{ request()->routeIs('pacientes.*') ? 'bg-emerald-600 text-white font-bold shadow-md' : 'hover:bg-slate-800/80 hover:text-white' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span x-show="sidebarOpen" class="whitespace-nowrap">Pacientes</span>
        </a>

        <!-- 2. DESPLEGABLE MÓDULO DE GESTIÓN (ADMINISTRADOR) -->
        @if (auth()->user()->rol?->nombre === 'Administrador')
            <div class="pt-4 px-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-500" x-show="sidebarOpen">
                Administración
            </div>

            <div x-data="{ open: {{ request()->routeIs('admin.usuarios.*', 'doctores.*', 'boxes.*', 'inventario.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" 
                        class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-medium transition hover:bg-slate-800/80 hover:text-white"
                        :class="{ 'text-emerald-400 font-bold': open }">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                        <span x-show="sidebarOpen" class="whitespace-nowrap">Gestión</span>
                    </div>
                    <svg x-show="sidebarOpen" class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <div x-show="open && sidebarOpen" x-collapse class="pl-8 pr-2 py-1 space-y-1">
                    <a href="{{ route('admin.usuarios.index') }}" class="block px-3 py-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('admin.usuarios.*') ? 'text-emerald-400 font-bold bg-slate-800' : 'text-slate-400 hover:text-white' }}">Roles y Usuarios</a>
                    <a href="{{ route('doctores.index') }}" class="block px-3 py-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('doctores.*') ? 'text-emerald-400 font-bold bg-slate-800' : 'text-slate-400 hover:text-white' }}">Odontólogos</a>
                    <a href="{{ route('boxes.index') }}" class="block px-3 py-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('boxes.*') ? 'text-emerald-400 font-bold bg-slate-800' : 'text-slate-400 hover:text-white' }}">Boxes</a>
                    <a href="{{ route('inventario.index') }}" class="block px-3 py-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('inventario.*') ? 'text-emerald-400 font-bold bg-slate-800' : 'text-slate-400 hover:text-white' }}">Inventario</a>
                </div>
            </div>
        @endif

        <!-- 3. DESPLEGABLE FINANCIERO -->
        @if (in_array(auth()->user()->rol?->nombre, ['Administrador', 'Recepción', 'Recepcionista']))
            <div x-data="{ open: {{ request()->is('financiero*') ? 'true' : 'false' }} }">
                <button @click="open = !open" 
                        class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-medium transition hover:bg-slate-800/80 hover:text-white"
                        :class="{ 'text-emerald-400 font-bold': open }">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span x-show="sidebarOpen" class="whitespace-nowrap">Finanzas</span>
                    </div>
                    <svg x-show="sidebarOpen" class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <div x-show="open && sidebarOpen" x-collapse class="pl-8 pr-2 py-1 space-y-1">
                    @if (auth()->user()->rol?->nombre === 'Administrador')
                        <a href="{{ route('financiero.comisiones.index') }}" class="block px-3 py-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('financiero.comisiones.index') ? 'text-emerald-400 font-bold bg-slate-800' : 'text-slate-400 hover:text-white' }}">Comisiones</a>
                        <a href="{{ route('financiero.comisiones.liquidaciones.index') }}" class="block px-3 py-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('financiero.comisiones.liquidaciones.*') ? 'text-emerald-400 font-bold bg-slate-800' : 'text-slate-400 hover:text-white' }}">Liquidaciones</a>
                    @endif
                    <a href="{{ route('financiero.pagos.cierre') }}" class="block px-3 py-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('financiero.pagos.cierre') ? 'text-emerald-400 font-bold bg-slate-800' : 'text-slate-400 hover:text-white' }}">Cierre de Caja</a>
                    <a href="{{ route('financiero.presupuestos.index') }}" class="block px-3 py-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('financiero.presupuestos.*') ? 'text-emerald-400 font-bold bg-slate-800' : 'text-slate-400 hover:text-white' }}">Presupuestos</a>
                </div>
            </div>
        @endif

    </div>

    <!-- PIE CON INFORMACIÓN DEL ROL -->
    <div class="p-3 border-t border-slate-800/80 bg-slate-900/40 shrink-0">
        <div class="flex items-center gap-3">
            <span class="w-2.5 h-2.5 rounded-full shrink-0
                @if(auth()->user()->rol?->nombre === 'Administrador') bg-red-500
                @elseif(auth()->user()->rol?->nombre === 'Dentista') bg-blue-500
                @elseif(in_array(auth()->user()->rol?->nombre, ['Recepción', 'Recepcionista'])) bg-emerald-500
                @else bg-slate-500 @endif">
            </span>
            <div class="text-[11px] font-semibold text-slate-400 overflow-hidden" x-show="sidebarOpen">
                <span class="block truncate leading-tight text-white">{{ auth()->user()->rol?->nombre ?? 'Sin Rol' }}</span>
                <span class="text-[10px] text-slate-500">Molaris System v1.0</span>
            </div>
        </div>
    </div>
</aside>