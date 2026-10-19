@extends('layouts.app')

@section('titulo', 'Detalle de Compra')

@section('contenido')
    <div class="mx-auto max-w-7xl space-y-6">
        
        {{-- Encabezado Principal --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center gap-3">
                <a
                    href="{{ route('financiero.compras.index') }}"
                    class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-colors hover:bg-slate-50 hover:text-slate-800"
                    title="Volver"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-bold tracking-tight text-slate-800">
                            Comprobante N° {{ $compra->numero_factura ?: 'S/N' }}
                        </h1>
                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700 border border-emerald-200/60">
                            Ingresado a Stock
                        </span>
                    </div>
                    <p class="mt-0.5 text-xs font-medium text-slate-500">Detalle general del documento de compra e insumos registrados.</p>
                </div>
            </div>

            <a
                href="{{ route('financiero.compras.index') }}"
                class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-600 shadow-sm transition-colors hover:bg-slate-50 hover:text-slate-900 shrink-0"
            >
                ← Volver al Historial
            </a>
        </div>

        {{-- Tarjeta de Datos Clave (Organizada en 3 Columnas) --}}
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-3 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            {{-- Proveedor --}}
            <div class="flex items-start gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div>
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Proveedor</span>
                    <span class="mt-0.5 block text-sm font-bold text-slate-800 leading-snug">
                        {{ $compra->proveedor?->nombre ?: 'Sin Proveedor Asignado' }}
                    </span>
                    <span class="font-mono text-[11px] text-slate-400">
                        RUT: {{ $compra->proveedor?->rut ?: 'Sin RUT' }}
                    </span>
                </div>
            </div>

            {{-- Fecha de Compra --}}
            <div class="flex items-start gap-3 sm:border-l sm:border-slate-100 sm:pl-6">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Fecha de Compra</span>
                    <span class="mt-0.5 block text-sm font-bold text-slate-800">
                        {{ $compra->fecha_compra ? \Carbon\Carbon::parse($compra->fecha_compra)->format('d/m/Y') : '-' }}
                    </span>
                    <span class="text-[11px] text-slate-400">
                        {{ count($compra->detalles ?? []) }} ítem(s) registrados
                    </span>
                </div>
            </div>

            {{-- Monto Total --}}
            <div class="flex items-start gap-3 sm:border-l sm:border-slate-100 sm:pl-6">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Monto Total Compra</span>
                    <span class="mt-0.5 block font-mono text-xl font-extrabold text-emerald-600">
                        ${{ number_format($compra->monto_total ?? 0, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Tabla de Insumos Registrados --}}
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 bg-slate-50/80 px-6 py-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Desglose de Insumos</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="border-b border-slate-200 bg-slate-50/50 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Insumo / Artículo</th>
                            <th scope="col" class="px-6 py-3 text-center">Cantidad</th>
                            <th scope="col" class="px-6 py-3 text-right">Precio Unitario</th>
                            <th scope="col" class="px-6 py-3 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($compra->detalles as $detalle)
                            <tr class="transition-colors hover:bg-slate-50/80">
                                <td class="px-6 py-3.5 font-semibold text-slate-800">
                                    {{ $detalle->inventario?->nombre ?: ($detalle->nombre_insumo ?: 'Insumo sin nombre') }}
                                </td>
                                <td class="px-6 py-3.5 text-center font-bold text-slate-700">
                                    <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 text-xs font-bold text-slate-700">
                                        {{ $detalle->cantidad }}
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 text-right font-mono text-slate-600">
                                    ${{ number_format($detalle->precio_unitario ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-3.5 text-right font-mono font-bold text-slate-800">
                                    ${{ number_format($detalle->subtotal ?? ($detalle->cantidad * $detalle->precio_unitario), 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-400">
                                    No hay ítems registrados en este documento.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="border-t border-slate-200 bg-slate-50/50 font-bold">
                        <tr>
                            <td colspan="3" class="px-6 py-3.5 text-right text-xs uppercase tracking-wider text-slate-500">
                                Total General:
                            </td>
                            <td class="px-6 py-3.5 text-right font-mono text-sm font-extrabold text-emerald-600">
                                ${{ number_format($compra->monto_total ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>
@endsection