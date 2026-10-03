<x-app-layout>
    <x-slot name="header">
        <div
            class="flex w-full flex-col gap-4 md:flex-row md:items-center md:justify-between"
        >
            <div>
                <h2 class="text-2xl font-bold leading-tight text-molaris-dark">
                    {{ __('Panel Principal') }}
                </h2>
                <p class="mt-1 text-xs text-slate-500">Bienvenido/a, {{ Auth::user()->name ?? Auth::user()->nombre ?? 'Usuario' }}. Resumen operativo de la Clínica Venedental.</p>
            </div>

            <!-- Acciones Rápidas del Header -->
            <div class="flex items-center gap-2">
                <a
                    href="{{ url('/pacientes/create') }}"
                    class="inline-flex shrink-0 items-center gap-1.5 rounded-lg border border-slate-200 bg-slate-100 px-3 py-2 text-xs font-semibold text-molaris-dark transition hover:-translate-y-0.5 hover:bg-slate-200"
                >
                    <svg class="h-4 w-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    Nuevo Paciente
                </a>
                <a
                    href="{{ url('/agenda') }}"
                    class="shadow-xs inline-flex shrink-0 items-center gap-1.5 rounded-lg bg-molaris-dark px-3.5 py-2 text-xs font-semibold text-white transition hover:-translate-y-0.5 hover:bg-black"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nueva Cita
                </a>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen w-full overflow-x-hidden bg-molaris-bg py-6">
        <div class="mx-auto w-full max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <!-- 1. TARJETAS DE MÉTRICAS (KPIs) -->
            <div
                class="grid w-full grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4"
            >
                <!-- Citas Hoy -->
                <div
                    class="shadow-xs flex items-center justify-between rounded-xl border border-slate-200 bg-white p-5"
                >
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Citas de Hoy</p>
                        <h3
                            class="mt-0.5 text-2xl font-extrabold text-molaris-dark"
                        >
                            {{ $citasHoyCount ?? (isset($citasHoy) ? count($citasHoy) : 0) }}
                        </h3>
                        <span
                            class="mt-1 flex items-center gap-1 text-[11px] font-semibold text-cyan-600"
                        >
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Agenda del día
                        </span>
                    </div>
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-blue-50 text-molaris-primary"
                    >
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Total Pacientes -->
                <div
                    class="shadow-xs flex items-center justify-between rounded-xl border border-slate-200 bg-white p-5"
                >
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Pacientes Fichados</p>
                        <h3
                            class="mt-0.5 text-2xl font-extrabold text-molaris-dark"
                        >
                            {{ $pacientesTotal ?? 0 }}
                        </h3>
                        <span
                            class="mt-1 flex items-center gap-1 text-[11px] font-semibold text-emerald-600"
                        >
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                            Registrados
                        </span>
                    </div>
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-molaris-mint"
                    >
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Odontólogos -->
                <div
                    class="shadow-xs flex items-center justify-between rounded-xl border border-slate-200 bg-white p-5"
                >
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Odontólogos</p>
                        <h3
                            class="mt-0.5 text-2xl font-extrabold text-molaris-dark"
                        >
                            {{ $doctoresTotal ?? 0 }}
                        </h3>
                        <span
                            class="mt-1 flex items-center gap-1 text-[11px] font-semibold text-slate-500"
                        >
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Staff disponible
                        </span>
                    </div>
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-cyan-50 text-molaris-accent"
                    >
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Boxes -->
                <div
                    class="shadow-xs flex items-center justify-between rounded-xl border border-slate-200 bg-white p-5"
                >
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Boxes Disponibles</p>
                        <h3
                            class="mt-0.5 text-2xl font-extrabold text-molaris-dark"
                        >
                            {{ $boxesTotal ?? 0 }}
                        </h3>
                        <span
                            class="mt-1 flex items-center gap-1 text-[11px] font-semibold text-amber-600"
                        >
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0h4m-4 0H9" />
                            </svg>
                            Equipamiento activo
                        </span>
                    </div>
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-amber-50 text-amber-600"
                    >
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                        </svg>
                    </div>
                </div>
            </div>
           
            <!-- 2. ACCESOS RÁPIDOS AL SISTEMA -->
            <div
                class="shadow-xs w-full rounded-2xl border border-slate-200/80 bg-white p-5"
            >
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span
                            class="h-2 w-2 rounded-full"
                            style="background-color: #06b6d4"
                        ></span>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Accesos Rápidos al Sistema</p>
                    </div>
                </div>

                <!-- Contenedor con separación entre botones -->
                <div
                    class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4"
                >
                    <!-- Gestión Pacientes -->
                    <a
                        href="{{ url('/pacientes') }}"
                        class="group flex items-center rounded-xl border p-3.5 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
                        style="background-color: #f0f9ff; border-color: #bae6fd"
                    >
                        <div
                            class="flex shrink-0 items-center justify-center text-white shadow-sm transition-transform duration-200 group-hover:scale-105"
                            style="
                                background-color: #0284c7;
                                width: 44px;
                                height: 44px;
                                min-width: 44px;
                                min-height: 44px;
                                border-radius: 9999px;
                                aspect-ratio: 1 / 1;
                                margin-right: 16px;
                            "
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span
                                class="text-sm font-bold text-slate-800 transition-colors group-hover:text-sky-900"
                            >
                                Gestión Pacientes
                            </span>
                            <span
                                class="text-[11px] font-medium text-slate-500"
                            >
                                Fichas y registros
                            </span>
                        </div>
                    </a>

                    <!-- Agenda Médica -->
                    <a
                        href="{{ url('/agenda') }}"
                        class="group flex items-center rounded-xl border p-3.5 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
                        style="background-color: #f0fdf4; border-color: #bbf7d0"
                    >
                        <div
                            class="flex shrink-0 items-center justify-center text-white shadow-sm transition-transform duration-200 group-hover:scale-105"
                            style="
                                background-color: #16a34a;
                                width: 44px;
                                height: 44px;
                                min-width: 44px;
                                min-height: 44px;
                                border-radius: 9999px;
                                aspect-ratio: 1 / 1;
                                margin-right: 16px;
                            "
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span
                                class="text-sm font-bold text-slate-800 transition-colors group-hover:text-emerald-900"
                            >
                                Agenda Médica
                            </span>
                            <span
                                class="text-[11px] font-medium text-slate-500"
                            >
                                Citas y calendario
                            </span>
                        </div>
                    </a>

                    <!-- Odontólogos -->
                    <a
                        href="{{ url('/doctores') }}"
                        class="group flex items-center rounded-xl border p-3.5 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
                        style="background-color: #f5f3ff; border-color: #ddd6fe"
                    >
                        <div
                            class="flex shrink-0 items-center justify-center text-white shadow-sm transition-transform duration-200 group-hover:scale-105"
                            style="
                                background-color: #7c3aed;
                                width: 44px;
                                height: 44px;
                                min-width: 44px;
                                min-height: 44px;
                                border-radius: 9999px;
                                aspect-ratio: 1 / 1;
                                margin-right: 16px;
                            "
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span
                                class="text-sm font-bold text-slate-800 transition-colors group-hover:text-violet-900"
                            >
                                Odontólogos
                            </span>
                            <span
                                class="text-[11px] font-medium text-slate-500"
                            >
                                Personal médico
                            </span>
                        </div>
                    </a>

                    <!-- Boxes / Pabellones -->
                    <a
                        href="{{ url('/boxes') }}"
                        class="group flex items-center rounded-xl border p-3.5 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
                        style="background-color: #fffbeb; border-color: #fde68a"
                    >
                        <div
                            class="flex shrink-0 items-center justify-center text-white shadow-sm transition-transform duration-200 group-hover:scale-105"
                            style="
                                background-color: #d97706;
                                width: 44px;
                                height: 44px;
                                min-width: 44px;
                                min-height: 44px;
                                border-radius: 9999px;
                                aspect-ratio: 1 / 1;
                                margin-right: 16px;
                            "
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span
                                class="text-sm font-bold text-slate-800 transition-colors group-hover:text-amber-900"
                            >
                                Boxes / Pabellones
                            </span>
                            <span
                                class="text-[11px] font-medium text-slate-500"
                            >
                                Salas de atención
                            </span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- 3. TABLA DE CITAS DE HOY (A ANCHO COMPLETO) -->
            <div
                class="shadow-xs w-full overflow-hidden rounded-xl border border-slate-200 bg-white"
            >
                <div
                    class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 p-5"
                >
                    <div>
                        <h3
                            class="flex items-center gap-2 text-base font-bold text-molaris-dark"
                        >
                            <svg class="h-5 w-5 shrink-0 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Agenda para el Día de Hoy
                        </h3>
                        <p class="mt-0.5 text-xs text-slate-500">Listado de atención médica programada para la jornada</p>
                    </div>
                    <a
                        href="{{ url('/agenda') }}"
                        class="flex shrink-0 items-center gap-1 text-xs font-bold text-molaris-primary transition hover:text-molaris-dark"
                    >
                        <span>Ver agenda completa</span>
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>

                <div class="w-full overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-700">
                        <thead
                            class="border-b border-slate-100 bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500"
                        >
                            <tr>
                                <th class="px-5 py-3">Hora</th>
                                <th class="px-5 py-3">Paciente</th>
                                <th class="px-5 py-3">Odontólogo</th>
                                <th class="px-5 py-3">Box</th>
                                <th class="px-5 py-3">Estado</th>
                                <th class="px-5 py-3 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($citasHoy ?? [] as $cita)
                                <tr class="transition hover:bg-slate-50/80">
                                    <td
                                        class="whitespace-nowrap px-5 py-4 font-bold text-molaris-dark"
                                    >
                                        {{ isset($cita->fecha_hora) ? \Carbon\Carbon::parse($cita->fecha_hora)->format('H:i') : ($cita->hora ?? '--:--') }} hrs
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <div
                                            class="text-xs font-bold text-slate-900"
                                        >
                                            {{ $cita->paciente->nombre ?? $cita->paciente_nombre ?? 'Sin nombre' }}
                                        </div>
                                        <div
                                            class="mt-0.5 font-mono text-[11px] text-slate-500"
                                        >
                                            {{ $cita->paciente->rut ?? $cita->paciente_rut ?? '' }}
                                        </div>
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-5 py-4 text-xs font-medium text-slate-700"
                                    >
                                        {{ $cita->doctor->usuario->name ?? $cita->doctor->nombre ?? $cita->doctor_nombre ?? 'Sin asignar' }}
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <span
                                            class="inline-flex items-center rounded-md border border-slate-200 bg-molaris-dark px-2.5 py-0.5 text-xs font-semibold text-white"
                                        >
                                            {{ $cita->box->nombre ?? $cita->box_nombre ?? 'Box N/A' }}
                                        </span>
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-900"
                                    >
                                        @switch ($cita->estado)
                                            @case ('confirmada')
                                                <span
                                                    class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold leading-5 text-emerald-800"
                                                >
                                                    ✓ Confirmada
                                                </span>
                                                @break
                                            @case ('rechazada')
                                                <span
                                                    class="inline-flex items-center gap-1 rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-semibold leading-5 text-rose-800"
                                                >
                                                    ✕ Rechazada
                                                </span>
                                                @break
                                            @default
                                                <span
                                                    class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold leading-5 text-amber-800"
                                                >
                                                    ⏳ Por Confirmar
                                                </span>
                                        @endswitch
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-5 py-4 text-center"
                                    >
                                        <div
                                            class="inline-flex items-center justify-center gap-2"
                                        >
                                            <!-- Contacto WhatsApp -->
                                            @php $tlf = $cita->paciente->telefono ?? $cita->paciente_telefono ?? ''; @endphp
                                            @if (!empty($tlf))
                                                <a
                                                    href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tlf) }}?text={{ urlencode('Hola '.($cita->paciente->nombre ?? 'Estimado/a').', le escribimos de Clínica Venedental sobre su cita agendada.') }}"
                                                    target="_blank"
                                                    title="Contactar paciente por WhatsApp"
                                                    class="shadow-xs inline-flex shrink-0 items-center justify-center rounded-lg bg-emerald-600 p-2 text-white transition-colors duration-200 hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-1"
                                                >
                                                    <!-- Ícono oficial de WhatsApp -->
                                                    <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24">
                                                        <path d="M12.012 2c-5.506 0-9.989 4.478-9.99 9.984a9.932 9.932 0 001.356 5.011L2 22l5.133-1.336a9.938 9.938 0 004.873 1.28h.005c5.507 0 9.99-4.479 9.99-9.985 0-2.667-1.038-5.174-2.924-7.06C17.19 3.033 14.682 2 12.012 2zm5.836 14.168c-.244.688-1.42 1.314-1.956 1.396-.51.077-1.168.109-1.884-.12-.435-.138-1.002-.323-1.728-.636-3.048-1.317-5.035-4.38-5.187-4.582-.153-.203-1.238-1.644-1.238-3.136 0-1.492.78-2.226 1.056-2.508.276-.282.6-.352.8-.352.201 0 .402.002.576.01.184.008.43-.07.674.515.245.586.837 2.042.91 2.191.073.149.122.324.024.519-.098.195-.147.316-.293.488-.146.172-.308.384-.44.516-.146.147-.298.307-.128.598.17.291.758 1.248 1.626 2.02 1.116.992 2.057 1.3 2.348 1.446.291.147.462.122.633-.073.171-.195.733-.854.928-1.147.195-.293.39-.244.659-.147.269.098 1.708.805 2.001.952.293.147.488.22.561.342.073.122.073.71-.171 1.398z" />
                                                    </svg>
                                                </a>
                                            @endif

                                            <!-- Rechazar Cita (Botón Rojo con X) -->
                                            @if (($cita->estado ?? '') !== 'rechazada' && ($cita->estado ?? '') !== 'cancelada')
                                                <form
                                                    action="{{ url('/agenda/'.$cita->id.'/rechazar') }}"
                                                    method="POST"
                                                    class="inline"
                                                >
                                                    @csrf
                                                    @method ('PATCH')
                                                    <button
                                                        type="submit"
                                                        title="Rechazar Cita"
                                                        class="shadow-xs inline-flex shrink-0 cursor-pointer items-center justify-center rounded-lg bg-red-600 p-2 text-white transition-colors duration-200 hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-1"
                                                    >
                                                        <!-- Ícono de X blanca sobre fondo rojo -->
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td
                                        colspan="6"
                                        class="px-6 py-10 text-center text-xs font-semibold text-slate-500"
                                    >
                                        No hay citas agendadas para el día de
                                        hoy.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 4. PANEL INFORMATIVO DEL SISTEMA -->
            <div
                class="shadow-xs w-full rounded-xl border border-slate-200 bg-white p-5"
            >
                <h4
                    class="mb-4 flex items-center gap-2 border-b border-slate-100 pb-3 text-sm font-bold text-molaris-dark"
                >
                    <svg class="h-4 w-4 shrink-0 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    INFORMACIÓN DEL SISTEMA
                </h4>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div
                        class="space-y-1.5 rounded-xl border border-blue-200/80 bg-blue-50/80 p-3.5"
                    >
                        <span
                            class="flex items-center gap-1.5 text-xs font-bold text-blue-900"
                        >
                            <svg class="h-3.5 w-3.5 shrink-0 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 01-2 2h-1a2 2 0 01-2-2v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                            Molaris Software Dental
                        </span>
                        <p class="text-[11px] font-medium leading-relaxed text-blue-800">Gestión médica centralizada. Recuerda verificar el estado de los boxes y la asignación de odontólogos antes del inicio de la jornada.</p>
                    </div>

                    <div
                        class="space-y-2 rounded-xl border border-slate-200 bg-slate-50 p-3.5"
                    >
                        <span
                            class="flex items-center gap-1.5 text-xs font-bold text-slate-800"
                        >
                            <svg class="h-3.5 w-3.5 shrink-0 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Estado del Sistema
                        </span>
                        <div
                            class="flex items-center gap-4 text-xs font-semibold text-slate-700"
                        >
                            <div class="flex items-center gap-1.5">
                                <span
                                    class="h-2 w-2 shrink-0 rounded-full bg-emerald-500"
                                ></span>
                                <span
                                    >BD:
                                    <strong class="text-slate-900"
                                        >Conectada</strong
                                    ></span
                                >
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span
                                    class="h-2 w-2 shrink-0 rounded-full bg-emerald-500"
                                ></span>
                                <span
                                    >Fichas Médicas:
                                    <strong class="text-slate-900"
                                        >Activas</strong
                                    ></span
                                >
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span
                                    class="h-2 w-2 shrink-0 rounded-full bg-emerald-500"
                                ></span>
                                <span
                                    >Estado de Agenda:
                                    <strong class="text-slate-900"
                                        >Abierta</strong
                                    ></span
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
