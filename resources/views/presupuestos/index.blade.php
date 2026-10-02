@extends('layouts.app')

@section('titulo', 'Presupuestos Clínicos')

@section('contenido')

<div class="max-w-7xl mx-auto space-y-6 pb-10">

    {{-- Encabezado --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between bg-white p-6 rounded-xl border border-slate-200 shadow-sm gap-4">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Listado de Presupuestos
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Gestiona los planes de tratamiento, abonos y saldos pendientes de los pacientes.
            </p>
        </div>

        <div>
            <a
                href="{{ route('financiero.presupuestos.create') }}"
                class="px-4 py-2.5 bg-molaris-dark hover:bg-black text-white font-bold text-sm rounded-xl shadow-md transition-colors flex items-center justify-center gap-2"
            >
                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 4v16m8-8H4"
                    />
                </svg>

                Nuevo Presupuesto
            </a>
        </div>

    </div>


    {{-- Buscador y Filtros --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">

        <form
            method="GET"
            action="{{ route('financiero.presupuestos.index') }}"
            class="flex flex-col lg:flex-row lg:items-end gap-4"
        >

            {{-- Buscar paciente --}}
            <div class="w-full lg:flex-1 lg:max-w-xl">

                <label
                    for="buscar"
                    class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1.5"
                >
                    Buscar paciente
                </label>

                <div class="relative">

                    <svg
                        class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="m21 21-4.35-4.35m1.35-5.15a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0Z"
                        />
                    </svg>

                    <input
                        type="text"
                        name="buscar"
                        id="buscar"
                        value="{{ request('buscar') }}"
                        placeholder="Nombre o RUT..."
                        class="w-full pl-9 pr-4 py-2.5 rounded-lg border border-slate-300 bg-white text-sm text-slate-700 placeholder-slate-400 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition"
                    >

                </div>

            </div>


            {{-- Filtro por estado --}}
            <div class="w-full lg:w-64">

                <label
                    for="estado"
                    class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1.5"
                >
                    Estado
                </label>

                <select
                    name="estado"
                    id="estado"
                    class="w-full py-2.5 px-3 rounded-lg border border-slate-300 bg-white text-sm text-slate-700 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition"
                >

                    <option value="">
                        Todos los estados
                    </option>

                    <option
                        value="borrador"
                        {{ request('estado') === 'borrador' ? 'selected' : '' }}
                    >
                        Borrador
                    </option>

                    <option
                        value="pendiente"
                        {{ request('estado') === 'pendiente' ? 'selected' : '' }}
                    >
                        Pendiente
                    </option>

                    <option
                        value="completado"
                        {{ request('estado') === 'completado' ? 'selected' : '' }}
                    >
                        Completado
                    </option>

                    <option
                        value="anulado"
                        {{ request('estado') === 'anulado' ? 'selected' : '' }}
                    >
                        Anulado
                    </option>

                </select>

            </div>


            {{-- Acciones --}}
            <div class="flex items-center gap-2">

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-semibold text-sm rounded-lg transition-colors whitespace-nowrap"
                >

                    <svg
                        class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="m21 21-6-6m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"
                        />
                    </svg>

                    Buscar

                </button>


                @if(request()->hasAny(['buscar', 'estado']))

                    <a
                        href="{{ route('financiero.presupuestos.index') }}"
                        class="inline-flex items-center justify-center w-10 h-10 bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-700 rounded-lg transition-colors"
                        title="Limpiar filtros"
                    >

                        <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 6l12 12M18 6 6 18"
                            />
                        </svg>

                    </a>

                @endif

            </div>

        </form>

    </div>


    {{-- Tabla de Presupuestos --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-left text-sm text-slate-600">

                {{-- Encabezado --}}
                <thead class="bg-slate-100 text-xs uppercase tracking-wider text-slate-500 font-semibold border-b border-slate-200">

                    <tr>

                        <th class="px-6 py-3.5">
                            Folio / Fecha
                        </th>

                        <th class="px-6 py-3.5">
                            Paciente
                        </th>

                        <th class="px-6 py-3.5">
                            Odontólogo Tratante
                        </th>

                        <th class="px-6 py-3.5 text-center">
                            Estado
                        </th>

                        <th class="px-6 py-3.5 text-right">
                            Monto Total
                        </th>

                        <th class="px-6 py-3.5 text-right">
                            Abonado
                        </th>

                        <th class="px-6 py-3.5 text-right">
                            Saldo Pendiente
                        </th>

                        <th class="px-6 py-3.5 text-center">
                            Acciones
                        </th>

                    </tr>

                </thead>


                {{-- Cuerpo --}}
                <tbody class="divide-y divide-slate-100">

                    @forelse($presupuestos as $presupuesto)

                        <tr class="hover:bg-slate-50 transition-colors">

                            {{-- Folio y fecha --}}
                            <td class="px-6 py-4">

                                <span class="font-mono font-bold text-slate-800">
                                    #{{ str_pad($presupuesto->id, 6, '0', STR_PAD_LEFT) }}
                                </span>

                                <span class="block text-xs text-slate-400 mt-0.5">
                                    {{ $presupuesto->created_at->format('d/m/Y H:i') }} hrs
                                </span>

                            </td>


                            {{-- Paciente --}}
                            <td class="px-6 py-4">

                                <span class="font-semibold text-slate-800 block">
                                    {{ $presupuesto->paciente->nombre ?? 'Paciente sin nombre' }}
                                </span>

                                <span class="text-xs text-slate-400 font-mono">
                                    RUT:
                                    {{ $presupuesto->paciente->rut ?? 'Sin RUT' }}
                                </span>

                            </td>


                            {{-- Odontólogo --}}
                            <td class="px-6 py-4 text-slate-700">

                                Dr(a).
                                {{ $presupuesto->dentista->usuario->name ?? 'N/A' }}

                            </td>


                            {{-- Estado --}}
                            <td class="px-6 py-4 text-center">

                                @php

                                    $clasesEstado = [

                                        'completado' =>
                                            'bg-emerald-100 text-emerald-800 border-emerald-200',

                                        'pendiente' =>
                                            'bg-amber-100 text-amber-800 border-amber-200',

                                        'borrador' =>
                                            'bg-slate-100 text-slate-700 border-slate-200',

                                        'anulado' =>
                                            'bg-rose-100 text-rose-800 border-rose-200',

                                    ];

                                @endphp


                                <span
                                    class="px-2.5 py-1 text-xs font-bold rounded-full border uppercase tracking-wider inline-block {{ $clasesEstado[$presupuesto->estado] ?? 'bg-slate-100 text-slate-600 border-slate-200' }}"
                                >
                                    {{ ucfirst($presupuesto->estado) }}
                                </span>

                            </td>


                            {{-- Monto total --}}
                            <td class="px-6 py-4 text-right font-semibold text-slate-800 font-mono">

                                ${{ number_format($presupuesto->monto_total, 0, ',', '.') }}

                            </td>


                            {{-- Abonado --}}
                            <td class="px-6 py-4 text-right font-semibold text-emerald-600 font-mono">

                                ${{ number_format($presupuesto->monto_pagado, 0, ',', '.') }}

                            </td>


                            {{-- Saldo pendiente --}}
                            <td
                                class="px-6 py-4 text-right font-bold font-mono {{ $presupuesto->saldo_pendiente > 0 ? 'text-rose-600' : 'text-slate-400' }}"
                            >

                                ${{ number_format($presupuesto->saldo_pendiente, 0, ',', '.') }}

                            </td>


                            {{-- Acciones --}}
                            <td class="px-6 py-4 text-center">

                                <a
                                    href="{{ route('financiero.presupuestos.show', $presupuesto->id) }}"
                                    class="p-2 text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors inline-block"
                                    title="Ver detalle / cobrar"
                                >

                                    <svg
                                        class="w-5 h-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7Z"
                                        />
                                    </svg>

                                </a>

                            </td>

                        </tr>

                    @empty

                        {{-- Sin resultados --}}
                        <tr>

                            <td
                                colspan="8"
                                class="px-6 py-12 text-center text-slate-400"
                            >

                                <svg
                                    class="w-12 h-12 mx-auto text-slate-300 mb-3"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2Z"
                                    />
                                </svg>

                                <p class="text-base font-bold text-slate-600">
                                    No se encontraron presupuestos
                                </p>

                                <p class="text-xs text-slate-400 mt-1">
                                    Prueba ajustando los filtros de búsqueda o emite un nuevo presupuesto.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Paginación --}}
        @if($presupuestos->hasPages())

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">

                {{ $presupuestos->withQueryString()->links() }}

            </div>

        @endif

    </div>

</div>

@endsection