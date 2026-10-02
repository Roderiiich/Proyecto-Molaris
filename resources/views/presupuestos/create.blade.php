
@extends('layouts.app')

@section('titulo', 'Nuevo Presupuesto')

@section('contenido')

<div
    class="max-w-7xl mx-auto space-y-6 pb-10"
    x-data="formularioPresupuesto(@js($pacientes))"
>

    {{-- ENCABEZADO --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-2">
                <a
                    href="{{ route('financiero.presupuestos.index') }}"
                    class="hover:text-emerald-600 transition-colors"
                >
                    Presupuestos
                </a>

                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5l7 7-7 7"
                    />
                </svg>

                <span class="text-slate-400">
                    Nuevo
                </span>
            </div>

            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                Nuevo presupuesto clínico
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Crea un presupuesto detallado para el plan de tratamiento del paciente.
            </p>
        </div>

        <a
            href="{{ route('financiero.presupuestos.index') }}"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl
                   border border-slate-200 bg-white text-sm font-semibold text-slate-700
                   hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M10 19l-7-7m0 0l7-7m-7 7h18"
                />
            </svg>

            Volver a presupuestos
        </a>

    </div>


    {{-- FORMULARIO --}}
    <form
        action="{{ route('financiero.presupuestos.store') }}"
        method="POST"
        class="space-y-6"
    >

        @csrf


        {{-- INFORMACIÓN GENERAL --}}
        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-visible">

            {{-- Header --}}
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/60">

                <div class="flex items-start gap-3">

                    <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">

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
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                            />
                        </svg>

                    </div>

                    <div>
                        <h2 class="text-base font-bold text-slate-900">
                            Información del presupuesto
                        </h2>

                        <p class="text-xs text-slate-500 mt-0.5">
                            Selecciona el paciente y el odontólogo responsable del tratamiento.
                        </p>
                    </div>

                </div>

            </div>


            {{-- Contenido --}}
            <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- PACIENTE --}}
                <div
                    class="relative"
                    @click.away="busquedaAbierta = false"
                >

                    <label class="block text-sm font-semibold text-slate-800 mb-2">
                        Paciente
                        <span class="text-rose-500">*</span>
                    </label>

                    <input
                        type="hidden"
                        name="paciente_id"
                        :value="pacienteSeleccionado ? pacienteSeleccionado.id : ''"
                        required
                    >


                    {{-- Buscador --}}
                    <div class="relative">

                        <input
                            type="text"
                            x-model="busquedaPaciente"
                            @focus="busquedaAbierta = true"
                            placeholder="Buscar por nombre o RUT..."
                            autocomplete="off"
                            class="w-full h-11 rounded-xl border border-slate-200
                                   bg-white pl-11 pr-11 text-sm text-slate-700
                                   placeholder:text-slate-400
                                   focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10
                                   transition-all"
                        >

                        {{-- Icono --}}
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">

                            <svg
                                class="w-5 h-5 text-slate-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                />
                            </svg>

                        </div>


                        {{-- Limpiar --}}
                        <button
                            type="button"
                            x-show="pacienteSeleccionado"
                            x-cloak
                            @click="limpiarPaciente()"
                            class="absolute inset-y-0 right-0 px-3 flex items-center
                                   text-slate-400 hover:text-rose-500 transition-colors"
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
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>

                        </button>

                    </div>


                    {{-- Resultados --}}
                    <div
                        x-show="busquedaAbierta && pacientesFiltrados.length > 0"
                        x-transition
                        x-cloak
                        class="absolute left-0 right-0 z-50 mt-2 bg-white
                               border border-slate-200 rounded-xl shadow-xl
                               overflow-hidden"
                    >

                        <div class="px-4 py-2.5 bg-slate-50 border-b border-slate-100">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                Pacientes encontrados
                            </p>
                        </div>

                        <div class="max-h-64 overflow-y-auto">

                            <template
                                x-for="p in pacientesFiltrados"
                                :key="p.id"
                            >

                                <button
                                    type="button"
                                    @click="seleccionarPaciente(p)"
                                    class="w-full text-left px-4 py-3
                                           hover:bg-emerald-50 transition-colors
                                           border-b border-slate-100 last:border-0"
                                >

                                    <div class="flex items-center justify-between gap-4">

                                        <div class="min-w-0">

                                            <p
                                                class="text-sm font-semibold text-slate-800 truncate"
                                                x-text="p.nombre || 'Paciente sin nombre'"
                                            ></p>

                                            <p
                                                class="text-xs text-slate-500 mt-0.5"
                                                x-text="`RUT: ${p.rut || 'Sin registro'}`"
                                            ></p>

                                        </div>

                                        <span class="flex-shrink-0 text-xs font-semibold text-emerald-600">
                                            Seleccionar
                                        </span>

                                    </div>

                                </button>

                            </template>

                        </div>

                    </div>


                    {{-- Sin resultados --}}
                    <div
                        x-show="busquedaAbierta && busquedaPaciente.length > 0 && pacientesFiltrados.length === 0"
                        x-cloak
                        class="absolute left-0 right-0 z-50 mt-2 p-5
                               bg-white border border-slate-200 rounded-xl shadow-xl"
                    >

                        <div class="text-center">

                            <div class="mx-auto w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center mb-2">

                                <svg
                                    class="w-5 h-5 text-slate-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>

                            </div>

                            <p class="text-sm font-medium text-slate-700">
                                No se encontraron pacientes
                            </p>

                            <p class="text-xs text-slate-400 mt-1">
                                Intenta con otro nombre o RUT.
                            </p>

                        </div>

                    </div>


                    @error('paciente_id')
                        <p class="mt-1.5 text-xs font-medium text-rose-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- ODONTÓLOGO --}}
                <div>

                    <label
                        for="dentista_id"
                        class="block text-sm font-semibold text-slate-800 mb-2"
                    >
                        Odontólogo tratante
                        <span class="text-rose-500">*</span>
                    </label>

                    <div class="relative">

                        <select
                            name="dentista_id"
                            id="dentista_id"
                            required
                            class="w-full h-11 rounded-xl border border-slate-200
                                   bg-white px-3.5 text-sm text-slate-700
                                   focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10
                                   transition-all appearance-none"
                        >

                            <option value="">
                                Seleccionar odontólogo
                            </option>

                            @foreach($dentistas as $dentista)

                                <option
                                    value="{{ $dentista->id }}"
                                    {{ old('dentista_id') == $dentista->id ? 'selected' : '' }}
                                >
                                    Dr(a). {{ $dentista->usuario->name ?? 'Sin Nombre' }}

                                    @if(!empty($dentista->especialidad))
                                        - {{ $dentista->especialidad }}
                                    @endif
                                </option>

                            @endforeach

                        </select>

                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">

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
                                    d="M19 9l-7 7-7-7"
                                />
                            </svg>

                        </div>

                    </div>

                    @error('dentista_id')
                        <p class="mt-1.5 text-xs font-medium text-rose-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

            </div>

        </section>


        {{-- PLAN DE TRATAMIENTO --}}
        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            {{-- Header --}}
            <div class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white">

                <div class="flex items-center gap-3">

                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">

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
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                            />
                        </svg>

                    </div>

                    <div>

                        <h2 class="text-base font-bold text-slate-900">
                            Plan de tratamiento
                        </h2>

                        <p class="text-xs text-slate-500 mt-0.5">
                            Agrega las prestaciones incluidas en el presupuesto.
                        </p>

                    </div>

                </div>


                <button
                    type="button"
                    @click="agregarItem()"
                    class="inline-flex items-center justify-center gap-2
                           px-4 py-2.5 rounded-xl
                           bg-emerald-600 hover:bg-emerald-700
                           text-white text-sm font-semibold
                           shadow-sm hover:shadow transition-all"
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
                            d="M12 4v16m8-8H4"
                        />
                    </svg>

                    Agregar prestación

                </button>

            </div>


            {{-- Tabla --}}
            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-slate-50 border-b border-slate-200">

                        <tr>

                            <th class="px-5 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 min-w-[280px]">
                                Prestación
                            </th>

                            <th class="px-4 py-3.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 min-w-[180px]">
                                Especialidad
                            </th>

                            <th class="px-4 py-3.5 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500 w-28">
                                Cantidad
                            </th>

                            <th class="px-4 py-3.5 text-right text-[11px] font-bold uppercase tracking-wider text-slate-500 min-w-[150px]">
                                Precio unitario
                            </th>

                            <th class="px-4 py-3.5 text-right text-[11px] font-bold uppercase tracking-wider text-slate-500 min-w-[150px]">
                                Subtotal
                            </th>

                            <th class="px-4 py-3.5 w-14"></th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        <template
                            x-for="(item, index) in items"
                            :key="index"
                        >

                            <tr class="group hover:bg-slate-50/60 transition-colors">

                                {{-- Prestación --}}
                                <td class="px-5 py-3.5">

                                    <input
                                        type="text"
                                        :name="`items[${index}][nombre_tratamiento]`"
                                        x-model="item.nombre_tratamiento"
                                        placeholder="Ej. Obturación de resina..."
                                        required
                                        class="w-full h-10 rounded-lg border border-slate-200
                                               text-sm text-slate-700
                                               placeholder:text-slate-400
                                               focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10"
                                    >

                                </td>


                                {{-- Especialidad --}}
                                <td class="px-4 py-3.5">

                                    <select
                                        :name="`items[${index}][especialidad]`"
                                        x-model="item.especialidad"
                                        class="w-full h-10 rounded-lg border border-slate-200
                                               text-sm text-slate-700
                                               focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10"
                                    >

                                        <option value="Odontología General">
                                            Odontología General
                                        </option>

                                        <option value="Endodoncia">
                                            Endodoncia
                                        </option>

                                        <option value="Ortodoncia">
                                            Ortodoncia
                                        </option>

                                        <option value="Periodoncia">
                                            Periodoncia
                                        </option>

                                        <option value="Cirugía Bucal">
                                            Cirugía Bucal
                                        </option>

                                        <option value="Implantología">
                                            Implantología
                                        </option>

                                        <option value="Rehabilitación Oral">
                                            Rehabilitación Oral
                                        </option>

                                    </select>

                                </td>


                                {{-- Cantidad --}}
                                <td class="px-4 py-3.5">

                                    <input
                                        type="number"
                                        :name="`items[${index}][cantidad]`"
                                        x-model.number="item.cantidad"
                                        min="1"
                                        required
                                        class="w-full h-10 rounded-lg border border-slate-200
                                               text-center text-sm font-semibold text-slate-700
                                               focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10"
                                    >

                                </td>


                                {{-- Precio --}}
                                <td class="px-4 py-3.5">

                                    <div class="relative">

                                        <span class="absolute inset-y-0 left-3 flex items-center text-xs font-semibold text-slate-400">
                                            $
                                        </span>

                                        <input
                                            type="number"
                                            :name="`items[${index}][precio_unitario]`"
                                            x-model.number="item.precio_unitario"
                                            min="0"
                                            step="1000"
                                            required
                                            class="w-full h-10 rounded-lg border border-slate-200
                                                   pl-7 pr-3 text-right text-sm font-semibold
                                                   text-slate-700 font-mono
                                                   focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10"
                                        >

                                    </div>

                                </td>


                                {{-- Subtotal --}}
                                <td class="px-4 py-3.5 text-right">

                                    <span class="inline-flex items-center justify-end min-w-[120px]
                                                 px-3 py-2 rounded-lg bg-slate-100
                                                 text-slate-800 font-bold font-mono text-sm">

                                        $<span x-text="formatearMoneda(item.cantidad * item.precio_unitario)"></span>

                                    </span>

                                </td>


                                {{-- Eliminar --}}
                                <td class="px-4 py-3.5 text-center">

                                    <button
                                        type="button"
                                        @click="removerItem(index)"
                                        x-show="items.length > 1"
                                        x-cloak
                                        title="Eliminar prestación"
                                        class="w-9 h-9 inline-flex items-center justify-center
                                               rounded-lg text-slate-400
                                               hover:text-rose-600 hover:bg-rose-50
                                               transition-colors"
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
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                            />
                                        </svg>

                                    </button>

                                </td>

                            </tr>

                        </template>

                    </tbody>

                </table>

            </div>


            {{-- RESUMEN --}}
            <div class="border-t border-slate-200 bg-slate-50/70 px-6 py-5">

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Prestaciones
                        </p>

                        <p class="mt-1 text-sm font-semibold text-slate-700">
                            <span x-text="items.length"></span>
                            <span x-text="items.length === 1 ? 'prestación agregada' : 'prestaciones agregadas'"></span>
                        </p>

                    </div>


                    <div class="flex items-center justify-between sm:justify-end gap-5">

                        <div class="text-right">

                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Total del presupuesto
                            </p>

                            <p class="mt-0.5 text-2xl font-black text-emerald-600 font-mono tracking-tight">
                                $<span x-text="formatearMoneda(calcularTotalGeneral())"></span>
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </section>


        {{-- OBSERVACIONES --}}
        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="px-6 py-5 border-b border-slate-100">

                <div class="flex items-center gap-3">

                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center">

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
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                            />
                        </svg>

                    </div>

                    <div>

                        <h2 class="text-base font-bold text-slate-900">
                            Observaciones y condiciones
                        </h2>

                        <p class="text-xs text-slate-500 mt-0.5">
                            Agrega información adicional relacionada con el presupuesto.
                        </p>

                    </div>

                </div>

            </div>


            <div class="p-6">

                <label
                    for="observaciones"
                    class="block text-sm font-semibold text-slate-800 mb-2"
                >
                    Observaciones
                </label>

                <textarea
                    name="observaciones"
                    id="observaciones"
                    rows="4"
                    class="w-full rounded-xl border border-slate-200
                           text-sm text-slate-700
                           placeholder:text-slate-400
                           focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10
                           transition-all resize-y"
                    placeholder="Ej: Presupuesto válido por 30 días. Incluye controles postoperatorios sin costo..."
                ></textarea>

            </div>

        </section>


        {{-- ACCIONES --}}
        <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3 pt-2">

            <a
                href="{{ route('financiero.presupuestos.index') }}"
                class="inline-flex items-center justify-center px-5 py-2.5
                       rounded-xl border border-slate-200 bg-white
                       text-sm font-semibold text-slate-700
                       hover:bg-slate-50 transition-all"
            >
                Cancelar
            </a>

            <button
                type="submit"
                class="inline-flex items-center justify-center gap-2
                       px-6 py-2.5 rounded-xl
                       bg-emerald-600 hover:bg-emerald-700
                       text-white text-sm font-bold
                       shadow-sm hover:shadow-md transition-all"
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
                        d="M5 13l4 4L19 7"
                    />
                </svg>

                Guardar presupuesto

            </button>

        </div>

    </form>

</div>


{{-- Alpine.js --}}
<script>

    function formularioPresupuesto(listaPacientes) {

        return {

            pacientes: listaPacientes || [],

            busquedaPaciente: '',

            busquedaAbierta: false,

            pacienteSeleccionado: null,

            items: [
                {
                    nombre_tratamiento: '',
                    especialidad: 'Odontología General',
                    cantidad: 1,
                    precio_unitario: 0
                }
            ],


            get pacientesFiltrados() {

                if (!this.busquedaPaciente || this.pacienteSeleccionado) {
                    return this.pacientes;
                }

                const termino = this.busquedaPaciente
                    .toLowerCase()
                    .trim();

                return this.pacientes.filter(p => {

                    const nombre = (p.nombre || '').toLowerCase();
                    const rut = (p.rut || '').toLowerCase();

                    return nombre.includes(termino) ||
                           rut.includes(termino);
                });
            },


            seleccionarPaciente(paciente) {

                this.pacienteSeleccionado = paciente;

                const nombre = paciente.nombre || '';
                const rut = paciente.rut || 'N/A';

                this.busquedaPaciente =
                    `${nombre} (RUT: ${rut})`;

                this.busquedaAbierta = false;
            },


            limpiarPaciente() {

                this.pacienteSeleccionado = null;

                this.busquedaPaciente = '';

                this.busquedaAbierta = true;
            },


            agregarItem() {

                this.items.push({

                    nombre_tratamiento: '',
                    especialidad: 'Odontología General',
                    cantidad: 1,
                    precio_unitario: 0

                });
            },


            removerItem(index) {

                if (this.items.length > 1) {
                    this.items.splice(index, 1);
                }
            },


            calcularTotalGeneral() {

                return this.items.reduce((total, item) => {

                    return total +
                        ((item.cantidad || 0) *
                         (item.precio_unitario || 0));

                }, 0);
            },


            formatearMoneda(valor) {

                return new Intl.NumberFormat('es-CL')
                    .format(valor || 0);
            }

        };
    }

</script>

@endsection
