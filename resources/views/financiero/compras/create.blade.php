@extends('layouts.app')

@section('titulo', 'Registrar Compra de Insumos')

@section('contenido')
    <div class="mx-auto max-w-6xl space-y-6" x-data="formularioCompra()">
        
        {{-- Encabezado Principal --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-800">Registrar Nueva Compra</h1>
                <p class="mt-1 text-xs font-medium text-slate-500">Ingresa los datos de la compra y sus insumos asociados.</p>
            </div>
            <a
                href="{{ route('financiero.compras.index') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-700 shadow-sm transition-all hover:bg-slate-50 hover:text-slate-900"
            >
                ← Volver al Historial
            </a>
        </div>

        {{-- Formulario Principal --}}
        <form method="POST" action="{{ route('financiero.compras.store') }}" class="space-y-6">
            @csrf

            {{-- Bloque 1: Datos de la Factura --}}
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div>
                        <label for="proveedor_id" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Proveedor</label>
                        <select
                            name="proveedor_id"
                            id="proveedor_id"
                            required
                            class="w-full rounded-lg border-slate-300 text-xs font-medium focus:border-emerald-500 focus:ring-emerald-500 shadow-sm"
                        >
                            <option value="">Selecciona un proveedor...</option>
                            @foreach ($proveedores as $prov)
                                <option value="{{ $prov->id }}">
                                    {{ $prov->nombre }} (★ {{$prov->evaluacion }}/5)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="numero_factura" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">N° Factura / Documento</label>
                        <input
                            type="text"
                            name="numero_factura"
                            id="numero_factura"
                            placeholder="12312355"
                            class="w-full rounded-lg border-slate-300 text-xs font-medium focus:border-emerald-500 focus:ring-emerald-500 shadow-sm"
                        />
                    </div>

                    <div>
                        <label for="fecha_compra" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Fecha de Compra</label>
                        <input
                            type="date"
                            name="fecha_compra"
                            id="fecha_compra"
                            value="{{ $hoy }}"
                            max="{{ $hoy }}"
                            required
                            class="w-full rounded-lg border-slate-300 text-xs font-medium focus:border-emerald-500 focus:ring-emerald-500 shadow-sm"
                        />
                    </div>
                </div>
            </div>

            {{-- Bloque 2: Insumos en Fila Horizontal con Títulos Alineados --}}
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm space-y-3">
                <div class="flex items-center justify-between pb-1">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-700">Insumos y Materiales Adquiridos</h2>
                    <button
                        type="button"
                        @click="agregarItem()"
                        class="text-xs font-bold text-emerald-600 hover:text-emerald-700 transition-colors"
                    >
                        + Agregar Insumo
                    </button>
                </div>

                {{-- Encabezados de Columna Alineados --}}
                <div class="flex items-center gap-3 px-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    <div class="flex-1">Nombre del Insumo</div>
                    <div class="w-28 text-center">Cantidad</div>
                    <div class="w-32 text-right">Precio Unitario ($)</div>
                    <div class="w-24 text-right">Subtotal</div>
                    <div class="w-6"></div>
                </div>

                {{-- Filas Dinámicas de Insumos --}}
                <div class="space-y-3">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex items-center gap-3">
                            
                            {{-- Nombre del Insumo con Autocompletado --}}
                            <div class="relative flex-1" x-data="{ abierto: false, resultados: [] }">
                                <input
                                    type="text"
                                    :name="`items[${index}][nombre_insumo]`"
                                    x-model="item.nombre_insumo"
                                    @input.debounce.300ms="
                                        if (item.nombre_insumo.length >= 2) {
                                            fetch(`{{ route('financiero.compras.buscar-insumos') }}?q=${encodeURIComponent(item.nombre_insumo)}`)
                                                .then(res => res.json())
                                                .then(data => { resultados = data; abierto = true; });
                                        } else {
                                            abierto = false;
                                        }
                                    "
                                    @click.away="abierto = false"
                                    placeholder="Nombre del insumo..."
                                    required
                                    class="w-full rounded-lg border-slate-300 text-xs font-medium focus:border-emerald-500 focus:ring-emerald-500 shadow-sm px-3 py-2.5"
                                    autocomplete="off"
                                />

                                {{-- Menú flotante de búsqueda --}}
                                <div
                                    x-show="abierto && resultados.length > 0"
                                    x-cloak
                                    class="absolute left-0 right-0 z-30 mt-1 max-h-48 overflow-y-auto rounded-lg border border-slate-200 bg-white shadow-xl"
                                >
                                    <template x-for="res in resultados" :key="res.id">
                                        <div
                                            @click="
                                                item.nombre_insumo = res.nombre;
                                                if (res.precio_costo) item.precio_unitario = res.precio_costo;
                                                abierto = false;
                                            "
                                            class="flex items-center justify-between cursor-pointer px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 border-b border-slate-50 last:border-0"
                                        >
                                            <span x-text="res.nombre"></span>
                                            <span class="font-mono text-[10px] text-slate-400" x-text="`($${new Intl.NumberFormat('es-CL').format(res.precio_costo ?? 0)})`"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            {{-- Cantidad --}}
                            <div class="w-28">
                                <input
                                    type="number"
                                    min="1"
                                    :name="`items[${index}][cantidad]`"
                                    x-model.number="item.cantidad"
                                    placeholder="1"
                                    required
                                    class="w-full text-center rounded-lg border-slate-300 text-xs font-medium focus:border-emerald-500 focus:ring-emerald-500 shadow-sm py-2.5"
                                />
                            </div>

                            {{-- Precio Unitario --}}
                            <div class="w-32">
                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    :name="`items[${index}][precio_unitario]`"
                                    x-model.number="item.precio_unitario"
                                    placeholder="0"
                                    required
                                    class="w-full text-right rounded-lg border-slate-300 text-xs font-medium focus:border-emerald-500 focus:ring-emerald-500 shadow-sm py-2.5"
                                />
                            </div>

                            {{-- Subtotal --}}
                            <div class="w-24 text-right font-mono text-xs font-bold text-slate-700">
                                $<span x-text="new Intl.NumberFormat('es-CL').format((item.cantidad || 0) * (item.precio_unitario || 0))"></span>
                            </div>

                            {{-- Botón Eliminar --}}
                            <div class="w-6 text-center">
                                <button
                                    type="button"
                                    @click="eliminarItem(index)"
                                    class="text-slate-400 hover:text-red-500 transition-colors text-sm font-bold"
                                    title="Eliminar fila"
                                >
                                    ✕
                                </button>
                            </div>

                        </div>
                    </template>
                </div>
            </div>

            {{-- Bloque 3: Observaciones y Total Final --}}
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm space-y-6">
                <div>
                    <label for="descripcion" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Notas u Observaciones</label>
                    <textarea
                        name="descripcion"
                        id="descripcion"
                        rows="2"
                        placeholder="Detalles adicionales sobre la entrega o la compra..."
                        class="w-full rounded-lg border-slate-300 text-xs font-medium focus:border-emerald-500 focus:ring-emerald-500 shadow-sm"
                    ></textarea>
                </div>

                <div class="flex items-center justify-between border-t border-slate-100 pt-4">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-0.5">Monto Total Estimado:</span>
                        <div class="font-mono text-2xl font-black text-emerald-600">
                            $<span x-text="calcularTotal()"></span>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="rounded-lg bg-emerald-600 px-6 py-2.5 text-xs font-bold text-white shadow-sm transition-colors hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                    >
                        Registrar Compra
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function formularioCompra() {
            return {
                items: [
                    { nombre_insumo: '', cantidad: 1, precio_unitario: 0 }
                ],
                agregarItem() {
                    this.items.push({ nombre_insumo: '', cantidad: 1, precio_unitario: 0 });
                },
                eliminarItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                    }
                },
                calcularTotal() {
                    let total = this.items.reduce((sum, item) => sum + ((item.cantidad || 0) * (item.precio_unitario || 0)), 0);
                    return new Intl.NumberFormat('es-CL').format(total);
                }
            }
        }
    </script>
@endsection