<x-app-layout>
    <x-slot name="header">
        <div
            class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between"
        >
            <div>
                <h2 class="text-2xl font-bold leading-tight text-slate-800">
                    Gestión de Inventario e Insumos
                </h2>
                <p class="mt-1 text-xs text-slate-400">Control de stock, insumos médicos y trazabilidad de material en tiempo real.</p>
            </div>
        </div>
    </x-slot>

    <div
        class="py-6"
        x-data="{
            openModal: false,
            openStockModal: false,
            selectedItem: null,
            search: '',
        }"
    >
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if ($errors->any())
                <div
                    class="rounded-2xl border border-rose-500/30 bg-rose-500/10 p-4 text-rose-600"
                >
                    <ul class="list-disc pl-5 text-xs font-bold">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <!-- MENSAJE DE ÉXITO -->
            @if (session('success'))
                <div
                    class="flex items-center justify-between rounded-2xl border border-emerald-500/30 bg-emerald-500/10 p-4 text-emerald-600"
                >
                    <div class="flex items-center gap-2">
                        <span
                            class="h-2 w-2 rounded-full bg-emerald-500"
                        ></span>
                        <span
                            class="text-xs font-bold"
                            >{{ session('success') }}</span
                        >
                    </div>
                    <button
                        type="button"
                        @click="$el.parentElement.remove()"
                        class="text-sm font-bold text-emerald-500 hover:text-emerald-700"
                    >
                        ✕
                    </button>
                </div>
            @endif

            <!-- TARJETAS DE RESUMEN -->
            <div style="display: flex; gap: 1rem; width: 100%; flex-wrap: wrap">
                <!-- TOTAL INSUMOS -->
                <div
                    style="flex: 1 1 250px; min-width: 0"
                    class="shadow-xs flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-5"
                >
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Insumos</p>
                        <h3
                            class="mt-0.5 text-2xl font-extrabold text-slate-800"
                        >
                            {{ $totalArticulos }}
                        </h3>
                        <span
                            class="mt-1 flex items-center gap-1 text-[11px] font-semibold text-cyan-600"
                        >
                            <span
                                class="h-2 w-2 rounded-full bg-cyan-500"
                            ></span>
                            Catálogo registrado
                        </span>
                    </div>
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center bg-cyan-50 text-cyan-600"
                        style="border-radius: 9999px; aspect-ratio: 1 / 1"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m0 0v10l8 4" />
                        </svg>
                    </div>
                </div>

                <!-- STOCK CRÍTICO -->
                <div
                    style="flex: 1 1 250px; min-width: 0"
                    class="shadow-xs flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-5"
                >
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Stock Crítico</p>
                        <h3
                            class="mt-0.5 text-2xl font-extrabold text-slate-800"
                        >
                            {{ $stockBajo }}
                        </h3>
                        <span
                            class="mt-1 flex items-center gap-1 text-[11px] font-semibold text-amber-600"
                        >
                            <span
                                class="h-2 w-2 rounded-full bg-amber-500"
                            ></span>
                            Requieren reposición
                        </span>
                    </div>
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center bg-amber-50 text-amber-600"
                        style="border-radius: 9999px; aspect-ratio: 1 / 1"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 1.732z" />
                        </svg>
                    </div>
                </div>

                <!-- SIN STOCK -->
                <div
                    style="flex: 1 1 250px; min-width: 0"
                    class="shadow-xs flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-5"
                >
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Sin Stock</p>
                        <h3
                            class="mt-0.5 text-2xl font-extrabold text-slate-800"
                        >
                            {{ $agotados }}
                        </h3>
                        <span
                            class="mt-1 flex items-center gap-1 text-[11px] font-semibold text-rose-600"
                        >
                            <span
                                class="h-2 w-2 rounded-full bg-rose-500"
                            ></span>
                            Fuera de servicio
                        </span>
                    </div>
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center bg-red-500 text-white"
                        style="border-radius: 9999px; aspect-ratio: 1 / 1"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- TABLA DE INVENTARIO -->
            <div
                class="shadow-xs overflow-hidden rounded-2xl border border-slate-200 bg-white"
            >
                <!-- ENCABEZADO -->
                <div
                    class="flex flex-col gap-4 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:justify-between"
                >
                    <div>
                        <h3 class="text-base font-extrabold text-slate-800">
                            Catálogo de Insumos
                        </h3>
                        <p class="mt-0.5 text-xs text-slate-400">Listado general de materiales disponibles en clínica</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <!-- INPUT DE BÚSQUEDA POR NOMBRE -->
                        <div class="relative min-w-[200px]">
                            <div
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"
                            >
                                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input
                                type="text"
                                x-model="search"
                                placeholder="Buscar insumo..."
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2 pl-9 pr-8 text-xs text-slate-700 placeholder-slate-400 transition focus:border-cyan-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-cyan-500"
                            />
                            <button
                                type="button"
                                x-show="search.length > 0"
                                @click="search = ''"
                                class="absolute inset-y-0 right-0 flex items-center pr-2.5 text-xs font-bold text-slate-400 hover:text-slate-600"
                            >
                                ✕
                            </button>
                        </div>

                        <!-- BOTÓN EXPORTAR EXCEL -->
                        <a
                            href="{{ route('inventario.exportExcel') }}"
                            class="flex cursor-pointer items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white shadow-md transition duration-150 hover:bg-black"
                        >
                            <svg class="h-4 w-4 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Exportar Pedido Excel
                        </a>

                        <!-- BOTÓN REGISTRAR INSUMO -->
                        <button
                            type="button"
                            @click="openModal = true"
                            class="flex cursor-pointer items-center gap-2 rounded-xl bg-molaris-dark px-4 py-2 text-xs font-bold text-white shadow-md transition duration-150 hover:bg-black"
                        >
                            <svg class="h-4 w-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                            </svg>
                            Registrar Insumo
                        </button>
                    </div>
                </div>

                <!-- TABLA -->
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-left">
                        <thead>
                            <tr
                                class="border-b border-slate-200 bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-400"
                            >
                                <th class="px-5 py-3.5">Insumo</th>
                                <th class="px-5 py-3.5">Categoría</th>
                                <th class="px-5 py-3.5">Stock Actual</th>
                                <th class="px-5 py-3.5">Unidad</th>
                                <th class="px-5 py-3.5">Estado</th>
                                <th class="px-5 py-3.5 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @if (count($articulos) > 0)
                                @foreach ($articulos as $item)
                                    <tr
                                        x-show="search === '' || '{{ strtolower($item->nombre) }}'.includes(search.toLowerCase())"
                                        class="transition hover:bg-slate-50/80"
                                    >
                                        <!-- INSUMO -->
                                        <td class="px-5 py-4">
                                            <div
                                                class="font-bold text-slate-800"
                                            >
                                                {{ $item->nombre }}
                                            </div>
                                            @if ($item->codigo_barras)
                                                <span
                                                    class="font-mono text-[11px] text-slate-400"
                                                >
                                                    Cód: {{ $item->codigo_barras }}
                                                </span>
                                            @endif
                                        </td>

                                        <!-- CATEGORÍA -->
                                        <td
                                            class="px-5 py-4 font-semibold text-slate-600"
                                        >
                                            {{ $item->categoria }}
                                        </td>

                                        <!-- STOCK -->
                                        <td class="px-5 py-4">
                                            <span
                                                class="font-extrabold text-base {{ $item->stock_actual <=$item->stock_minimo ? 'text-amber-600' : 'text-slate-800' }}"
                                            >
                                                {{ $item->stock_actual }}
                                            </span>
                                            <span
                                                class="block text-[11px] font-medium text-slate-400"
                                            >
                                                Mínimo: {{ $item->stock_minimo }}
                                            </span>
                                        </td>

                                        <!-- UNIDAD -->
                                        <td
                                            class="px-5 py-4 font-medium text-slate-500"
                                        >
                                            {{ $item->unidad_medida }}
                                        </td>

                                        <!-- ESTADO -->
                                        <td class="px-5 py-4">
                                            @if ($item->stock_actual <= 0)
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full border border-rose-200 bg-rose-50 px-2.5 py-1 text-xs font-bold text-rose-600"
                                                >
                                                    <span
                                                        class="h-1.5 w-1.5 rounded-full bg-rose-500"
                                                    ></span>
                                                    Agotado
                                                </span>
                                            @elseif ($item->stock_actual <=$item->stock_minimo)
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-600"
                                                >
                                                    <span
                                                        class="h-1.5 w-1.5 rounded-full bg-amber-500"
                                                    ></span>
                                                    Stock Bajo
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-600"
                                                >
                                                    <span
                                                        class="h-1.5 w-1.5 rounded-full bg-emerald-500"
                                                    ></span>
                                                    Disponible
                                                </span>
                                            @endif
                                        </td>

                                        <!-- ACCIONES -->
                                        <td
                                            class="space-x-2 px-5 py-4 text-right"
                                        >
                                            <button
                                                type="button"
                                                @click="
                                                    openStockModal = true;
                                                    selectedItem = @js($item);
                                                "
                                                class="cursor-pointer rounded-xl border border-cyan-200 bg-cyan-50 px-3 py-1.5 text-xs font-bold text-cyan-700 transition hover:bg-cyan-100"
                                            >
                                                Ajustar Stock
                                            </button>

                                            <form
                                                action="{{ route('inventario.destroy', $item->id) }}"
                                                method="POST"
                                                class="inline-block"
                                                onsubmit="
                                                    return confirm(
                                                        '¿Deseas eliminar este insumo del catálogo?'
                                                    );
                                                "
                                            >
                                                @csrf
                                                @method ('DELETE')
                                                <button
                                                    type="submit"
                                                    class="cursor-pointer rounded-xl border border-rose-200 bg-red-600 px-2.5 py-1.5 text-xs font-bold text-white transition hover:bg-rose-50"
                                                >
                                                    Eliminar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td
                                        colspan="6"
                                        class="py-12 text-center font-medium text-slate-400"
                                    >
                                        No hay insumos registrados en el
                                        inventario.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

           
            <!-- MODAL REGISTRAR NUEVO INSUMO -->
            <div
                x-show="openModal"
                x-cloak
                class="fixed inset-0 z-[9999] flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm"
            >
                <div
                    @click.away="openModal = false"
                    class="w-full max-w-md overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl p-4"
                >
                    <!-- ENCABEZADO -->
                    <div
                        class="flex items-center justify-between border-b border-slate-100 px-5 py-4"
                    >
                        <div>
                            <h3 class="text-base font-bold text-slate-800">
                                Registrar Nuevo Insumo
                            </h3>
                            <p class="mt-0.5 text-xs text-slate-400">Completa los datos del nuevo insumo</p>
                        </div>

                        <button
                            type="button"
                            @click="openModal = false"
                            class="flex h-8 w-8 items-center justify-center rounded-lg text-lg font-semibold text-slate-400 transition hover:bg-slate-100 hover:text-slate-600"
                        >
                            ✕
                        </button>
                    </div>

                    <!-- FORMULARIO -->
                    <form
                        action="{{ route('inventario.store') }}"
                        method="POST"
                        class="space-y-4 px-5 py-5"
                    >
                        @csrf

                        <!-- NOMBRE -->
                        <div>
                            <label
                                for="nombre"
                                class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-700"
                            >
                                Nombre del Insumo
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                id="nombre"
                                type="text"
                                name="nombre"
                                required
                                placeholder="Ej: Anestesia Cartucho 2%"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-cyan-500 focus:bg-white focus:ring-2 focus:ring-cyan-500/10"
                            />
                        </div>

                        <!-- CATEGORÍA -->
                        <div>
                            <label
                                for="categoria"
                                class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-700"
                            >
                                Categoría <span class="text-red-500">*</span>
                            </label>

                            <select
                                id="categoria"
                                name="categoria"
                                required
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:bg-white focus:ring-2 focus:ring-cyan-500/10"
                            >
                                <option value="Anestesia">Anestesia</option>

                                <option value="Protección">
                                    Protección (Guantes/Mascarillas)
                                </option>

                                <option value="Restauración">
                                    Restauración (Resinas/Adhesivos)
                                </option>

                                <option value="Endodoncia">Endodoncia</option>

                                <option value="Cirugía">
                                    Cirugía / Suturas
                                </option>

                                <option value="Insumo General">
                                    Insumo General
                                </option>
                            </select>
                        </div>

                        <!-- UNIDAD DE MEDIDA -->
                        <div>
                            <label
                                for="unidad_medida"
                                class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-700"
                            >
                                Unidad de Medida
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                id="unidad_medida"
                                type="text"
                                name="unidad_medida"
                                required
                                placeholder="Ej: Caja x 50, Par, Ml"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-cyan-500 focus:bg-white focus:ring-2 focus:ring-cyan-500/10"
                            />
                        </div>

                        <!-- STOCK -->
                        <div class="grid grid-cols-2 gap-3">
                            <!-- STOCK INICIAL -->
                            <div>
                                <label
                                    for="stock_actual"
                                    class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-700"
                                >
                                    Stock Inicial
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    id="stock_actual"
                                    type="number"
                                    name="stock_actual"
                                    min="0"
                                    value="0"
                                    required
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:bg-white focus:ring-2 focus:ring-cyan-500/10"
                                />
                            </div>

                            <!-- STOCK MÍNIMO -->
                            <div>
                                <label
                                    for="stock_minimo"
                                    class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-700"
                                >
                                    Stock Mínimo
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    id="stock_minimo"
                                    type="number"
                                    name="stock_minimo"
                                    min="1"
                                    value="5"
                                    required
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-cyan-500 focus:bg-white focus:ring-2 focus:ring-cyan-500/10"
                                />
                            </div>
                        </div>

                        <!-- BOTONES -->
                        <div
                            class="flex items-center justify-end gap-2 border-t border-slate-100 pt-4"
                        >
                            <button
                                type="button"
                                @click="openModal = false"
                                class="cursor-pointer rounded-xl px-4 py-2.5 text-xs font-bold text-slate-600 transition hover:bg-slate-100"
                            >
                                Cancelar
                            </button>

                            <button
                                type="submit"
                                class="cursor-pointer rounded-xl bg-molaris-dark px-5 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-black hover:shadow-md"
                            >
                                Guardar Insumo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            

            <!-- MODAL AJUSTAR STOCK MANUAL -->
            <div
                x-show="openStockModal"
                x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm"
                style="
                    position: fixed;
                    top: 0;
                    right: 0;
                    bottom: 0;
                    left: 0;
                    z-index: 9999;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background-color: rgba(15, 23, 42, 0.6);
                "
            >
                <div
                    @click.away="openStockModal = false"
                    class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl"
                    style="max-height: 90vh; overflow-y: auto"
                >
                    <div
                        class="flex items-center justify-between border-b border-slate-100 pb-3"
                    >
                        <h3 class="text-base font-bold text-slate-800">
                            Ajustar Stock Manual
                        </h3>
                        <button
                            type="button"
                            @click="openStockModal = false"
                            class="text-lg font-bold text-slate-400 hover:text-slate-600"
                        >
                            ✕
                        </button>
                    </div>

                    <template x-if="selectedItem">
                        <form
                            :action="`/inventario/${selectedItem.id}/stock`"
                            method="POST"
                            class="mt-4 space-y-4"
                        >
                            @csrf
                            @method ('PATCH')

                            <p class="text-xs text-slate-500">
                                Insumo seleccionado:
                                <strong
                                    class="text-slate-800"
                                    x-text="selectedItem.nombre"
                                ></strong>
                            </p>

                            <!-- CANTIDAD DIRECTA A ACTUALIZAR -->
                            <div>
                                <label
                                    class="mb-1 block text-xs font-bold uppercase text-slate-700"
                                >
                                    Nuevo Stock Actual
                                </label>
                                <input
                                    type="number"
                                    name="stock_actual"
                                    min="0"
                                    x-model="selectedItem.stock_actual"
                                    required
                                    placeholder="Ingrese el stock actualizado"
                                    class="w-full rounded-xl border-slate-200 text-sm focus:border-cyan-500 focus:ring-cyan-500"
                                />
                            </div>

                            <div
                                class="flex justify-end gap-2 border-t border-slate-100 pt-4"
                            >
                                <button
                                    type="button"
                                    @click="openStockModal = false"
                                    class="cursor-pointer rounded-xl px-4 py-2 text-xs font-bold text-slate-600 transition hover:bg-slate-100"
                                >
                                    Cancelar
                                </button>
                                <button
                                    type="submit"
                                    class="cursor-pointer rounded-xl bg-molaris-dark px-4 py-2 text-xs font-bold text-white transition hover:bg-black"
                                >
                                    Actualizar Stock
                                </button>
                            </div>
                        </form>
                    </template>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
