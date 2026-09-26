<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-molaris-dark leading-tight">
                    {{ __('Panel Principal') }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Bienvenido/a, {{ Auth::user()->name ?? Auth::user()->nombre }}. Resumen general de la Clínica Venedental.
                </p>
            </div>
            
            <!-- Acciones Rápidas -->
            <div class="flex items-center gap-2">
                <a href="{{ route('pacientes.create') }}" class="inline-flex items-center px-3 py-2 bg-slate-100 hover:bg-slate-200 text-molaris-dark font-semibold text-xs rounded-lg transition">
                    + Paciente
                </a>
                @if(Route::has('citas.create'))
                    <a href="{{ route('citas.create') }}" class="inline-flex items-center px-3 py-2 bg-molaris-dark hover:bg-molaris-primary text-white font-semibold text-xs rounded-lg shadow transition">
                        + Nueva Cita
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-molaris-bg min-h-[calc(100vh-8rem)]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- 1. TARJETAS DE MÉTRICAS (KPIs) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- Citas Hoy -->
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Citas de Hoy</p>
                        <h3 class="text-3xl font-bold text-molaris-dark mt-1">{{ $citasHoyCount }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 text-molaris-primary rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Total Pacientes -->
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Pacientes Fichados</p>
                        <h3 class="text-3xl font-bold text-molaris-dark mt-1">{{ $pacientesTotal }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 text-molaris-mint rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Odontólogos -->
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Odontólogos</p>
                        <h3 class="text-3xl font-bold text-molaris-dark mt-1">{{ $doctoresTotal }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-cyan-50 text-molaris-accent rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Boxes -->
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Boxes Disponibles</p>
                        <h3 class="text-3xl font-bold text-molaris-dark mt-1">{{ $boxesTotal }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0h4m-4 0H9"></path>
                        </svg>
                    </div>
                </div>

            </div>

            <!-- 2. TABLA DE PRÓXIMAS CITAS DEL DÍA -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-molaris-dark">Agenda para el Día de Hoy</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Listado de atención médica programada para la jornada</p>
                    </div>
                    @if(Route::has('citas.index'))
                        <a href="{{ route('citas.index') }}" class="text-xs font-semibold text-molaris-primary hover:text-molaris-dark transition">
                            Ver agenda completa &rarr;
                        </a>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3">Hora</th>
                                <th class="px-6 py-3">Paciente</th>
                                <th class="px-6 py-3">Odontólogo</th>
                                <th class="px-6 py-3">Box</th>
                                <th class="px-6 py-3">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($citasHoy as $cita)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4 font-semibold text-molaris-dark">
                                        {{ \Carbon\Carbon::parse($cita->fecha_hora)->format('H:i') }} hrs
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-800">{{ $cita->paciente->nombre ?? 'N/A' }}</div>
                                        <div class="text-xs text-slate-400">{{ $cita->paciente->rut ?? '' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $cita->doctor->usuario->name ?? $cita->doctor->usuario->nombre ?? 'Sin asignar' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                            {{ $cita->box->nombre ?? 'Box N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if(($cita->estado ?? 'confirmada') === 'confirmada')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                                Confirmada
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                {{ ucfirst($cita->estado ?? 'Pendiente') }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                                        No hay citas agendadas para el día de hoy.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>