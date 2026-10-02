<x-app-layout>
    <div
        x-data="liquidacionManager()"
        class="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8"
    >
        <!-- Encabezado -->
        <div
            class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between"
        >
            <div>
                <h1 class="text-2xl font-bold text-slate-800">
                    Liquidación de Honorarios y Comisiones
                </h1>
                <p class="text-sm text-slate-500">Generación de liquidaciones formales e historial de pagos de profesionales.</p>
            </div>
        </div>

        <!-- Alertas de Sesión -->
        @if (session('success'))
            <div
                class="mb-4 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800"
            >
                {{ session('success') }}
            </div>
        @endif

        <!-- Formulario para Generar Nueva Liquidación -->
        <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-bold text-slate-800">
                Generar Nueva Liquidación
            </h2>

            <form
                action="{{ route('financiero.comisiones.liquidaciones.store') }}"
                method="POST"
                id="formLiquidacion"
            >
                @csrf
                <div class="mb-4 grid grid-cols-1 gap-4 md:grid-cols-3">
                    <!-- Odontólogo -->
                    <div>
                        <label
                            class="mb-1 block text-xs font-semibold text-slate-600"
                            >Odontólogo</label
                        >
                        <select
                            name="dentista_id"
                            x-model="dentista_id"
                            @change="
                                calcularRecaudacion();
                                actualizarNombreDoctor($event);
                            "
                            required
                            class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="">Seleccionar doctor...</option>
                            @foreach ($dentistas as $dentista)
                                <option
                                    value="{{ $dentista->usuario_id ?? $dentista->usuario?->id ?? $dentista->id }}"
                                    data-nombre="{{ $dentista->usuario->name ?? $dentista->name }}"
                                >
                                    {{ $dentista->usuario->name ?? $dentista->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Fecha Inicio -->
                    <div>
                        <label
                            class="mb-1 block text-xs font-semibold text-slate-600"
                            >Fecha Inicio</label
                        >
                        <input
                            type="date"
                            name="periodo_inicio"
                            x-model="periodo_inicio"
                            @change="calcularRecaudacion()"
                            required
                            class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                        />
                    </div>

                    <!-- Fecha Fin -->
                    <div>
                        <label
                            class="mb-1 block text-xs font-semibold text-slate-600"
                            >Fecha Fin</label
                        >
                        <input
                            type="date"
                            name="periodo_fin"
                            x-model="periodo_fin"
                            @change="calcularRecaudacion()"
                            required
                            class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"
                        />
                    </div>
                </div>

                <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
                    <!-- Total Recaudado -->
                    <div>
                        <label
                            class="mb-1 block text-xs font-semibold text-slate-600"
                            >Total Recaudado ($)</label
                        >
                        <input
                            type="number"
                            step="0.01"
                            name="total_recaudado"
                            x-model="total_recaudado"
                            readonly
                            class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm font-semibold text-slate-600"
                        />
                    </div>

                    <!-- Porcentaje Tasa -->
                    <div>
                        <label
                            class="mb-1 block text-xs font-semibold text-slate-600"
                            >Tasa Comisión Aplicada (%)</label
                        >
                        <input
                            type="text"
                            x-model="porcentaje_tasa + '%'"
                            readonly
                            class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm font-semibold text-slate-600"
                        />
                    </div>

                    <!-- Monto Comisión -->
                    <div>
                        <label
                            class="mb-1 block text-xs font-semibold text-slate-600"
                            >Monto Comisión / Honorario ($)</label
                        >
                        <input
                            type="number"
                            step="0.01"
                            name="total_comision"
                            x-model="total_comision"
                            readonly
                            class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm font-bold text-emerald-600"
                        />
                    </div>
                </div>

                <!-- Botón Vista Previa -->
                <div class="flex justify-end gap-3">
                    <button
                        type="button"
                        @click="abrirModalNuevaLiquidacion()"
                        :disabled="!formularioValido"
                        class="flex items-center gap-2 rounded-xl bg-slate-800 px-5 py-2.5 text-sm font-medium text-white hover:bg-slate-700 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Vista Previa y Emitir Liquidación
                    </button>
                </div>
            </form>
        </div>

        <!-- Historial de Liquidaciones -->
        <div
            class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm"
        >
            <div class="border-b border-slate-100 p-6">
                <h2 class="text-lg font-bold text-slate-800">
                    Historial de Liquidaciones
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr
                            class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500"
                        >
                            <th class="px-4 py-3.5">Odontólogo</th>
                            <th class="px-4 py-3.5">Período</th>
                            <th class="px-4 py-3.5">Total Recaudado</th>
                            <th class="px-4 py-3.5">Comisión / Honorario</th>
                            <th class="px-4 py-3.5">Estado</th>
                            <th class="px-4 py-3.5 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse ($liquidaciones as $liq)
                            <tr class="hover:bg-slate-50/50">
                                <td
                                    class="px-4 py-3.5 font-medium text-slate-800"
                                >
                                    {{ $liq->dentista->name ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-3.5 text-slate-600">
                                    {{ \Carbon\Carbon::parse($liq->periodo_inicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($liq->periodo_fin)->format('d/m/Y') }}
                                </td>
                                <td
                                    class="px-4 py-3.5 font-semibold text-slate-700"
                                >
                                    ${{ number_format($liq->total_recaudado, 0, ',', '.') }}
                                </td>
                                <td
                                    class="px-4 py-3.5 font-bold text-emerald-600"
                                >
                                    ${{ number_format($liq->total_comision, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3.5">
                                    @if ($liq->estado === 'pagado')
                                        <span
                                            class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800"
                                        >
                                            Pagado ({{ \Carbon\Carbon::parse($liq->fecha_pago)->format('d/m/Y') }})
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-800"
                                        >
                                            Pendiente
                                        </span>
                                    @endif
                                </td>
                                <td class="space-x-2 px-4 py-3.5 text-right">
                                    <!-- Botón Ver/Imprimir Boleta del Historial -->
                                    <button
                                        type="button"
                                        @click="verBoletaHistorial({
                                                id: '{{ $liq->id }}',
                                                doctor: '{{ $liq->dentista->name ?? 'N/A' }}',
                                                inicio: '{{ $liq->periodo_inicio }}',
                                                fin: '{{ $liq->periodo_fin }}',
                                                recaudado: {{ $liq->total_recaudado }},
                                                comision: {{ $liq->total_comision }},
                                                estado: '{{ $liq->estado }}',
                                                fecha_emision: '{{ \Carbon\Carbon::parse($liq->created_at)->format('d/m/Y') }}'
                                            })"
                                        class="inline-flex items-center gap-1 rounded-lg bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-200"
                                    >
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                                        Ver / Imprimir
                                    </button>

                                    <!-- Botón Marcar como Pagado -->
                                    @if ($liq->estado !== 'pagado')
                                        <form
                                            action="{{ route('financiero.comisiones.liquidaciones.pagar', $liq->id) }}"
                                            method="POST"
                                            class="inline"
                                        >
                                            @csrf
                                            @method ('PATCH')
                                            <button
                                                type="submit"
                                                class="rounded-lg bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600 hover:bg-emerald-100"
                                            >
                                                Marcar Pagado
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="6"
                                    class="py-6 text-center text-slate-400"
                                >
                                    No hay liquidaciones registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- MODAL TIPO HOJA CARTA FORMAL -->
        <!-- ========================================== -->
        <div
            x-show="modalAbierto"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-slate-900/70 p-4 backdrop-blur-sm md:p-6"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <!-- Contenedor Principal en Proporción Carta Vertical -->
            <div
                class="relative my-8 w-full max-w-xl"
                @click.away="modalAbierto = false"
            >
                <!-- HOJA CARTA FORMAL -->
                <div
                    id="boletaPrint"
                    class="space-y-6 rounded-lg border border-slate-200 bg-white p-8 text-slate-800 shadow-2xl md:p-10 print:border-none print:p-0 print:shadow-none"
                >
                    <!-- Encabezado de la Hoja -->
                    <div
                        class="flex items-start justify-between border-b-2 border-slate-800 pb-5"
                    >
                        <div>
                            <h2
                                class="text-2xl font-black tracking-tight text-slate-900"
                            >
                                Clínica Venedental
                            </h2>
                            <p class="text-xs font-bold uppercase tracking-widest text-blue-600">Molaris Software</p>
                            <div
                                class="mt-2 space-y-0.5 text-[11px] text-slate-500"
                            >
                                <p>RUT: 77.432.890-K</p>
                                <p>Av. Principal #1234, Oficina 502</p>
                                <p>Contacto: contacto@molaris.cl | +56 9 1234 5678</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span
                                class="inline-block rounded bg-slate-900 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-white"
                            >
                                Liquidación de Honorarios
                            </span>
                            <div
                                class="mt-3 space-y-0.5 text-[11px] text-slate-600"
                            >
                                <p><strong>N° Folio:</strong> <span class="font-mono text-slate-900" x-text="boletaActive.folio"></span></p>
                                <p><strong>Fecha Emisión:</strong> <span x-text="boletaActive.fecha_emision"></span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Título Interno -->
                    <div class="py-1 text-center">
                        <h3
                            class="text-xs font-bold uppercase tracking-widest text-slate-700"
                        >
                            Comprobante de Liquidación y Pago de Honorarios
                        </h3>
                    </div>

                    <!-- Datos del Profesional y Período -->
                    <div
                        class="grid grid-cols-2 gap-4 rounded-lg border border-slate-200 bg-slate-50 p-3.5 text-[11px]"
                    >
                        <div>
                            <p class="text-[9px] font-bold uppercase text-slate-400">Profesional Prestador</p>
                            <p class="mt-0.5 text-xs font-bold text-slate-900" x-text="
                                    boletaActive.doctor
                                "></p>
                            <p class="text-slate-500">Especialidad: Odontología General / Clínica</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold uppercase text-slate-400">Período de Liquidación</p>
                            <p class="mt-0.5 text-xs font-bold text-slate-900" x-text="
                                    formatearFecha(boletaActive.inicio) +
                                    ' al ' +
                                    formatearFecha(boletaActive.fin)
                                "></p>
                            <p class="text-slate-500">Estado: <span class="font-bold uppercase text-slate-800" x-text="boletaActive.estado"></span></p>
                        </div>
                    </div>

                    <!-- Detalle de Conceptos de la Hoja -->
                    <div
                        class="overflow-hidden rounded-lg border border-slate-200"
                    >
                        <table class="w-full text-left text-[11px]">
                            <thead
                                class="border-b border-slate-200 bg-slate-100 font-bold text-slate-700"
                            >
                                <tr>
                                    <th class="p-2.5">Detalle / Concepto</th>
                                    <th class="p-2.5 text-center">Tasa / %</th>
                                    <th class="p-2.5 text-right">Monto ($)</th>
                                </tr>
                            </thead>
                            <tbody
                                class="divide-y divide-slate-100 text-slate-700"
                            >
                                <tr>
                                    <td class="p-2.5 font-medium">
                                        Total Recaudado por Presupuestos
                                        Atendidos
                                    </td>
                                    <td
                                        class="p-2.5 text-center text-slate-400"
                                    >
                                        -
                                    </td>
                                    <td
                                        class="p-2.5 text-right font-semibold"
                                        x-text="
                                            '$' +
                                            formatearMonto(
                                                boletaActive.recaudado
                                            )
                                        "
                                    ></td>
                                </tr>
                                <tr>
                                    <td class="p-2.5 font-medium">
                                        Comisión Pactada según Contrato
                                    </td>
                                    <td
                                        class="p-2.5 text-center font-bold text-blue-600"
                                        x-text="boletaActive.porcentaje + '%'"
                                    ></td>
                                    <td
                                        class="p-2.5 text-right font-bold text-emerald-600"
                                        x-text="
                                            '$' +
                                            formatearMonto(
                                                boletaActive.comision
                                            )
                                        "
                                    ></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Cuadro Total Líquido -->
                    <div class="flex justify-end pt-2">
                        <div
                            class="w-3/5 rounded-lg bg-slate-900 p-3.5 text-right text-white"
                        >
                            <p class="text-[10px] font-bold uppercase tracking-wider text-black">Total Líquido a Pagar</p>
                            <p class="mt-0.5 text-xl font-black text-emerald-600" x-text="
                                    '$' + formatearMonto(boletaActive.comision)
                                "></p>
                        </div>
                    </div>

                    <!-- Firmas Pie de Página -->
                    <div
                        class="grid grid-cols-2 gap-10 pt-12 text-center text-[10px] text-slate-500"
                    >
                        <div class="border-t border-slate-400 pt-1.5">
                            <p class="font-bold text-slate-800">Clínica Venedental</p>
                            <p>Firma / Timbre Administración</p>
                        </div>
                        <div class="border-t border-slate-400 pt-1.5">
                            <p class="font-bold text-slate-800" x-text="
                                    boletaActive.doctor
                                "></p>
                            <p>Firma Conforme Profesional</p>
                        </div>
                    </div>
                </div>

                <!-- Botones Flotantes Fuera de la Hoja Imprimible -->
                <div class="mt-4 flex justify-end gap-3 print:hidden">
                    <button
                        type="button"
                        @click="modalAbierto = false"
                        class="rounded-xl bg-emerald-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-emerald-700"
                    >
                        Cerrar
                    </button>

                    <button
                        type="button"
                        @click="imprimirBoleta()"
                        class="flex items-center gap-2 rounded-xl bg-molaris-dark px-4 py-2 text-xs font-semibold text-white shadow-lg transition hover:bg-blue-700"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                        Imprimir / Guardar PDF
                    </button>

                    <template x-if="esNuevaLiquidacion">
                        <button
                            type="button"
                            @click="confirmarYGuardar()"
                            class="flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white shadow-lg transition hover:bg-emerald-700"
                        >
                            Confirmar y Emitir
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- Script de AlpineJS -->
    <script>
        function liquidacionManager() {
            return {
                dentista_id: '',
                nombreDoctor: 'Doctor Seleccionado',
                periodo_inicio: '',
                periodo_fin: '',
                total_recaudado: 0,
                porcentaje_tasa: 0,
                total_comision: 0,
                modalAbierto: false,
                esNuevaLiquidacion: true,

                boletaActive: {
                    folio: '',
                    doctor: '',
                    inicio: '',
                    fin: '',
                    recaudado: 0,
                    porcentaje: 0,
                    comision: 0,
                    estado: 'pendiente',
                    fecha_emision: '{{ date('d/m/Y') }}',
                },

                get formularioValido() {
                    return (
                        this.dentista_id &&
                        this.periodo_inicio &&
                        this.periodo_fin &&
                        this.total_recaudado >= 0
                    );
                },

                actualizarNombreDoctor(event) {
                    const selectedOption =
                        event.target.options[event.target.selectedIndex];
                    this.nombreDoctor =
                        selectedOption.getAttribute('data-nombre') ||
                        'Doctor Seleccionado';
                },

                async calcularRecaudacion() {
                    if (!this.dentista_id || !this.periodo_inicio || !this.periodo_fin)
                        return;

                    try {
                        const url = `{{ route('financiero.comisiones.calcular-recaudacion') }}?dentista_id=${this.dentista_id}&periodo_inicio=${this.periodo_inicio}&periodo_fin=${this.periodo_fin}`;
                        const response = await fetch(url);
                        const data = await response.json();

                        this.total_recaudado = data.total_recaudado || 0;
                        this.porcentaje_tasa = data.porcentaje || 0;
                        this.total_comision = data.total_comision || 0;
                    } catch (error) {
                        console.error('Error al obtener recaudación:', error);
                    }
                },

                abrirModalNuevaLiquidacion() {
                    if (this.formularioValido) {
                        this.esNuevaLiquidacion = true;
                        this.boletaActive = {
                            folio: 'LIQ-{{ date('Ym') }}-NUEVA',
                            doctor: this.nombreDoctor,
                            inicio: this.periodo_inicio,
                            fin: this.periodo_fin,
                            recaudado: this.total_recaudado,
                            porcentaje: this.porcentaje_tasa,
                            comision: this.total_comision,
                            estado: 'Pendiente',
                            fecha_emision: '{{ date('d/m/Y') }}',
                        };
                        this.modalAbierto = true;
                    }
                },

                verBoletaHistorial(data) {
                    this.esNuevaLiquidacion = false;
                    let porcentajeCalculado =
                        data.recaudado > 0
                            ? Math.round((data.comision / data.recaudado) * 100)
                            : 0;

                    this.boletaActive = {
                        folio: 'LIQ-' + String(data.id).padStart(5, '0'),
                        doctor: data.doctor,
                        inicio: data.inicio,
                        fin: data.fin,
                        recaudado: data.recaudado,
                        porcentaje: porcentajeCalculado,
                        comision: data.comision,
                        estado: data.estado,
                        fecha_emision: data.fecha_emision,
                    };
                    this.modalAbierto = true;
                },

                confirmarYGuardar() {
                    document.getElementById('formLiquidacion').submit();
                },

                formatearMonto(monto) {
                    return new Intl.NumberFormat('es-CL').format(monto || 0);
                },

                formatearFecha(fechaStr) {
                    if (!fechaStr) return '';

                    // 1. Remueve la hora si viene en formato ISO ('T') o con espacio (' ')
                    const soloFecha = fechaStr.split('T')[0].split(' ')[0];

                    // 2. Separa por guiones (YYYY-MM-DD)
                    const partes = soloFecha.split('-');

                    if (partes.length === 3) {
                        // Devuelve formato DD/MM/YYYY
                        return `${partes[2]}/${partes[1]}/${partes[0]}`;
                    }

                    return soloFecha;
                },

                imprimirBoleta() {
                    window.print();
                },
            };
        }
    </script>
</x-app-layout>
