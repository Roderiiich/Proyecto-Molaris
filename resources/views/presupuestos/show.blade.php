@extends ('layouts.app')

@section ('titulo', 'Detalle de Presupuesto #' . str_pad($presupuesto->id, 6, '0', STR_PAD_LEFT))

@section ('contenido')
    <div
        x-data="{ modalPago: false, modalEstado: false }"
        class="mx-auto max-w-7xl space-y-6"
    >
        {{-- Mensajes de Alerta / Flashes --}}
        @if (session('success'))
            <div
                class="flex items-center justify-between rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800"
            >
                <span>{{ session('success') }}</span>
                <button
                    onclick="this.parentElement.remove()"
                    class="text-emerald-500 hover:text-emerald-700"
                >
                    &times;
                </button>
            </div>
        @endif

        @if (session('info'))
            <div
                class="flex items-center justify-between rounded-xl border border-sky-200 bg-sky-50 p-4 text-sm font-semibold text-sky-800"
            >
                <span>{{ session('info') }}</span>
                <button
                    onclick="this.parentElement.remove()"
                    class="text-sky-500 hover:text-sky-700"
                >
                    &times;
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div
                class="space-y-1 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm font-semibold text-rose-800"
            >
                <p class="font-bold">Atención:</p>
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Encabezado Principal y Acciones --}}
        <div
            class="flex flex-col justify-between gap-4 rounded-xl border border-slate-200 bg-white p-6 shadow-sm md:flex-row md:items-center"
        >
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="font-mono text-2xl font-bold text-slate-800">
                        Presupuesto #{{ str_pad($presupuesto->id, 6, '0', STR_PAD_LEFT) }}
                    </h1>

                    {{-- Badge de Estado --}}
                    @switch ($presupuesto->estado)
                        @case ('borrador')
                            <span
                                class="rounded-full border border-slate-300 bg-slate-100 px-3 py-1 text-xs font-bold uppercase text-slate-700"
                                >Borrador</span
                            >
                            @break
                        @case ('pendiente')
                            <span
                                class="rounded-full border border-amber-300 bg-amber-100 px-3 py-1 text-xs font-bold uppercase text-amber-800"
                                >Pendiente</span
                            >
                            @break
                        @case ('completado')
                            <span
                                class="rounded-full border border-emerald-300 bg-emerald-100 px-3 py-1 text-xs font-bold uppercase text-emerald-800"
                                >Completado</span
                            >
                            @break
                        @case ('anulado')
                            <span
                                class="rounded-full border border-rose-300 bg-rose-100 px-3 py-1 text-xs font-bold uppercase text-rose-800"
                                >Anulado</span
                            >
                            @break
                    @endswitch
                </div>
                <p class="mt-1 text-sm text-slate-500">Registrado el {{ $presupuesto->created_at->format('d/m/Y H:i') }} hrs.</p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                @if ($presupuesto->estado !== 'anulado')
                    <button
                        @click="modalEstado = true"
                        class="rounded-lg border border-slate-300 bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-200"
                    >
                        Cambiar Estado
                    </button>
                @endif

                @if ($presupuesto->estado !== 'anulado' && $presupuesto->saldo_pendiente > 0)
                    <button
                        @click="modalPago = true"
                        class="flex items-center gap-2 rounded-lg bg-molaris-dark px-5 py-2 text-sm font-bold text-white shadow-sm transition-colors hover:bg-black"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Registrar Abono
                    </button>
                @endif
            </div>
        </div>

        {{-- Resumen Informativo y Financiero --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            {{-- Tarjeta Paciente y Profesional --}}
            <div
                class="space-y-4 rounded-xl border border-slate-200 bg-white p-6 shadow-sm"
            >
                <h2
                    class="text-sm font-bold uppercase tracking-wider text-slate-400"
                >
                    Información General
                </h2>

                <div class="border-b border-slate-100 pb-3">
                    <span class="block text-xs text-slate-400">Paciente:</span>
                    <span class="block text-base font-bold text-slate-800">
                        {{ $presupuesto->paciente->nombre ?? 'N/A' }}
                    </span>
                    <span class="font-mono text-xs text-slate-500"
                        >RUT: {{ $presupuesto->paciente->rut ?? 'N/A' }}</span
                    >
                </div>

                <div>
                    <span class="block text-xs text-slate-400"
                        >Doctor(a) Tratante:</span
                    >
                    <span class="block text-sm font-semibold text-slate-700">
                        {{ $presupuesto->dentista->usuario->nombre ?? $presupuesto->dentista->user->name ?? 'N/A' }}
                    </span>
                </div>

                @if ($presupuesto->estado === 'anulado')
                    <div
                        class="rounded-lg border border-rose-200 bg-rose-50 p-3 text-xs text-rose-800"
                    >
                        <span class="block font-bold"
                            >Motivo de Anulación:</span
                        >
                        <p class="mt-0.5">{{ $presupuesto->motivo_anulacion ?: 'Sin especificar' }}</p>
                        <span
                            class="mt-1 block text-[10px] text-rose-500"
                            >{{ $presupuesto->fecha_anulacion?->format('d/m/Y H:i') }}</span
                        >
                    </div>
                @endif
            </div>

            {{-- Tarjeta Avance y Resumen de Estado Financiero --}}
            @php
    $total = (float) $presupuesto->monto_total;
    $pagado = (float) $presupuesto->monto_pagado;
    $porcentaje = $total > 0 ? min(100, round(($pagado / $total) * 100, 1)) : 0;
@endphp

            <div
                class="flex flex-col justify-between rounded-xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2"
            >
                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <h2
                            class="text-sm font-bold uppercase tracking-wider text-slate-400"
                        >
                            Estado Financiero
                        </h2>
                        <span class="text-xs font-bold text-slate-600">
                            {{ $porcentaje }}% Cubierto
                        </span>
                    </div>

                    {{-- Barra de Progreso --}}
                    <div
                        class="mb-6 w-full rounded-full bg-slate-200"
                        style="height: 14px; overflow: hidden"
                    >
                        <div
                            style="width: {{ $porcentaje }}%; height: 14px; background-color: #10b981; border-radius: 9999px; transition: width 0.5s ease;"
                        ></div>
                    </div>
                </div>

                <div
                    class="grid grid-cols-1 gap-4 border-t border-slate-100 pt-4 sm:grid-cols-3"
                >
                    <div>
                        <span class="block text-xs text-slate-400"
                            >Monto Total</span
                        >
                        <span
                            class="font-mono text-xl font-bold text-slate-800"
                        >
                            ${{ number_format($presupuesto->monto_total, 0, ',', '.') }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-xs text-slate-400"
                            >Total Pagado</span
                        >
                        <span
                            class="font-mono text-xl font-bold text-emerald-600"
                        >
                            ${{ number_format($presupuesto->monto_pagado, 0, ',', '.') }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-xs text-slate-400"
                            >Saldo Pendiente</span
                        >
                        <span
                            class="text-xl font-bold font-mono {{ $presupuesto->saldo_pendiente > 0 ? 'text-rose-600' : 'text-slate-700' }}"
                        >
                            ${{ number_format($presupuesto->saldo_pendiente, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Historial de Pagos --}}
        <div
            class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm"
        >
            <div
                class="flex items-center justify-between border-b border-slate-200 bg-slate-50 p-5"
            >
                <h2 class="text-base font-bold text-slate-800">
                    Historial de Abonos y Pagos
                </h2>
                <span class="text-xs font-semibold text-slate-500"
                    >{{ $presupuesto->pagos->count() }} registro(s)</span
                >
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead
                        class="border-b border-slate-200 bg-slate-100 text-xs font-semibold uppercase tracking-wider text-slate-500"
                    >
                        <tr>
                            <th class="px-6 py-3.5">N° Recibo / Fecha</th>
                            <th class="px-6 py-3.5">Medio de Pago</th>
                            <th class="px-6 py-3.5">N° Referencia</th>
                            <th class="px-6 py-3.5">Cajero</th>
                            <th class="px-6 py-3.5 text-right">Monto</th>
                            <th class="px-6 py-3.5 text-center">Comprobante</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($presupuesto->pagos as $pago)
                            <tr class="transition-colors hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <span
                                        class="font-mono font-bold text-slate-800"
                                        >#{{ str_pad($pago->id, 6, '0', STR_PAD_LEFT) }}</span
                                    >
                                    <span
                                        class="mt-0.5 block text-xs text-slate-400"
                                        >{{ $pago->fecha_pago?->format('d/m/Y H:i') }} hrs</span
                                    >
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="rounded-full border border-slate-200 bg-green-100 px-2.5 py-1 text-xs font-bold uppercase text-slate-700"
                                    >
                                        {{ $pago->medio_pago_texto }}
                                    </span>
                                </td>
                                <td
                                    class="px-6 py-4 font-mono text-xs text-slate-600"
                                >
                                    {{ $pago->numero_referencia ?: '---' }}
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-700">
                                    {{ $pago->usuario->nombre ?? 'Sistema' }} {{ $pago->usuario->apellido ?? '' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span
                                        class="rounded-full border border-slate-200 bg-green-100 px-2.5 py-1 text-xs font-bold uppercase text-slate-700"
                                    >
                                        ${{ number_format($pago->monto, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a
                                        href="{{ route('financiero.pagos.comprobante', $pago->id) }}"
                                        target="_blank"
                                        class="inline-block rounded-lg p-2 text-slate-500 transition-colors hover:bg-emerald-50 hover:text-emerald-600"
                                        title="Ver Recibo"
                                    >
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="6"
                                    class="px-6 py-8 text-center text-slate-400"
                                >
                                    No se han registrado abonos o pagos para
                                    este presupuesto.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Modal para Registrar Abono / Pago --}}
        <div
            x-show="modalPago"
            class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-slate-900/50 p-4 backdrop-blur-sm"
            x-cloak
        >
            <div
                @click.away="modalPago = false"
                class="w-full max-w-md rounded-2xl border border-slate-100 bg-white p-6 shadow-2xl"
            >
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-800">
                        Registrar Nuevo Abono
                    </h3>
                    <button
                        @click="modalPago = false"
                        class="text-slate-400 hover:text-slate-600"
                    >
                        &times;
                    </button>
                </div>

                <form
                    action="{{ route('financiero.pagos.store') }}"
                    method="POST"
                    class="space-y-4"
                >
                    @csrf
                    <input
                        type="hidden"
                        name="presupuesto_id"
                        value="{{ $presupuesto->id }}"
                    />

                    <div>
                        <label
                            class="mb-1 block text-xs font-bold uppercase text-slate-600"
                            >Monto a Abonar ($)</label
                        >
                        <input
                            type="number"
                            name="monto"
                            max="{{ $presupuesto->saldo_pendiente }}"
                            step="1"
                            required
                            value="{{ $presupuesto->saldo_pendiente }}"
                            class="w-full rounded-xl border-slate-300 font-mono text-lg focus:border-emerald-500 focus:ring-emerald-500"
                        />
                        <span class="mt-1 block text-[11px] text-slate-400"
                            >Saldo pendiente actual: ${{ number_format($presupuesto->saldo_pendiente, 0, ',', '.') }}</span
                        >
                    </div>

                    <div>
                        <label
                            class="mb-1 block text-xs font-bold uppercase text-slate-600"
                            >Medio de Pago</label
                        >
                        <select
                            name="medio_pago"
                            required
                            class="w-full rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                        >
                            <option value="efectivo">Efectivo</option>
                            <option value="transferencia">
                                Transferencia Bancaria
                            </option>
                            <option value="pos_transbank">POS Transbank</option>
                        </select>
                    </div>

                    <div>
                        <label
                            class="mb-1 block text-xs font-bold uppercase text-slate-600"
                            >N° Referencia / Voucher (Opcional)</label
                        >
                        <input
                            type="text"
                            name="numero_referencia"
                            placeholder="Ej: 123456789"
                            class="w-full rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                        />
                    </div>

                    <div class="flex justify-end gap-2 pt-4">
                        <button
                            type="button"
                            @click="modalPago = false"
                            class="rounded-xl bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-200"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            class="rounded-xl bg-emerald-600 px-5 py-2 text-sm font-bold text-white shadow-sm transition-colors hover:bg-emerald-700"
                        >
                            Guardar y Generar Recibo
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal para Cambiar Estado --}}
        <div
            x-show="modalEstado"
            class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-slate-900/50 p-4 backdrop-blur-sm"
            x-cloak
        >
            <div
                @click.away="modalEstado = false"
                class="w-full max-w-md rounded-2xl border border-slate-100 bg-white p-6 shadow-2xl"
                x-data="{ nuevoEstado: '{{ $presupuesto->estado }}' }"
            >
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-800">
                        Cambiar Estado del Presupuesto
                    </h3>
                    <button
                        @click="modalEstado = false"
                        class="text-slate-400 hover:text-slate-600"
                    >
                        &times;
                    </button>
                </div>

                <form
                    action="{{ route('financiero.presupuestos.cambiar-estado', $presupuesto->id) }}"
                    method="POST"
                    class="space-y-4"
                >
                    @csrf

                    <div>
                        <label
                            class="mb-1 block text-xs font-bold uppercase text-slate-600"
                            >Nuevo Estado</label
                        >
                        <select
                            name="estado"
                            x-model="nuevoEstado"
                            class="w-full rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                        >
                            <option value="borrador">Borrador</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="completado">Completado</option>
                            <option value="anulado">Anulado</option>
                        </select>
                    </div>

                    <div x-show="nuevoEstado === 'anulado'">
                        <label
                            class="mb-1 block text-xs font-bold uppercase text-rose-600"
                            >Motivo de Anulación *</label
                        >
                        <textarea
                            name="motivo_anulacion"
                            rows="2"
                            placeholder="Especifique el motivo por el cual se anula..."
                            class="w-full rounded-xl border-slate-300 text-sm focus:border-rose-500 focus:ring-rose-500"
                        ></textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-4">
                        <button
                            type="button"
                            @click="modalEstado = false"
                            class="rounded-xl bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-200"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            class="rounded-xl bg-slate-800 px-5 py-2 text-sm font-bold text-white shadow-sm transition-colors hover:bg-slate-900"
                        >
                            Actualizar Estado
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
