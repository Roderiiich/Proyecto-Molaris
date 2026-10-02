@extends('layouts.app')

@section('titulo', 'Liquidación de Comisiones')

@section('contenido')
<div class="max-w-6xl mx-auto space-y-6">

    {{-- Encabezado --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between bg-white p-6 rounded-xl border border-slate-200 shadow-sm gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Liquidación de Comisiones Odontológicas</h1>
            <p class="text-sm text-slate-500 mt-1">Cálculo de comisiones por especialidad según recaudación de abonos efectivos.</p>
        </div>
    </div>

    {{-- Filtros de Búsqueda y Periodo --}}
    <form method="GET" action="{{ route('financiero.liquidaciones.index') }}" class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
        {{-- Selección de Mes --}}
        <div>
            <label for="mes" class="block text-xs font-semibold uppercase text-slate-500 mb-1">Mes</label>
            <select name="mes" id="mes" class="w-full rounded-lg border-slate-300 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}" {{ $mes == $m ? 'selected' : '' }}>
                        {{ Carbon\Carbon::create()->month($m)->locale('es')->monthName }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Selección de Año --}}
        <div>
            <label for="anio" class="block text-xs font-semibold uppercase text-slate-500 mb-1">Año</label>
            <select name="anio" id="anio" class="w-full rounded-lg border-slate-300 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                @foreach(range(2024, 2026) as $a)
                    <option value="{{ $a }}" {{ $anio == $a ? 'selected' : '' }}>{{ $a }}</option>
                @endforeach
            </select>
        </div>

        {{-- Selección de Odontólogo --}}
        <div>
            <label for="dentista_id" class="block text-xs font-semibold uppercase text-slate-500 mb-1">Odontólogo</label>
            <select name="dentista_id" id="dentista_id" class="w-full rounded-lg border-slate-300 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                <option value="">-- Todos los Profesionales --</option>
                @foreach($dentistas as $dentista)
                    <option value="{{ $dentista->id }}" {{ $dentistaId == $dentista->id ? 'selected' : '' }}>
                        Dr(a). {{ $dentista->nombre }} {{ $dentista->apellido }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Botón Filtrar --}}
        <div>
            <button type="submit" class="w-full px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-lg shadow-sm transition-colors flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                Calcular Comisiones
            </button>
        </div>
    </form>

    {{-- Listado de Liquidaciones por Odontólogo --}}
    @forelse($liquidaciones as $liquidacion)
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden" x-data="{ abierto: true }">
            {{-- Resumen del Odontólogo --}}
            <div class="p-6 bg-slate-50 border-b border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <button @click="abierto = !abierto" class="p-1 text-slate-400 hover:text-slate-600 rounded-lg">
                        <svg class="w-5 h-5 transform transition-transform" :class="abierto ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">Dr(a). {{ $liquidacion['dentista_nombre'] }}</h2>
                        <span class="text-xs text-slate-500">Periodo: {{ Carbon\Carbon::create()->month((int)$mes)->locale('es')->monthName }} {{ $anio }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-6 text-right">
                    <div>
                        <span class="block text-xs font-semibold text-slate-400 uppercase">Recaudado Bruto</span>
                        <span class="text-lg font-bold text-slate-700">${{ number_format($liquidacion['total_recaudado'], 0, ',', '.') }}</span>
                    </div>
                    <div class="border-l border-slate-300 pl-6">
                        <span class="block text-xs font-semibold text-emerald-600 uppercase">Total Comisión Odontólogo</span>
                        <span class="text-2xl font-black text-emerald-700">${{ number_format($liquidacion['total_comision'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Detalle Desglosado por Especialidad --}}
            <div x-show="abierto" class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-100 text-xs uppercase tracking-wider text-slate-500 border-b border-slate-200 font-semibold">
                        <tr>
                            <th class="px-6 py-3">Especialidad Clínica</th>
                            <th class="px-6 py-3 text-right">Monto Recaudado ($)</th>
                            <th class="px-6 py-3 text-center">% Comisión Pactado</th>
                            <th class="px-6 py-3 text-right">Monto Comisión ($)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($liquidacion ['detalle_especialidades'] as $detalle)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-3.5 font-medium text-slate-800">{{ $detalle['especialidad'] }}</td>
                                <td class="px-6 py-3.5 text-right font-mono">${{ number_format($detalle['total_recaudado'], 0, ',', '.') }}</td>
                                <td class="px-6 py-3.5 text-center font-bold text-slate-700">{{ $detalle['porcentaje'] }}%</td>
                                <td class="px-6 py-3.5 text-right font-bold text-emerald-700 font-mono">${{ number_format($detalle['monto_comision'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-12 text-center">
            <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <h3 class="text-base font-bold text-slate-700">Sin recaudaciones en este periodo</h3>
            <p class="text-sm text-slate-400 mt-1">No se registran pagos o abonos procesados para el filtro de fecha u odontólogo seleccionado.</p>
        </div>
    @endforelse

</div>
@endsection