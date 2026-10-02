@extends ('layouts.app')

@section ('titulo', 'Cierre y Arqueo de Caja')

@section ('contenido')
    <div class="mx-auto max-w-7xl space-y-6">
        {{-- Encabezado y Selector de Fecha --}}
        <div
            class="flex flex-col justify-between gap-4 rounded-xl border border-slate-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center"
        >
            <div>
                <h1 class="text-2xl font-bold text-slate-800">
                    Arqueo y Cierre de Caja
                </h1>
                <p class="mt-1 text-sm text-slate-500">Resumen diario de abonos recaudados por medio de pago.</p>
            </div>

            {{-- Formulario de Fecha --}}
            <form
                method="GET"
                action="{{ route('financiero.pagos.cierre') }}"
                class="flex items-center gap-2"
            >
                <div>
                    <label for="fecha" class="sr-only">Fecha del Cierre</label>
                    <input
                        type="date"
                        name="fecha"
                        id="fecha"
                        value="{{ $fecha }}"
                        class="rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                        onchange="this.form.submit()"
                    />
                </div>
                <button
                    type="submit"
                    class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-slate-900"
                >
                    Consultar
                </button>
            </form>
        </div>

        {{-- Tarjetas de Métricas por Medio de Pago --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            {{-- Total Efectivo --}}
            <div
                class="flex items-center justify-between rounded-xl border border-slate-200 bg-white p-5 shadow-sm"
            >
                <div>
                    <span
                        class="block text-xs font-semibold uppercase text-slate-400"
                        >Efectivo en Caja</span
                    >
                    <span
                        class="mt-1 block font-mono text-2xl font-black text-slate-800"
                    >
                        ${{ number_format($resumen['total_efectivo'], 0, ',', '.') }}
                    </span>
                </div>
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600"
                >
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>

            {{-- Total Transferencias --}}
            <div
                class="flex items-center justify-between rounded-xl border border-slate-200 bg-white p-5 shadow-sm"
            >
                <div>
                    <span
                        class="block text-xs font-semibold uppercase text-slate-400"
                        >Transferencias</span
                    >
                    <span
                        class="mt-1 block font-mono text-2xl font-black text-slate-800"
                    >
                        ${{ number_format($resumen['total_transferencia'], 0, ',', '.') }}
                    </span>
                </div>
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-xl bg-sky-50 text-sky-600"
                >
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                </div>
            </div>

            {{-- Total POS Transbank --}}
            <div
                class="flex items-center justify-between rounded-xl border border-slate-200 bg-white p-5 shadow-sm"
            >
                <div>
                    <span
                        class="block text-xs font-semibold uppercase text-slate-400"
                        >POS / Tarjetas</span
                    >
                    <span
                        class="mt-1 block font-mono text-2xl font-black text-slate-800"
                    >
                        ${{ number_format($resumen['total_transbank'], 0, ',', '.') }}
                    </span>
                </div>
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600"
                >
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                </div>
            </div>

            {{-- Total General --}}
            <div
                class="flex items-center justify-between rounded-xl border border-emerald-900 bg-emerald-600 p-5 text-white shadow-sm"
            >
                <div>
                    <span
                        class="block text-xs font-semibold uppercase text-emerald-200"
                        >Total Recaudado</span
                    >
                    <span class="mt-1 block font-mono text-2xl font-black">
                        ${{ number_format($resumen['total_general'], 0, ',', '.') }}
                    </span>
                    <span class="mt-0.5 block text-[11px] text-emerald-200"
                        >{{ $resumen['cantidad_transacciones'] }} transacción(es)</span
                    >
                </div>
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-700 text-white"
                >
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        {{-- Tabla de Transacciones del Día --}}
        <div
            class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm"
        >
            <div
                class="flex items-center justify-between border-b border-slate-200 bg-slate-50 p-5"
            >
                <h2 class="text-base font-bold text-slate-800">
                    Detalle de Ingresos ({{ Carbon\Carbon::parse($fecha)->format('d/m/Y') }})
                </h2>
                <button
                    onclick="window.print()"
                    class="flex items-center gap-1.5 rounded-lg bg-molaris-dark px-3 py-1.5 text-xs font-bold text-white transition-colors hover:bg-black"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Imprimir Resumen
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead
                        class="border-b border-slate-200 bg-slate-100 text-xs font-semibold uppercase tracking-wider text-slate-500"
                    >
                        <tr>
                            <th class="px-6 py-3.5">Hora / N° Recibo</th>
                            <th class="px-6 py-3.5">Presupuesto</th>
                            <th class="px-6 py-3.5">Paciente</th>
                            <th class="px-6 py-3.5">Medio de Pago</th>
                            <th class="px-6 py-3.5">Referencia / Voucher</th>
                            <th class="px-6 py-3.5">Cajero</th>
                            <th class="px-6 py-3.5 text-right">Monto</th>
                            <th class="px-6 py-3.5 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($pagosDelDia as $pago)
                            <tr class="transition-colors hover:bg-slate-50">
                                {{-- Hora y ID Pago --}}
                                <td class="px-6 py-4">
                                    <span
                                        class="font-mono font-bold text-slate-800"
                                        >#{{ str_pad($pago->id, 6, '0', STR_PAD_LEFT) }}</span
                                    >
                                    <span
                                        class="mt-0.5 block text-xs text-slate-400"
                                        >{{ Carbon\Carbon::parse($pago->fecha_pago ?? $pago->created_at)->format('H:i') }} hrs</span
                                    >
                                </td>

                                {{-- Folio Presupuesto --}}
                                <td
                                    class="px-6 py-4 font-mono font-semibold text-slate-700"
                                >
                                    #{{ str_pad($pago->presupuesto_id, 6, '0', STR_PAD_LEFT) }}
                                </td>

                                {{-- Paciente --}}
                                <td class="px-6 py-4">
                                    <span
                                        class="block font-semibold text-slate-800"
                                    >
                                        {{ $pago->presupuesto->paciente->nombre ?? 'N/A' }} {{ $pago->presupuesto->paciente->apellidos ?? '' }}
                                    </span>
                                    <span
                                        class="font-mono text-xs text-slate-400"
                                        >RUT: {{ $pago->presupuesto->paciente->rut ?? 'N/A' }}</span
                                    >
                                </td>

                                {{-- Medio de Pago Badge --}}
                                <td class="px-6 py-4">
                                    @switch ($pago->medio_pago)
                                        @case ('efectivo')
                                            <span
                                                class="rounded-full border border-emerald-200 bg-emerald-100 px-2.5 py-1 text-xs font-bold uppercase text-emerald-800"
                                                >Efectivo</span
                                            >
                                            @break
                                        @case ('transferencia')
                                            <span
                                                class="rounded-full border border-sky-200 bg-sky-100 px-2.5 py-1 text-xs font-bold uppercase text-sky-800"
                                                >Transferencia</span
                                            >
                                            @break
                                        @case ('pos_transbank')
                                            <span
                                                class="rounded-full border border-indigo-200 bg-indigo-100 px-2.5 py-1 text-xs font-bold uppercase text-indigo-800"
                                                >POS Transbank</span
                                            >
                                            @break
                                        @default
                                            <span
                                                class="rounded-full border border-slate-200 bg-slate-100 px-2.5 py-1 text-xs font-bold uppercase text-slate-700"
                                                >{{ $pago->medio_pago }}</span
                                            >
                                    @endswitch
                                </td>

                                {{-- Voucher / Ref --}}
                                <td
                                    class="px-6 py-4 font-mono text-xs text-slate-600"
                                >
                                    {{ $pago->numero_referencia ?: '---' }}
                                </td>

                                {{-- Cajero --}}
                                <td class="px-6 py-4 text-xs text-slate-700">
                                    {{ $pago->usuario->nombre ?? 'Sistema' }} {{ $pago->usuario->apellido ?? '' }}
                                </td>

                                {{-- Monto --}}
                               
                                <td class="px-6 py-4 text-right">
                                    <span
                                        class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-100 px-3 py-1 text-xs font-bold uppercase text-emerald-800"
                                    >
                                        ${{ number_format($pago->monto, 0, ',', '.') }}
                                    </span>
                                </td>

                                {{-- Reimprimir Comprobante --}}
                                <td class="px-6 py-4 text-center">
                                    <a
                                        href="{{ route('financiero.pagos.comprobante', $pago->id) }}"
                                        target="_blank"
                                        class="inline-block rounded-lg p-2 text-molaris-dark transition-colors hover:bg-emerald-50 hover:text-emerald-600"
                                        title="Ver / Imprimir Recibo"
                                    >
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="8"
                                    class="px-6 py-12 text-center text-slate-400"
                                >
                                    <svg class="mx-auto mb-3 h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <p class="text-base font-bold text-slate-600">No hay cobros registrados en la fecha seleccionada</p>
                                    <p class="mt-1 text-xs text-slate-400">Selecciona otro día en el calendario para auditar los movimientos.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
