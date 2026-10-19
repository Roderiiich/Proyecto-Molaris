@extends('layouts.app')

@section('titulo', 'Historial de Compras de Insumos')

@section('contenido')
    <div class="mx-auto max-w-7xl space-y-6">
        
        {{-- Mensajes de Notificación --}}
        @if(session('success'))
            <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50/80 p-4 text-xs font-bold text-emerald-800 shadow-sm backdrop-blur-sm">
                <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Encabezado Principal --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-800">
                    Historial de Compras e Insumos
                </h1>
                <p class="mt-1 text-xs font-medium text-slate-500">Consulta y gestiona el registro de compras, facturas e insumos ingresados al stock.</p>
            </div>
            <a
                href="{{ route('financiero.compras.create') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-xs font-bold text-white shadow-sm transition-colors hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 shrink-0"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Registrar Compra
            </a>
        </div>

        {{-- Bloque de Filtros de Búsqueda (Distribución exacta según boceto) --}}
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="GET" action="{{ route('financiero.compras.index') }}" class="space-y-4">
                
                {{-- Fila Superior: Proveedor y N° Factura (2 columnas simétricas) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="proveedor_id" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                            Proveedor
                        </label>
                        <select
                            name="proveedor_id"
                            id="proveedor_id"
                            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                        >
                            <option value="">Todos los proveedores</option>
                            @foreach ($proveedores as $prov)
                                <option value="{{ $prov->id }}" {{ request('proveedor_id') == $prov->id ? 'selected' : '' }}>
                                    {{ $prov->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="numero_factura" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                            N° Factura / Doc.
                        </label>
                        <input
                            type="text"
                            name="numero_factura"
                            id="numero_factura"
                            value="{{ request('numero_factura') }}"
                            placeholder="Ej. 123456"
                            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 placeholder-slate-400 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                        />
                    </div>
                </div>

                {{-- Fila Media: Fecha Desde y Fecha Hasta (2 columnas simétricas) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="fecha_desde" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                            Fecha Desde
                        </label>
                        <input
                            type="date"
                            name="fecha_desde"
                            id="fecha_desde"
                            value="{{ request('fecha_desde') }}"
                            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                        />
                    </div>

                    <div>
                        <label for="fecha_hasta" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                            Fecha Hasta
                        </label>
                        <input
                            type="date"
                            name="fecha_hasta"
                            id="fecha_hasta"
                            value="{{ request('fecha_hasta') }}"
                            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                        />
                    </div>
                </div>

                {{-- Fila Inferior: Botones alineados a la derecha al final --}}
                <div class="flex items-center justify-end gap-3 pt-2">
                    <a
                        href="{{ route('financiero.compras.index') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-1.5 text-xs font-bold text-slate-600 shadow-sm transition-colors hover:bg-slate-50 hover:text-slate-900"
                    >
                        Limpiar
                    </a>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-slate-800 px-5 py-1.5 text-xs font-bold text-white shadow-sm transition-colors hover:bg-slate-900 focus:outline-none"
                    >
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Filtrar
                    </button>
                </div>

            </form>
        </div>

        {{-- Tabla de Registro de Compras --}}
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="border-b border-slate-200 bg-slate-50 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">N° Doc / Factura</th>
                            <th scope="col" class="px-6 py-3">Proveedor</th>
                            <th scope="col" class="px-6 py-3">Fecha Compra</th>
                            <th scope="col" class="px-6 py-3 text-center">Insumos</th>
                            <th scope="col" class="px-6 py-3 text-right">Monto Total</th>
                            <th scope="col" class="px-6 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($compras as $compra)
                            <tr class="transition-colors hover:bg-slate-50/80">
                                <td class="px-6 py-3.5 font-mono font-bold text-slate-800">
                                    {{ $compra->numero_factura ?: 'S/N' }}
                                </td>
                                <td class="px-6 py-3.5 font-semibold text-slate-700">
                                    {{ $compra->proveedor?->nombre ?: 'Sin Proveedor' }}
                                </td>
                                <td class="px-6 py-3.5 text-slate-500">
                                    {{ $compra->fecha_compra ? \Carbon\Carbon::parse($compra->fecha_compra)->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-6 py-3.5 text-center font-bold text-slate-600">
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-[10px] font-bold text-slate-700">
                                        {{ $compra->items_count ?? $compra->detalles_count ?? count($compra->detalles ?? []) }} ítem(s)
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 text-right font-mono font-extrabold text-emerald-600">
                                    ${{ number_format($compra->monto_total ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-3.5 text-center">
                                    <a
                                        href="{{ route('financiero.compras.show', $compra->id) }}"
                                        class="font-bold text-emerald-600 hover:text-emerald-800 transition-colors"
                                    >
                                        Ver Detalle →
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                    <svg class="mx-auto mb-3 h-10 w-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    <p class="text-xs font-bold text-slate-600">No se encontraron compras registradas</p>
                                    <p class="mt-1 text-[11px] text-slate-400">Intenta ajustar los filtros de búsqueda o haz clic en "Limpiar" para restablecer.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            @if(method_exists($compras, 'hasPages') && $compras->hasPages())
                <div class="border-t border-slate-100 px-6 py-3">
                    {{ $compras->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection