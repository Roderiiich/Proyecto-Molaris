@extends ('layouts.app')

@section ('titulo', 'Configuración de Tasas de Comisión')

@section ('contenido')
    <div class="mx-auto max-w-6xl space-y-6">
        @if (session('exito'))
            <div
                class="flex items-center gap-3 rounded-2xl border border-emerald-200/80 bg-emerald-50/80 p-4 text-sm font-medium text-emerald-900 shadow-sm backdrop-blur-sm"
            >
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span>{{ session('exito') }}</span>
            </div>
        @endif

        {{-- Encabezado --}}
        <div
            class="flex flex-col justify-between gap-4 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm sm:flex-row sm:items-center"
        >
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                        Tasas de Comisión
                    </h1>
                    <p class="mt-0.5 text-xs sm:text-sm text-slate-500">Configura el porcentaje de comisión asignado a cada profesional según su especialidad.</p>
                </div>
            </div>
            <a
                href="{{ route('financiero.comisiones.liquidaciones.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-800 px-4 py-2.5 text-center text-sm font-semibold text-white shadow-sm transition-all duration-150 ease-in-out hover:bg-slate-900 hover:shadow"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Ir a Liquidaciones
            </a>
        </div>

        {{-- Formulario para asignar/actualizar tasa --}}
        <div
            class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm"
        >
            <div class="mb-5 flex items-center gap-2 border-b border-slate-100 pb-3">
                <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <h2 class="text-base font-bold text-slate-800">
                    Asignar o Modificar Tasa
                </h2>
            </div>

            <form
                action="{{ route('financiero.comisiones.store') }}"
                method="POST"
                class="rounded-2xl border border-slate-100 bg-slate-50/50 p-5 transition-all"
            >
                @csrf

                <div class="mb-5 grid grid-cols-1 gap-4 md:grid-cols-3">
                    {{-- 1. Selector de Odontólogo --}}
                    <div>
                        <label
                            class="mb-1 block text-xs font-semibold uppercase tracking-wider text-slate-700"
                            >Odontólogo <span class="text-red-500">*</span></label
                        >
                        <select
                            name="dentista_id"
                            required
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-800 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                        >
                            <option value="">Seleccionar Odontólogo...</option>
                            @foreach ($dentistas as $dentista)
                                @if ($dentista->usuario)
                                    <option
                                        value="{{ $dentista->usuario->id }}"
                                    >
                                        {{ $dentista->usuario->nombre ?? $dentista->usuario->name }} {{ $dentista->usuario->apellido ?? '' }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    {{-- 2. Selector de Especialidad --}}
                    <div>
                        <label
                            class="mb-1 block text-xs font-semibold uppercase tracking-wider text-slate-700"
                            >Especialidad <span class="text-red-500">*</span></label
                        >
                        <select
                            name="especialidad"
                            required
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-800 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                        >
                            <option value="">
                                Seleccionar Especialidad...
                            </option>
                            @foreach ($especialidadesDisponibles as $esp)
                                <option value="{{ $esp }}">{{ $esp }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 3. Porcentaje --}}
                    <div>
                        <label
                            class="mb-1 block text-xs font-semibold uppercase tracking-wider text-slate-700"
                            >Porcentaje de Comisión (%) <span class="text-red-500">*</span></label
                        >
                        <div class="relative">
                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                max="100"
                                name="porcentaje"
                                required
                                placeholder="Ej: 45.00"
                                class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-3 pr-8 text-sm text-slate-800 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                            />
                            <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-xs font-bold text-slate-400">%</span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end border-t border-slate-200/60 pt-4">
                    <button
                        type="submit"
                        class="inline-flex cursor-pointer items-center gap-2 rounded-xl bg-molaris-dark px-5 py-2.5 text-sm font-bold text-white shadow-md transition duration-150 ease-in-out hover:-translate-y-0.5 hover:bg-black"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Guardar Tasa
                    </button>
                </div>
            </form>
        </div>

        {{-- Listado de Tasas Configuradas --}}
        <div
            class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm"
        >
            <div class="border-b border-slate-100 bg-slate-50/70 px-6 py-4">
                <h2 class="text-sm font-bold tracking-wide text-slate-800">
                    Tasas Configuradas por Odontólogo
                </h2>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse ($dentistas as $dentista)
                    @php
                        // Soporte híbrido para buscar por ID de usuario o por ID de doctor
                        $uId = $dentista->usuario->id ?? null;
                        $dId = $dentista->id;

                        $tasasDentista = $uId && $tasas->has($uId) 
                            ? $tasas->get($uId) 
                            : ($tasas->has($dId) ? $tasas->get($dId) : collect());

                        $nombreCompleto = trim(($dentista->usuario->nombre ?? $dentista->usuario->name ?? '') . ' ' . ($dentista->usuario->apellido ?? ''));
                    @endphp

                    <div class="p-6 transition-colors hover:bg-slate-50/30">
                        <div class="mb-4 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 font-bold text-indigo-600 shadow-sm ring-1 ring-indigo-100"
                                >
                                    {{ strtoupper(substr($nombreCompleto ?: 'DR', 0, 2)) }}
                                </div>
                                <div>
                                    <h3
                                        class="text-sm font-bold text-slate-800"
                                    >
                                        {{ $nombreCompleto ?: 'Odontólogo' }}
                                    </h3>
                                    <p class="text-xs text-slate-400">{{ $dentista->usuario->email ?? '' }}</p>
                                </div>
                            </div>
                            <span
                                class="inline-flex items-center rounded-lg bg-indigo-50/80 px-2.5 py-1 text-xs font-semibold text-indigo-700 ring-1 ring-indigo-200/50"
                            >
                                {{ $tasasDentista->count() }} {{ Str::plural('especialidad', $tasasDentista->count()) }}
                            </span>
                        </div>

                        @if ($tasasDentista->isNotEmpty())
                            <div
                                class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3"
                            >
                                @foreach ($tasasDentista as $tasa)
                                    <div
                                        x-data="{ openEdit: false }"
                                        class="relative flex items-center justify-between rounded-xl border border-slate-200/80 bg-slate-50/80 p-3.5 transition hover:border-slate-300 hover:bg-white hover:shadow-sm"
                                    >
                                        {{-- Información de la Tasa --}}
                                        <div class="flex flex-col">
                                            <span
                                                class="text-xs font-semibold text-slate-700"
                                            >
                                                {{ $tasa->especialidad }}
                                            </span>
                                            <span
                                                class="mt-0.5 text-xs font-black text-emerald-600"
                                            >
                                                {{ number_format($tasa->porcentaje, 2) }}%
                                            </span>
                                        </div>

                                        {{-- Acciones (Editar y Eliminar) --}}
                                        <div class="flex items-center gap-1.5">
                                            {{-- Botón Editar --}}
                                            <button
                                                type="button"
                                                @click="openEdit = true"
                                                title="Editar tasa"
                                                class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-molaris-dark shadow-2xs transition-colors hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-600"
                                            >
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>

                                            {{-- Botón Eliminar --}}
                                            <form
                                                action="{{ route('financiero.comisiones.tasas.destroy', $tasa->id) }}"
                                                method="POST"
                                                onsubmit="
                                                    return confirm(
                                                        '¿Deseas eliminar esta tasa?'
                                                    );
                                                "
                                            >
                                                @csrf
                                                @method ('DELETE')
                                                <button
                                                    type="submit"
                                                    title="Eliminar tasa"
                                                    class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-red-500 shadow-2xs transition-colors hover:border-red-200 hover:bg-red-50 hover:text-red-600"
                                                >
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>

                                        {{-- Modal de Edición (Alpine.js) --}}
                                        <div
                                            x-show="openEdit"
                                            x-cloak
                                            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 p-4 backdrop-blur-sm"
                                            @keydown.escape.window="
                                                openEdit = false
                                            "
                                        >
                                            <div
                                                class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl ring-1 ring-slate-900/5"
                                                @click.away="openEdit = false"
                                            >
                                                <div
                                                    class="mb-4 flex items-center justify-between border-b border-slate-100 pb-3"
                                                >
                                                    <h3
                                                        class="text-base font-bold text-slate-800"
                                                    >
                                                        Editar Tasa de Comisión
                                                    </h3>
                                                    <button
                                                        type="button"
                                                        @click="
                                                            openEdit = false
                                                        "
                                                        class="rounded-lg p-1 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600"
                                                    >
                                                        ✕
                                                    </button>
                                                </div>

                                                <form
                                                    action="{{ route('financiero.comisiones.tasas.update', $tasa->id) }}"
                                                    method="POST"
                                                    class="space-y-4"
                                                >
                                                    @csrf
                                                    @method ('PUT')

                                                    <div>
                                                        <label
                                                            class="mb-1 block text-xs font-semibold uppercase tracking-wider text-slate-700"
                                                            >Porcentaje (%) <span class="text-red-500">*</span></label
                                                        >
                                                        <div class="relative">
                                                            <input
                                                                type="number"
                                                                step="0.01"
                                                                min="0"
                                                                max="100"
                                                                name="porcentaje"
                                                                value="{{ $tasa->porcentaje }}"
                                                                required
                                                                class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-3 pr-8 text-sm text-slate-800 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                                            />
                                                            <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-xs font-bold text-slate-400">%</span>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="flex justify-end gap-2 pt-2"
                                                    >
                                                        <button
                                                            type="button"
                                                            @click="
                                                                openEdit = false
                                                            "
                                                            class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-600 transition hover:bg-slate-50"
                                                        >
                                                            Cancelar
                                                        </button>
                                                        <button
                                                            type="submit"
                                                            class="rounded-xl bg-molaris-dark px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-black"
                                                        >
                                                            Guardar Cambios
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            {{-- Bloque para doctores sin tasa con Modal de Asignación --}}
                            <div
                                x-data="{ openAssign: false }"
                                class="mt-3 flex items-center justify-between rounded-xl border border-dashed border-slate-200/80 bg-slate-50/50 p-3.5 transition hover:border-slate-300"
                            >
                                <span class="text-xs italic text-slate-400"
                                    >Sin tasas asignadas aún.</span
                                >

                                <button
                                    type="button"
                                    @click="openAssign = true"
                                    class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-bold text-indigo-600 transition hover:bg-indigo-100 hover:text-indigo-700"
                                >
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Asignar tasa
                                </button>

                                {{-- Modal de Asignación Rápida --}}
                                <div
                                    x-show="openAssign"
                                    x-cloak
                                    class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 p-4 backdrop-blur-sm"
                                    @keydown.escape.window="openAssign = false"
                                >
                                    <div
                                        class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl ring-1 ring-slate-900/5"
                                        @click.away="openAssign = false"
                                    >
                                        <div
                                            class="mb-4 flex items-center justify-between border-b border-slate-100 pb-3"
                                        >
                                            <h3
                                                class="text-base font-bold text-slate-800"
                                            >
                                                Asignar Tasa a {{ $dentista->usuario->name ?? 'Odontólogo' }}
                                            </h3>
                                            <button
                                                type="button"
                                                @click="openAssign = false"
                                                class="rounded-lg p-1 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600"
                                            >
                                                ✕
                                            </button>
                                        </div>

                                        <form
                                            action="{{ route('financiero.comisiones.store') }}"
                                            method="POST"
                                            class="space-y-4"
                                        >
                                            @csrf

                                            {{-- USAR usuario_id EN LUGAR DE id DE DOCTORES --}}
                                            <input
                                                type="hidden"
                                                name="dentista_id"
                                                value="{{ $dentista->usuario_id ?? $dentista->usuario->id }}"
                                            />

                                            <div>
                                                <label
                                                    class="mb-1 block text-xs font-semibold uppercase tracking-wider text-slate-700"
                                                    >Especialidad <span class="text-red-500">*</span></label
                                                >
                                                <input
                                                    type="text"
                                                    name="especialidad"
                                                    value="Odontopediatría"
                                                    required
                                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-3 text-sm text-slate-800 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                                />
                                            </div>

                                            <div>
                                                <label
                                                    class="mb-1 block text-xs font-semibold uppercase tracking-wider text-slate-700"
                                                    >Porcentaje (%) <span class="text-red-500">*</span></label
                                                >
                                                <div class="relative">
                                                    <input
                                                        type="number"
                                                        step="0.01"
                                                        min="0"
                                                        max="100"
                                                        name="porcentaje"
                                                        value="40"
                                                        required
                                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-3 pr-8 text-sm text-slate-800 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                                    />
                                                    <span class="absolute inset-y-0 right-0 flex items-center pr-3 text-xs font-bold text-slate-400">%</span>
                                                </div>
                                            </div>

                                            <div
                                                class="flex justify-end gap-2 pt-2"
                                            >
                                                <button
                                                    type="button"
                                                    @click="openAssign = false"
                                                    class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-600 transition hover:bg-slate-50"
                                                >
                                                    Cancelar
                                                </button>
                                                <button
                                                    type="submit"
                                                    class="rounded-xl bg-molaris-dark px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-black"
                                                >
                                                    Guardar Tasa
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-sm font-medium text-slate-500">
                        No se encontraron odontólogos registrados en el sistema.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

@endsection