<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
        Comprobante de Pago #{{ str_pad($pago->id, 6, '0', STR_PAD_LEFT) }}
    </title>
    @vite (['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            body {
                background-color: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .ticket {
                width: 100% !important;
                max-width: 80mm !important;
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                margin: 0 auto !important;
            }
        }
    </style>
</head>
<body
    class="flex min-h-screen flex-col items-center justify-center bg-slate-100 py-8 font-mono text-slate-800 antialiased"
>
    {{-- Botones de Acción (Se ocultan al imprimir) --}}
    <div class="no-print mb-6 flex gap-3">
        <button
            onclick="window.print()"
            class="flex items-center gap-2 rounded-xl bg-molaris-dark px-5 py-2.5 font-sans text-sm font-bold text-white shadow-md transition-all hover:bg-black"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Imprimir Comprobante
        </button>
        <a
            href="{{ route('financiero.presupuestos.show', $pago->presupuesto_id) }}"
            class="flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-5 py-2.5 font-sans text-sm font-semibold text-slate-700 shadow-sm transition-all hover:bg-slate-50"
        >
            Volver al Presupuesto
        </a>
    </div>

    {{-- Formato Ticket Térmico (80mm) --}}
    <div
        class="ticket w-[80mm] rounded-xl border border-slate-200 bg-white p-5 text-xs leading-relaxed shadow-lg"
    >
        {{-- Encabezado de la Clínica --}}
        <div
            class="space-y-1 border-b border-dashed border-slate-300 pb-3 text-center"
        >
            <h1
                class="text-base font-black uppercase tracking-wider text-slate-900"
            >
                Clínica Odontológica Venedental
            </h1>
            <p class="text-[10px] text-slate-500">R.U.T.: 76.543.210-K</p>
            <p class="text-[10px] text-slate-500">Av. Principal #1234, Concepción</p>
            <p class="text-[10px] text-slate-500">Tel: +56 41 234 5678</p>
        </div>

        {{-- Datos del Comprobante --}}
        <div class="space-y-1 border-b border-dashed border-slate-300 py-3">
            <div class="flex justify-between font-bold text-slate-900">
                <span>COMPROBANTE:</span>
                <span>#{{ str_pad($pago->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="flex justify-between text-slate-600">
                <span>FECHA:</span>
                <span
                    >{{ $pago->fecha_pago ? Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y H:i') : $pago->created_at->format('d/m/Y H:i') }}</span
                >
            </div>
            <div class="flex justify-between text-slate-600">
                <span>PRESUPUESTO:</span>
                <span
                    >#{{ str_pad($pago->presupuesto_id, 6, '0', STR_PAD_LEFT) }}</span
                >
            </div>
        </div>

        {{-- Datos del Paciente y Profesional --}}
        <div class="space-y-1 border-b border-dashed border-slate-300 py-3">
            {{-- Paciente --}}
            <div>
                <span class="block text-[10px] uppercase text-slate-400">
                    Paciente:
                </span>

                <span class="block font-bold uppercase text-slate-900">
                    {{ $pago->presupuesto->paciente->nombre ?? 'Paciente sin nombre' }}
                </span>

                <span class="text-slate-600">
                    RUT: {{ $pago->presupuesto->paciente->rut ?? 'Sin RUT' }}
                </span>
            </div>
            <div class="pt-1">
                <span class="block text-[10px] uppercase text-slate-400"
                    >Dr(a). Tratante:</span
                >
                <span class="font-medium text-slate-800">
                    {{ $pago->presupuesto->dentista->usuario->name ?? 'N/A' }}
                </span>
            </div>
        </div>

        {{-- Detalle Financiero del Pago --}}
        <div class="space-y-1.5 border-b border-dashed border-slate-300 py-3">
            <div class="flex justify-between text-slate-600">
                <span>MEDIO DE PAGO:</span>
                <span class="font-bold uppercase text-slate-800">
                    @switch ($pago->medio_pago)
                        @case ('efectivo')
                            Efectivo
                            @break
                        @case ('transferencia')
                            Transferencia
                            @break
                        @case ('pos_transbank')
                            POS Transbank
                            @break
                        @default
                            {{ $pago->medio_pago }}
                    @endswitch
                </span>
            </div>

            @if ($pago->numero_referencia)
                <div class="flex justify-between text-slate-600">
                    <span>N° REF / VOUCHER:</span>
                    <span
                        class="font-mono text-slate-800"
                        >{{ $pago->numero_referencia }}</span
                    >
                </div>
            @endif

            <div
                class="flex items-center justify-between border-t border-slate-100 pt-2 text-sm font-black text-slate-900"
            >
                <span>MONTO ABONADO:</span>
                <span class="text-base"
                    >${{ number_format($pago->monto, 0, ',', '.') }}</span
                >
            </div>
        </div>

        {{-- Estado de la Cuenta --}}
        <div
            class="-mx-5 my-1 space-y-1 border-b border-dashed border-slate-300 bg-slate-50 px-5 py-3"
        >
            <div class="flex justify-between text-slate-600">
                <span>Total Tratamiento:</span>
                <span
                    >${{ number_format($pago->presupuesto->monto_total, 0, ',', '.') }}</span
                >
            </div>
            <div class="flex justify-between text-slate-600">
                <span>Total Pagado a la fecha:</span>
                <span class="font-bold text-emerald-700"
                    >${{ number_format($pago->presupuesto->monto_pagado, 0, ',', '.') }}</span
                >
            </div>
            <div
                class="flex justify-between border-t border-slate-200 pt-1 font-bold text-slate-900"
            >
                <span>SALDO PENDIENTE:</span>
                <span
                    class="{{ $pago->presupuesto->saldo_pendiente > 0 ? 'text-rose-600' : 'text-slate-700' }}"
                >
                    ${{ number_format($pago->presupuesto->saldo_pendiente, 0, ',', '.') }}
                </span>
            </div>
        </div>

        {{-- Pie de Imprenta / Firma --}}
        <div class="space-y-3 pt-4 text-center">
            <div class="mx-auto w-3/4 border-b border-slate-300 pt-6"></div>
            <p class="text-[10px] uppercase text-slate-500">Cajero: {{ $pago->usuario->nombre ?? 'Sistema' }} {{ $pago->usuario->apellido ?? '' }}</p>
            <p class="text-[9px] leading-tight text-slate-400">*** Gracias por su confianza ***<br />Conserve este comprobante como respaldo de su abono.</p>
        </div>
    </div>
</body>
</html>
