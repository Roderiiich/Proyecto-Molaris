@extends('layouts.app')

@section('titulo', 'Gestión y Evaluación de Proveedores')

@section('contenido')
    <div class="mx-auto max-w-7xl space-y-6" x-data="{ modalNuevo: false, modalEditar: false, proveedorEdit: {} }">
        
        {{-- Mensaje de Éxito --}}
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
                    Directorio y Calificación de Proveedores
                </h1>
                <p class="mt-1 text-xs font-medium text-slate-500">Administra los proveedores de insumos clínicos, datos de contacto y consulta sus evaluaciones.</p>
            </div>
            <button
                @click="modalNuevo = true"
                class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-xs font-bold text-white shadow-sm transition-colors hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Nuevo Proveedor
            </button>
        </div>

        {{-- Listado de Proveedores en Tarjetas Grid --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($proveedores as $prov)
                <div class="flex flex-col justify-between rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:border-slate-300 hover:shadow-md">
                    <div>
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <h3 class="text-sm font-bold text-slate-800 leading-snug">{{ $prov->nombre }}</h3>
                                <span class="font-mono text-[11px] text-slate-400">RUT: {{ $prov->rut ?: 'Sin RUT' }}</span>
                            </div>
                            
                            {{-- Badge de Evaluación --}}
                            <div class="inline-flex items-center gap-1 rounded-lg border border-amber-200 bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-700 shrink-0">
                                <span class="text-amber-500">★</span>
                                <span>{{ $prov->evaluacion }}/5</span>
                            </div>
                        </div>

                        <div class="my-4 space-y-2 border-y border-slate-100 py-3 text-xs font-medium text-slate-600">
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                <span class="truncate">{{ $prov->email ?: 'Sin correo electrónico' }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                <span>{{ $prov->telefono ?: 'Sin teléfono' }}</span>
                            </div>
                            @if($prov->notas)
                                <p class="mt-2 rounded-lg bg-slate-50 border border-slate-100 p-2.5 text-[11px] font-normal italic text-slate-500 leading-relaxed">
                                    "{{ $prov->notas }}"
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center justify-between border-t border-slate-100 pt-4 text-xs">
                        <span class="font-semibold text-slate-500">
                            {{ $prov->compras_count }} compra(s) · <span class="font-mono text-slate-700 font-bold">${{ number_format($prov->compras_sum_monto_total ?? 0, 0, ',', '.') }}</span>
                        </span>
                        
                        <div class="flex items-center gap-3">
                            <button
                                @click="proveedorEdit = {{ json_encode($prov) }}; modalEditar = true"
                                class="font-bold text-slate-600 transition-colors hover:text-slate-900"
                            >
                                Editar
                            </button>
                            <a
                                href="{{ route('financiero.compras.index', ['proveedor_id' => $prov->id]) }}"
                                class="font-bold text-emerald-600 transition-colors hover:text-emerald-700"
                            >
                                Compras →
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full rounded-xl border border-slate-200 bg-white p-12 text-center">
                    <svg class="mx-auto mb-3 h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <p class="text-sm font-bold text-slate-700">No hay proveedores registrados</p>
                    <p class="mt-1 text-xs text-slate-400">Haz clic en "+ Nuevo Proveedor" para registrar la primera empresa de insumos.</p>
                </div>
            @endforelse
        </div>

        {{-- Modal Crear Proveedor --}}
        <div
            x-show="modalNuevo"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm"
        >
            <div @click.away="modalNuevo = false" class="w-full max-w-xl rounded-2xl border border-slate-200 bg-white shadow-2xl space-y-0 overflow-hidden">
                
                {{-- Header del Modal --}}
                <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/80 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800">Registrar Nuevo Proveedor</h3>
                            <p class="text-[11px] text-slate-500">Ingresa los datos comerciales de la empresa proveedora.</p>
                        </div>
                    </div>
                    <button @click="modalNuevo = false" class="rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors text-base font-bold">&times;</button>
                </div>

                {{-- Cuerpo del Formulario --}}
                <form method="POST" action="{{ route('financiero.proveedores.store') }}" class="space-y-3.5 p-6 text-xs">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                        {{-- Nombre / Razón Social --}}
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Nombre o Razón Social <span class="text-red-500">*</span></label>
                            <input type="text" name="nombre" placeholder="Ej. Dental Distribuidora SpA" required class="w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-800 placeholder-slate-400 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                        </div>

                        {{-- RUT --}}
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">RUT Empresa</label>
                            <input type="text" name="rut" placeholder="76.123.456-7" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-800 placeholder-slate-400 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                        </div>

                        {{-- Teléfono --}}
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Teléfono</label>
                            <input type="text" name="telefono" placeholder="+56 9 1234 5678" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-800 placeholder-slate-400 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                        </div>

                        {{-- Correo Electrónico --}}
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Correo Electrónico</label>
                            <input type="email" name="email" placeholder="contacto@distribuidora.cl" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-800 placeholder-slate-400 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                        </div>

                        {{-- Evaluación Inicial --}}
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Evaluación Inicial</label>
                            <select name="evaluacion" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-800 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                                <option value="5">★★★★★ (5/5) - Excelente</option>
                                <option value="4">★★★★☆ (4/5) - Bueno</option>
                                <option value="3" selected>★★★☆☆ (3/5) - Aceptable / Estándar</option>
                                <option value="2">★★☆☆☆ (2/5) - Regular</option>
                                <option value="1">★☆☆☆☆ (1/5) - Deficiente</option>
                            </select>
                        </div>

                        {{-- Notas u Observaciones --}}
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Notas u Observaciones</label>
                            <textarea name="notas" rows="2" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-800 placeholder-slate-400 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" placeholder="Ej. Tiempos de despacho, ejecutivos de venta, facilidades de crédito..."></textarea>
                        </div>
                    </div>

                    {{-- Footer de Botones --}}
                    <div class="flex items-center justify-end gap-2 border-t border-slate-100 pt-3.5 mt-2">
                        <button type="button" @click="modalNuevo = false" class="rounded-lg border border-slate-200 bg-white px-4 py-1.5 text-xs font-bold text-slate-600 hover:bg-slate-50 transition-colors">Cancelar</button>
                        <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-1.5 text-xs font-bold text-white shadow-sm hover:bg-emerald-700 transition-colors">Guardar Proveedor</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Editar Proveedor --}}
        <div
            x-show="modalEditar"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm"
        >
            <div @click.away="modalEditar = false" class="w-full max-w-xl rounded-2xl border border-slate-200 bg-white shadow-2xl space-y-0 overflow-hidden">
                
                {{-- Header Modal --}}
                <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/80 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-200 text-slate-700">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800">Editar Información del Proveedor</h3>
                            <p class="text-[11px] text-slate-500">Actualiza los datos o la calificación del proveedor seleccionado.</p>
                        </div>
                    </div>
                    <button @click="modalEditar = false" class="rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors text-base font-bold">&times;</button>
                </div>

                {{-- Formulario Editar --}}
                <form :action="`{{ url('financiero/proveedores') }}/${proveedorEdit.id}`" method="POST" class="space-y-3.5 p-6 text-xs">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Nombre o Razón Social <span class="text-red-500">*</span></label>
                            <input type="text" name="nombre" x-model="proveedorEdit.nombre" required class="w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-800 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">RUT Empresa</label>
                            <input type="text" name="rut" x-model="proveedorEdit.rut" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-800 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Teléfono</label>
                            <input type="text" name="telefono" x-model="proveedorEdit.telefono" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-800 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Correo Electrónico</label>
                            <input type="email" name="email" x-model="proveedorEdit.email" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-800 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Calificación</label>
                            <select name="evaluacion" x-model="proveedorEdit.evaluacion" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-800 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                                <option value="5">★★★★★ (5/5) - Excelente</option>
                                <option value="4">★★★★☆ (4/5) - Bueno</option>
                                <option value="3">★★★☆☆ (3/5) - Aceptable</option>
                                <option value="2">★★☆☆☆ (2/5) - Regular</option>
                                <option value="1">★☆☆☆☆ (1/5) - Deficiente</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Notas u Observaciones</label>
                            <textarea name="notas" x-model="proveedorEdit.notas" rows="2" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-800 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"></textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-slate-100 pt-3.5 mt-2">
                        <button type="button" @click="modalEditar = false" class="rounded-lg border border-slate-200 bg-white px-4 py-1.5 text-xs font-bold text-slate-600 hover:bg-slate-50 transition-colors">Cancelar</button>
                        <button type="submit" class="rounded-lg bg-slate-800 px-4 py-1.5 text-xs font-bold text-white shadow-sm hover:bg-slate-900 transition-colors">Actualizar Proveedor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection