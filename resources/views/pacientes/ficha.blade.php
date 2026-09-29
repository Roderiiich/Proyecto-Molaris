<x-app-layout>
    <x-slot name="header">
        <div
            class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
        >
            <div class="flex items-center gap-3">
                <a
                    href="{{ route('pacientes.index') }}"
                    class="rounded-xl border border-slate-200 bg-white p-2 text-slate-600 shadow-sm transition hover:bg-slate-100 hover:text-slate-900"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h2
                        class="text-2xl font-bold tracking-tight text-slate-900"
                    >
                        Ficha Clínica del Paciente
                    </h2>
                    <p class="text-xs font-semibold text-slate-500">Historia clínica digital y registro de atenciones</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a
                    href="{{ route('pacientes.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-50"
                >
                    Volver a Pacientes
                </a>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-100/60 py-8">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            {{-- ALERTAS DE ÉXITO O ERROR --}}
            @if (session('success'))
                <div
                    class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-xs font-bold text-emerald-800 shadow-sm"
                >
                    <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div
                    class="rounded-2xl border border-red-200 bg-red-50 p-4 text-xs font-bold text-red-800 shadow-sm"
                >
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- TARJETA DE RESUMEN DEL PACIENTE --}}
            <div
                class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm"
            >
                <div
                    class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between"
                >
                    {{-- DATOS DEL PACIENTE --}}
                    <div class="flex items-center gap-4">
                        <div>
                            <h3 class="text-xl font-bold text-slate-900">
                                {{ $paciente->nombre }} {{ $paciente->apellido }}
                            </h3>
                            <div
                                class="mt-1 flex flex-wrap items-center gap-3 text-xs font-semibold text-slate-500"
                            >
                                <span
                                    class="rounded-md border border-slate-200 bg-slate-50 px-2 py-0.5 font-mono text-slate-700"
                                >
                                    RUT: {{ $paciente->rut ?? 'Sin RUT' }}
                                </span>
                                <span
                                    >📞 {{ $paciente->telefono ?? 'Sin teléfono' }}</span
                                >
                                <span
                                    >✉️ {{ $paciente->correo ?? 'Sin email' }}</span
                                >
                            </div>
                        </div>
                    </div>

                    {{-- FORMULARIO DE ALERTAS MÉDICAS --}}
                    <div class="flex items-center gap-3">
                        <form
                            action="{{ route('pacientes.alertas', $paciente->id) }}"
                            method="POST"
                            class="flex flex-col items-center gap-2 sm:flex-row"
                        >
                            @csrf
                            @method ('PUT')

                            <input
                                type="text"
                                name="alergias"
                                value="{{ old('alergias', $paciente->alergias) }}"
                                placeholder="Alergias (Ej: Penicilina)"
                                class="w-full rounded-xl border-slate-200 text-xs shadow-sm focus:border-red-500 focus:ring-red-500 sm:w-48"
                            />

                            <input
                                type="text"
                                name="enfermedades_cronicas"
                                value="{{ old('enfermedades_cronicas', $paciente->enfermedades_cronicas) }}"
                                placeholder="Enf. Crónicas (Ej: Asma)"
                                class="w-full rounded-xl border-slate-200 text-xs shadow-sm focus:border-red-500 focus:ring-red-500 sm:w-48"
                            />

                            <button
                                type="submit"
                                class="w-full shrink-0 cursor-pointer rounded-xl bg-red-600 px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-red-700 sm:w-auto"
                            >
                                Guardar Alertas
                            </button>
                        </form>
                    </div>
                </div>

                {{-- VISUALIZACIÓN DE ALERTAS EN LA TARJETA --}}
                @if ($paciente->alergias || $paciente->enfermedades_cronicas)
                    <div
                        class="mt-4 flex flex-col gap-2 rounded-xl border border-red-200 bg-red-50/80 p-3 text-xs text-red-800 sm:flex-row sm:items-center sm:gap-6"
                    >
                        <div
                            class="flex shrink-0 items-center gap-1.5 font-bold text-red-900"
                        >
                            <span class="text-base">⚠️</span>
                            <span>ALERTAS MÉDICAS:</span>
                        </div>

                        <div class="flex flex-wrap items-center gap-4 text-xs">
                            @if ($paciente->alergias)
                                <p><strong>Alergias:</strong> {{ $paciente->alergias }}</p>
                            @endif

                            @if ($paciente->enfermedades_cronicas)
                                <p><strong>Enf. Crónicas:</strong> {{ $paciente->enfermedades_cronicas }}</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
            @php
    $cuadrante1 = [18, 17, 16, 15, 14, 13, 12, 11];$cuadrante2 = [21, 22, 23, 24, 25, 26, 27, 28];
    $cuadrante4 = [48, 47, 46, 45, 44, 43, 42, 41];$cuadrante3 = [31, 32, 33, 34, 35, 36, 37, 38];
@endphp

            <div
                class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm"
            >
                <div
                    class="mb-6 flex flex-col items-start justify-between gap-4 border-b border-slate-100 pb-3 sm:flex-row sm:items-center"
                >
                    <div class="flex items-center gap-3">
                        <div class="rounded-xl bg-cyan-50 p-2.5 text-cyan-700">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L5.6 15.12a2 2 0 00-1.18.117l-.112.056a2 2 0 00-1.026 2.215l.488 2.44a2 2 0 001.96 1.61h14.54a2 2 0 001.96-1.61l.488-2.44a2 2 0 00-.272-1.48zM12 3v9" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-base font-bold text-slate-800">
                                Odontograma del Paciente
                            </h4>
                            <p class="text-xs text-slate-500">Selecciona el estado de cada pieza dental según la nomenclatura FDI</p>
                        </div>
                    </div>

                    <!-- Leyenda de Estados Interactivas -->
                    <div
                        class="flex flex-wrap items-center gap-3 rounded-xl border border-slate-200/80 bg-slate-50 p-2.5 text-xs font-semibold"
                    >
                        <span class="text-slate-400">Estado a aplicar:</span>
                        <button
                            type="button"
                            onclick="setEstadoActivo('sano')"
                            id="btn-estado-sano"
                            class="flex items-center gap-1.5 rounded-lg border border-emerald-500 bg-emerald-50 px-2 py-1 text-emerald-800 transition"
                        >
                            <span
                                class="inline-block h-3 w-3 rounded-full bg-emerald-500"
                            ></span>
                            Sano
                        </button>
                        <button
                            type="button"
                            onclick="setEstadoActivo('caries')"
                            id="btn-estado-caries"
                            class="flex items-center gap-1.5 rounded-lg border border-transparent px-2 py-1 text-slate-600 transition hover:bg-slate-200"
                        >
                            <span
                                class="inline-block h-3 w-3 rounded-full bg-rose-500"
                            ></span>
                            Caries
                        </button>
                        <button
                            type="button"
                            onclick="setEstadoActivo('tratado')"
                            id="btn-estado-tratado"
                            class="flex items-center gap-1.5 rounded-lg border border-transparent px-2 py-1 text-slate-600 transition hover:bg-slate-200"
                        >
                            <span
                                class="inline-block h-3 w-3 rounded-full bg-cyan-600"
                            ></span>
                            Tratado
                        </button>
                        <button
                            type="button"
                            onclick="setEstadoActivo('ausente')"
                            id="btn-estado-ausente"
                            class="flex items-center gap-1.5 rounded-lg border border-transparent px-2 py-1 text-slate-600 transition hover:bg-slate-200"
                        >
                            <span
                                class="inline-block h-3 w-3 rounded-full bg-slate-800"
                            ></span>
                            Ausente
                        </button>
                    </div>
                </div>

                <!-- Arcadas Dentales -->
                <div class="space-y-6 overflow-x-auto pb-2">
                    <!-- Arcada Superior -->
                    <div>
                        <span
                            class="mb-2 block text-center text-xs font-bold uppercase tracking-wider text-slate-400"
                        >
                            Arcada Superior (Maxilar)
                        </span>
                        <div class="flex min-w-[650px] justify-center gap-1.5">
                            <div
                                class="flex gap-1 border-r-2 border-slate-300 pr-2"
                            >
                                @foreach ($cuadrante1 as $pieza)
                                    <button
                                        type="button"
                                        onclick="cambiarEstadoPieza({{ $pieza }})"
                                        id="pieza-btn-{{ $pieza }}"
                                        class="flex h-14 w-10 flex-col items-center justify-between rounded-xl border border-slate-200 bg-slate-50 p-1.5 shadow-sm transition hover:bg-slate-100"
                                    >
                                        <span
                                            class="font-mono text-[10px] font-bold text-slate-500"
                                            >{{ $pieza }}</span
                                        >
                                        <span
                                            id="pieza-icon-{{ $pieza }}"
                                            data-estado="sano"
                                            class="h-5 w-5 rounded-full border-2 border-white bg-emerald-500 shadow-sm transition"
                                        ></span>
                                    </button>
                                @endforeach
                            </div>
                            <div class="flex gap-1 pl-2">
                                @foreach ($cuadrante2 as $pieza)
                                    <button
                                        type="button"
                                        onclick="cambiarEstadoPieza({{ $pieza }})"
                                        id="pieza-btn-{{ $pieza }}"
                                        class="flex h-14 w-10 flex-col items-center justify-between rounded-xl border border-slate-200 bg-slate-50 p-1.5 shadow-sm transition hover:bg-slate-100"
                                    >
                                        <span
                                            class="font-mono text-[10px] font-bold text-slate-500"
                                            >{{ $pieza }}</span
                                        >
                                        <span
                                            id="pieza-icon-{{ $pieza }}"
                                            data-estado="sano"
                                            class="h-5 w-5 rounded-full border-2 border-white bg-emerald-500 shadow-sm transition"
                                        ></span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <hr class="my-2 border-slate-100" />

                    <!-- Arcada Inferior -->
                    <div>
                        <span
                            class="mb-2 block text-center text-xs font-bold uppercase tracking-wider text-slate-400"
                        >
                            Arcada Inferior (Mandíbula)
                        </span>
                        <div class="flex min-w-[650px] justify-center gap-1.5">
                            <div
                                class="flex gap-1 border-r-2 border-slate-300 pr-2"
                            >
                                @foreach ($cuadrante4 as $pieza)
                                    <button
                                        type="button"
                                        onclick="cambiarEstadoPieza({{ $pieza }})"
                                        id="pieza-btn-{{ $pieza }}"
                                        class="flex h-14 w-10 flex-col items-center justify-between rounded-xl border border-slate-200 bg-slate-50 p-1.5 shadow-sm transition hover:bg-slate-100"
                                    >
                                        <span
                                            id="pieza-icon-{{ $pieza }}"
                                            data-estado="sano"
                                            class="h-5 w-5 rounded-full border-2 border-white bg-emerald-500 shadow-sm transition"
                                        ></span>
                                        <span
                                            class="font-mono text-[10px] font-bold text-slate-500"
                                            >{{ $pieza }}</span
                                        >
                                    </button>
                                @endforeach
                            </div>
                            <div class="flex gap-1 pl-2">
                                @foreach ($cuadrante3 as $pieza)
                                    <button
                                        type="button"
                                        onclick="cambiarEstadoPieza({{ $pieza }})"
                                        id="pieza-btn-{{ $pieza }}"
                                        class="flex h-14 w-10 flex-col items-center justify-between rounded-xl border border-slate-200 bg-slate-50 p-1.5 shadow-sm transition hover:bg-slate-100"
                                    >
                                        <span
                                            id="pieza-icon-{{ $pieza }}"
                                            data-estado="sano"
                                            class="h-5 w-5 rounded-full border-2 border-white bg-emerald-500 shadow-sm transition"
                                        ></span>
                                        <span
                                            class="font-mono text-[10px] font-bold text-slate-500"
                                            >{{ $pieza }}</span
                                        >
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @push ('scripts')
                <script>
                    const pacienteId = @json ($paciente->id ?? $ficha->paciente_id ?? null);
                    const csrfToken = @json (csrf_token());

                    let estadoSeleccionado = 'sano';

                    const mapaClases = {
                        sano: 'bg-emerald-500',
                        caries: 'bg-rose-500',
                        tratado: 'bg-cyan-600',
                        ausente: 'bg-slate-800',
                    };

                    const todasLasClases = [
                        'bg-emerald-500',
                        'bg-rose-500',
                        'bg-cyan-600',
                        'bg-slate-800',
                    ];

                    document.addEventListener('DOMContentLoaded', () => {
                        if (pacienteId) {
                            cargarOdontograma();
                        }
                    });

                    function setEstadoActivo(nuevoEstado) {
                        estadoSeleccionado = nuevoEstado;
                        ['sano', 'caries', 'tratado', 'ausente'].forEach((est) => {
                            const btn = document.getElementById(`btn-estado-${est}`);
                            if (btn) {
                                if (est === nuevoEstado) {
                                    btn.classList.add(
                                        'border-emerald-500',
                                        'bg-emerald-50',
                                        'text-emerald-800'
                                    );
                                    btn.classList.remove(
                                        'border-transparent',
                                        'hover:bg-slate-200',
                                        'text-slate-600'
                                    );
                                } else {
                                    btn.classList.remove(
                                        'border-emerald-500',
                                        'bg-emerald-50',
                                        'text-emerald-800'
                                    );
                                    btn.classList.add(
                                        'border-transparent',
                                        'hover:bg-slate-200',
                                        'text-slate-600'
                                    );
                                }
                            }
                        });
                    }

                    function cargarOdontograma() {
                        fetch(`/pacientes/${pacienteId}/odontograma/estados`)
                            .then((res) => res.json())
                            .then((data) => {
                                data.forEach((item) => {
                                    aplicarEstadoEnDOM(item.numero_diente, item.estado);
                                });
                            })
                            .catch((err) => console.error('Error al cargar el odontograma:', err));
                    }

                    function cambiarEstadoPieza(pieza) {
                        const icon = document.getElementById(`pieza-icon-${pieza}`);
                        if (!icon) return;

                        let estadoAAplicar = estadoSeleccionado;
                        const estadoActual = icon.getAttribute('data-estado') || 'sano';

                        if (estadoActual === estadoSeleccionado) {
                            const secuencia = ['sano', 'caries', 'tratado', 'ausente'];
                            const idx = secuencia.indexOf(estadoActual);
                            estadoAAplicar = secuencia[(idx + 1) % secuencia.length];
                        }

                        aplicarEstadoEnDOM(pieza, estadoAAplicar);

                        if (pacienteId) {
                            fetch(`/pacientes/${pacienteId}/odontograma/guardar`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                },
                                body: JSON.stringify({
                                    numero_diente: pieza,
                                    cara: 'general',
                                    estado: estadoAAplicar,
                                }),
                            })
                                .then((res) => res.json())
                                .catch((err) => console.error('Error al guardar diente:', err));
                        }
                    }

                    function aplicarEstadoEnDOM(pieza, estado) {
                        const icon = document.getElementById(`pieza-icon-${pieza}`);
                        if (icon) {
                            icon.classList.remove(...todasLasClases);
                            const nuevaClase = mapaClases[estado] || 'bg-emerald-500';
                            icon.classList.add(nuevaClase);
                            icon.setAttribute('data-estado', estado);
                        }
                    }
                </script>
            @endpush

            {{-- CONTENIDO PRINCIPAL: FORMULARIO Y HISTORIAL --}}
            <div class="flex flex-col gap-6 md:flex-row">
                {{-- FORMULARIO DE REGISTRO DE NUEVA ATENCIÓN --}}
                <div
                    class="w-full rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm md:w-5/12"
                >
                    <div class="mb-4 border-b border-slate-100 pb-3">
                        <h4
                            class="flex items-center gap-2 text-base font-bold text-slate-800"
                        >
                            <span>📝</span> Registrar Nueva Atención
                        </h4>
                        <p class="text-xs font-medium text-slate-500">Ingrese la información detallada de la consulta.</p>
                    </div>

                    <form
                        action="{{ route('fichas.store', $paciente->id) }}"
                        method="POST"
                        enctype="multipart/form-data"
                        class="space-y-4"
                    >
                        @csrf

                        <div>
                            <label
                                class="mb-1 block text-xs font-bold text-slate-700"
                                >Motivo de la Consulta *</label
                            >
                            <input
                                type="text"
                                name="motivo_consulta"
                                required
                                value="{{ old('motivo_consulta') }}"
                                placeholder="Ej: Dolor muela del juicio, Limpieza..."
                                class="w-full rounded-xl border-slate-200 text-xs shadow-sm focus:border-cyan-500 focus:ring-cyan-500"
                            />
                        </div>

                        <div>
                            <label
                                class="mb-1 block text-xs font-bold text-slate-700"
                                >Diagnóstico *</label
                            >
                            <textarea
                                name="diagnostico"
                                rows="3"
                                required
                                placeholder="Descripción del diagnóstico clínico..."
                                class="w-full rounded-xl border-slate-200 text-xs shadow-sm focus:border-cyan-500 focus:ring-cyan-500"
                                >{{ old('diagnostico') }}</textarea
                            >
                        </div>

                        <div>
                            <label
                                class="mb-1 block text-xs font-bold text-slate-700"
                                >Tratamiento Aplicado</label
                            >
                            <textarea
                                name="tratamiento"
                                rows="3"
                                placeholder="Procedimientos realizados..."
                                class="w-full rounded-xl border-slate-200 text-xs shadow-sm focus:border-cyan-500 focus:ring-cyan-500"
                                >{{ old('tratamiento') }}</textarea
                            >
                        </div>

                        <div>
                            <label
                                class="mb-1 block text-xs font-bold text-slate-700"
                                >Observaciones y Indicaciones Médicas</label
                            >
                            <textarea
                                name="observaciones"
                                rows="2"
                                placeholder="Indicaciones para el paciente, medicamentos..."
                                class="w-full rounded-xl border-slate-200 text-xs shadow-sm focus:border-cyan-500 focus:ring-cyan-500"
                                >{{ old('observaciones') }}</textarea
                            >
                        </div>

                        <div>
                            <label
                                class="mb-1 block text-xs font-bold text-slate-700"
                                >Adjuntar Archivos / Radiografías</label
                            >
                            <input
                                type="file"
                                name="adjuntos[]"
                                multiple
                                class="w-full text-xs text-slate-500 file:mr-4 file:rounded-xl file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-xs file:font-semibold file:text-slate-700 hover:file:bg-slate-200"
                            />
                        </div>

                        <button
                            type="submit"
                            class="flex w-full cursor-pointer items-center justify-center gap-2 rounded-xl px-4 py-3 text-xs font-bold text-white shadow-md transition hover:opacity-90"
                            style="
                                background-color: #0f2d4a !important;
                                color: #ffffff !important;
                            "
                        >
                            <svg
                                class="h-4 w-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                style="color: #ffffff"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Guardar Registro en Ficha
                        </button>
                    </form>
                </div>

                {{-- HISTORIAL DE CONSULTAS Y ATENCIONES --}}
                <div
                    class="w-full rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm md:w-7/12"
                >
                    <div
                        class="mb-4 flex items-center justify-between border-b border-slate-100 pb-3"
                    >
                        <div class="flex items-center gap-2">
                            <div
                                class="rounded-lg bg-slate-100 p-2 text-slate-700"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h4 class="text-base font-bold text-slate-800">
                                Historial de Consultas y Atenciones
                            </h4>
                        </div>
                        <span class="text-xs font-semibold text-slate-400"
                            >Cronológico</span
                        >
                    </div>

                    <div class="max-h-[680px] space-y-4 overflow-y-auto pr-1">
                        @if ($paciente->fichasClinicas && $paciente->fichasClinicas->count() > 0)
                            @foreach ($paciente->fichasClinicas as $ficha)
                                <div
                                    class="space-y-3 rounded-2xl border border-slate-200 bg-slate-50/70 p-4 shadow-sm transition hover:border-cyan-300"
                                >
                                    <div
                                        class="flex items-center justify-between border-b border-slate-200/80 pb-2 text-xs font-semibold"
                                    >
                                        <span
                                            class="flex items-center gap-1.5 font-bold text-cyan-800"
                                        >
                                            🩺 Atención Médica
                                        </span>
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="rounded-md border border-slate-200 bg-white p-2.5 font-mono text-[11px] text-slate-600"
                                            >
                                                {{ $ficha->created_at ? $ficha->created_at->format('d/m/Y ') : 'Sin fecha' }}
                                            </span>

                                            <a
                                                href="{{ route('fichas.receta', $ficha->id) }}"
                                                target="_blank"
                                                title="Descargar Receta PDF"
                                                class="shadow-xs inline-flex items-center gap-1.5 rounded-md border border-slate-700 px-3 py-1.5 text-xs font-bold text-white transition hover:opacity-90"
                                                style="
                                                    background-color: #0f2d4a;
                                                "
                                            >
                                                📄 PDF
                                            </a>

                                            <form
                                                action="{{ route('fichas.destroy', $ficha->id) }}"
                                                method="POST"
                                                onsubmit="
                                                    return confirm(
                                                        '¿Seguro que deseas eliminar esta atención?'
                                                    );
                                                "
                                                class="inline"
                                            >
                                                @csrf
                                                @method ('DELETE')
                                                <button
                                                    type="submit"
                                                    title="Eliminar Atención"
                                                    class="shadow-xs inline-flex shrink-0 cursor-pointer items-center justify-center rounded-lg bg-red-600 p-2 text-white transition-colors duration-200 hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-1"
                                                >
                                                    <!-- Ícono de X blanca sobre fondo rojo -->
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <div
                                        class="space-y-1.5 pt-1 text-xs leading-relaxed text-slate-700"
                                    >
                                        <p><strong class="font-bold text-slate-900">Motivo:</strong> {{ $ficha->motivo_consulta }}</p>
                                        @if ($ficha->diagnostico)
                                            <p><strong class="font-bold text-slate-900">Diagnóstico:</strong> {{ $ficha->diagnostico }}</p>
                                        @endif
                                        @if ($ficha->tratamiento)
                                            <p><strong class="font-bold text-slate-900">Tratamiento:</strong> {{ $ficha->tratamiento }}</p>
                                        @endif
                                        @if ($ficha->observaciones)
                                            <p class="mt-1 rounded-xl border border-slate-200/60 bg-white p-2.5 italic text-slate-600">
                                                <strong
                                                    class="font-bold not-italic text-slate-800"
                                                    >Obs/Indicaciones:</strong
                                                >
                                                {{ $ficha->observaciones }}
                                            </p>
                                        @endif

                                        @if ($ficha->adjuntos && $ficha->adjuntos->count() > 0)
                                            <div
                                                class="mt-2 border-t border-slate-200/60 pt-2"
                                            >
                                                <span
                                                    class="mb-1 block text-[11px] font-bold text-slate-600"
                                                    >Radiografías / Archivos
                                                    Adjuntos:</span
                                                >
                                                <div
                                                    class="flex flex-wrap gap-2"
                                                >
                                                    @foreach ($ficha->adjuntos as $adjunto)
                                                        <a
                                                            href="{{ asset('storage/' . $adjunto->ruta_archivo) }}"
                                                            target="_blank"
                                                            class="flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-[11px] font-medium text-cyan-700 transition hover:bg-cyan-50"
                                                        >
                                                            📎 {{ Str::limit($adjunto->nombre_original, 18) }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div
                                class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 py-12 text-center"
                            >
                                <p class="text-xs font-bold text-slate-500">El paciente no registra atenciones previas.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>