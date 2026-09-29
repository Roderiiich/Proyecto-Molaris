<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Agenda Clínica - Molaris') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <!-- Mensajes de alerta -->
            @if (session('success'))
                <div
                    class="relative rounded-xl border border-green-400 bg-green-100 px-4 py-3 text-green-700 shadow-sm"
                >
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->has('error'))
                <div
                    class="relative rounded-xl border border-red-400 bg-red-100 px-4 py-3 text-red-700 shadow-sm"
                >
                    {{ $errors->first('error') }}
                </div>
            @endif

            <!-- ========================================== -->
            <!-- CARD PRINCIPAL: FORMULARIO + PANEL DERECHO -->
            <!-- ========================================== -->
            <div
                class="overflow-hidden border border-slate-100 bg-white shadow-xl sm:rounded-2xl"
            >
                <!-- Estructura Flexbox garantizada en 2 columnas para pantallas grandes -->
                <div class="flex w-full flex-col items-stretch lg:flex-row">
                    <!-- COLUMNA IZQUIERDA: FORMULARIO (60% DE ANCHO EN DESKTOP) -->
                    <div
                        class="flex w-full flex-col justify-between p-6 sm:p-8 lg:w-[60%]"
                    >
                        <div>
                            <div class="mb-6 flex items-center gap-3">
                                <div
                                    class="rounded-xl bg-indigo-50 p-2.5 text-indigo-600"
                                >
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-slate-800">
                                    Agendar Nueva Cita
                                </h3>
                            </div>

                            <form
                                action="{{ route('citas.store') }}"
                                method="POST"
                                class="space-y-4"
                            >
                                @csrf

                                <!-- 1. Buscador de Paciente -->
                                <div class="relative">
                                    <label
                                        for="buscar_paciente"
                                        class="mb-1 block text-xs font-semibold uppercase tracking-wider text-slate-700"
                                    >
                                        Buscar Paciente (RUT o Nombre)
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <div
                                        id="contenedor_input_buscar"
                                        class="relative"
                                    >
                                        <input
                                            type="text"
                                            id="buscar_paciente"
                                            autocomplete="off"
                                            placeholder="Ej: 15666777-8 o Juan Pérez..."
                                            class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-3 pr-10 text-sm text-slate-800 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                        />

                                        <button
                                            type="button"
                                            onclick="limpiarPaciente()"
                                            class="absolute inset-y-0 right-0 flex items-center pr-3 font-bold text-slate-400 transition hover:text-red-500"
                                        >
                                            ✕
                                        </button>
                                    </div>

                                    <!-- Resultados flotantes -->
                                    <div
                                        id="resultados_paciente"
                                        class="absolute left-0 right-0 z-50 mt-1.5 hidden max-h-64 space-y-1 overflow-y-auto rounded-xl border border-slate-200 bg-white p-2 shadow-2xl"
                                    ></div>

                                    <!-- Input oculto paciente_id -->
                                    <input
                                        type="hidden"
                                        name="paciente_id"
                                        id="paciente_id"
                                        value="{{ old('paciente_id') }}"
                                        required
                                    />

                                    <!-- Confirmación selección -->
                                    <div
                                        id="paciente_seleccionado_info"
                                        class="mt-2 hidden items-center justify-between rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-xs font-medium text-emerald-800 shadow-sm"
                                    >
                                        <div
                                            class="flex items-center space-x-2"
                                        >
                                            <svg class="h-4 w-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span
                                                id="nombre_paciente_seleccionado"
                                                class="text-sm font-semibold text-slate-800"
                                            ></span>
                                        </div>
                                        <button
                                            type="button"
                                            id="btn_quitar_paciente"
                                            onclick="limpiarPaciente()"
                                            class="rounded-md bg-red-100 px-2.5 py-1 text-xs font-bold text-red-600 transition hover:bg-red-200 hover:text-red-800"
                                        >
                                            ✕ Cambiar
                                        </button>
                                    </div>
                                </div>

                                <!-- 2. Odontólogo y Box en 2 columnas -->
                                <div
                                    class="grid grid-cols-1 gap-4 sm:grid-cols-2"
                                >
                                    <div>
                                        <label
                                            for="doctor_id"
                                            class="mb-1 block text-xs font-semibold uppercase tracking-wider text-slate-700"
                                        >
                                            Odontólogo
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <select
                                            name="doctor_id"
                                            id="doctor_id"
                                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                            required
                                        >
                                            <option value="" disabled selected>
                                                Seleccione un odontólogo
                                            </option>
                                            @foreach (\App\Models\Doctor::with('usuario')->get() as $doctor)
                                                <option
                                                    value="{{ $doctor->id }}"
                                                    {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}
                                                >
                                                    Dr. {{ $doctor->usuario->name ?? $doctor->usuario->nombre ?? 'Sin nombre' }} - {{ $doctor->especialidad }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label
                                            for="box_id"
                                            class="mb-1 block text-xs font-semibold uppercase tracking-wider text-slate-700"
                                        >
                                            Box de Atención
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <select
                                            name="box_id"
                                            id="box_id"
                                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                            required
                                        >
                                            <option value="" disabled selected>
                                                Seleccione un box
                                            </option>
                                            @foreach (\App\Models\Box::all() as $box)
                                                <option
                                                    value="{{ $box->id }}"
                                                    {{ old('box_id') == $box->id ? 'selected' : '' }}
                                                >
                                                    {{ $box->nombre }} ({{$box->estado }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- 3. Fecha y Hora en 2 columnas -->
                                <div
                                    class="grid grid-cols-1 gap-4 sm:grid-cols-2"
                                >
                                    <div>
                                        <label
                                            for="fecha"
                                            class="mb-1 block text-xs font-semibold uppercase tracking-wider text-slate-700"
                                        >
                                            Fecha de la Cita
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <input
                                            type="date"
                                            id="fecha"
                                            name="fecha"
                                            value="{{ old('fecha', date('Y-m-d')) }}"
                                            required
                                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                        />
                                    </div>

                                    <div>
                                        <label
                                            for="hora"
                                            class="mb-1 block text-xs font-semibold uppercase tracking-wider text-slate-700"
                                        >
                                            Hora de la Cita
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <select
                                            id="hora"
                                            name="hora"
                                            required
                                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                                        >
                                            <option value="" disabled selected>
                                                -- Selecciona un horario --
                                            </option>

                                            @php
                                                $inicio = \Carbon\Carbon::createFromTime(9, 0);$limite = \Carbon\Carbon::createFromTime(19, 0);
                                            @endphp

                                            @for ($time = $inicio->clone();$time->lte($limite);$time->addMinutes(30))
                                                <option
                                                    value="{{ $time->format('H:i') }}"
                                                    {{ old('hora') == $time->format('H:i') ? 'selected' : '' }}
                                                >
                                                    {{ $time->format('h:i A') }} ({{$time->format('H:i') }} hrs)
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                <!-- Botón de Submit -->
                                <div class="flex w-full justify-center pt-2">
                                    <button
                                        type="submit"
                                        class="w-lg flex max-w-md cursor-pointer items-center justify-center gap-2 rounded-xl bg-molaris-dark px-4 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-200 transition duration-150 ease-in-out hover:bg-black hover:-translate-y-0.5"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Agendar Cita
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- COLUMNA DERECHA: LOGO & IDENTIDAD DE MARCA (40% DE ANCHO EN DESKTOP) -->
                    <div
                        class="relative flex min-h-[350px] w-full flex-col justify-between overflow-hidden bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 p-6 text-white sm:p-8 lg:w-[40%]"
                    >
                        <!-- Efectos de fondo suaves -->
                        <div
                            class="pointer-events-none absolute -bottom-10 -right-10 h-44 w-44 rounded-full bg-indigo-500/10 blur-2xl"
                        ></div>
                        <div
                            class="pointer-events-none absolute -left-10 -top-10 h-44 w-44 rounded-full bg-cyan-500/10 blur-2xl"
                        ></div>

                        <div
                            class="relative z-10 my-auto flex flex-col items-center py-6 text-center"
                        >
                            <!-- LOGO MOLARIS (Sustituye 'images/logo.png' por tu ruta exacta) -->
                            <div
                                class="mb-4 rounded-2xl border border-white/10 bg-white/10 p-2 shadow-xl backdrop-blur-md"
                            >
                                <img
                                    src="{{ asset('img/logo.png') }}"
                                    alt="Molaris Logo"
                                    class="h-50 w-auto rounded-2xl object-contain"
                                    onError="
                                        this.style.display = 'none';
                                        document.getElementById(
                                            'logo-fallback-brand'
                                        ).style.display = 'block';
                                    "
                                />

                                <div
                                    id="logo-fallback-brand"
                                    class="hidden text-center"
                                >
                                    <span
                                        class="text-2xl font-black tracking-wider text-white"
                                        >MOLARIS</span
                                    >
                                    <p class="text-[10px] font-semibold uppercase tracking-widest text-indigo-300">Software Dental</p>
                                </div>
                            </div>

                            <span
                                class="mb-3 rounded-full border border-indigo-400/30 bg-indigo-500/20 px-3.5 py-1 text-xs font-medium tracking-wide text-indigo-300"
                            >
                                Sistema de Agenda
                            </span>
                            <p class="max-w-xs text-xs leading-relaxed text-slate-300">Control en tiempo real de disponibilidad para profesionales y boxes de atención.</p>
                        </div>

                        <!-- Card Informativa en la base -->
                        <div
                            class="relative z-10 space-y-2 rounded-xl border border-white/10 bg-white/5 p-4 backdrop-blur-sm"
                        >
                            <div
                                class="flex items-center gap-2 border-b border-white/10 pb-2 text-xs font-semibold text-indigo-200"
                            >
                                <svg class="h-4 w-4 shrink-0 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Tip de Operación</span>
                            </div>
                            <p class="text-[11px] leading-normal text-slate-300">Recuerda verificar la información de contacto antes de confirmar la cita programada.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Listado de Citas Agendadas -->
            <div
                class="border border-slate-100 bg-white p-4 shadow-xl sm:rounded-2xl sm:p-8"
            >
                <h3 class="mb-4 text-lg font-bold text-gray-900">
                    Citas Programadas
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500"
                                >
                                    Paciente
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500"
                                >
                                    Doctor
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500"
                                >
                                    Box
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500"
                                >
                                    Fecha y Hora
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500"
                                >
                                    Estado
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse (\App\Models\Cita::with(['paciente', 'doctor.usuario', 'box'])->get() as $cita)
                                <tr>
                                    <td
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-900"
                                    >
                                        {{ $cita->paciente->nombre ?? 'N/A' }}
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-900"
                                    >
                                        {{ $cita->doctor->usuario->name ?? $cita->doctor->usuario->nombre ?? 'N/A' }}
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-900"
                                    >
                                        {{ $cita->box->nombre ?? 'N/A' }}
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-900"
                                    >
                                        {{ $cita->fecha_hora }}
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-900"
                                    >
                                        <span
                                            class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold leading-5 text-green-800"
                                        >
                                            {{ $cita->estado }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td
                                        colspan="5"
                                        class="px-6 py-4 text-center text-sm text-gray-500"
                                    >
                                        No hay citas agendadas aún.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push ('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const inputBuscar = document.getElementById('buscar_paciente');
                const inputPacienteId = document.getElementById('paciente_id');
                const contenedorResultados = document.getElementById(
                    'resultados_paciente'
                );
                const pacienteSeleccionadoInfo = document.getElementById(
                    'paciente_seleccionado_info'
                );
                const nombrePacienteSeleccionado = document.getElementById(
                    'nombre_paciente_seleccionado'
                );
                const btnQuitarPaciente = document.getElementById(
                    'btn_quitar_paciente'
                );

                let timeoutBusqueda = null;

                if (inputBuscar) {
                    inputBuscar.addEventListener('input', function () {
                        clearTimeout(timeoutBusqueda);
                        const query = this.value.trim();

                        if (query.length < 2) {
                            contenedorResultados.classList.add('hidden');
                            contenedorResultados.innerHTML = '';
                            return;
                        }

                        // Debounce de 300ms para evitar peticiones excesivas
                        timeoutBusqueda = setTimeout(() => {
                            fetch(
                                `/api/pacientes/buscar?q=${encodeURIComponent(query)}`
                            )
                                .then((response) => response.json())
                                .then((data) => {
                                    contenedorResultados.innerHTML = '';

                                    if (data.length === 0) {
                                        contenedorResultados.innerHTML = `
                                <div class="p-4 text-sm text-slate-500 text-center">
                                    No se encontraron pacientes que coincidan.
                                </div>
                            `;
                                        contenedorResultados.classList.remove(
                                            'hidden'
                                        );
                                        return;
                                    }

                                    data.forEach((paciente) => {
                                        const item =
                                            document.createElement('div');

                                        item.className =
                                            'px-4 py-3 rounded-lg hover:bg-cyan-50/80 cursor-pointer transition-all duration-150 flex items-center justify-between border border-transparent hover:border-cyan-200';

                                        item.innerHTML = `
        <div class="flex flex-col pr-3">
            <span class="font-bold text-slate-800 text-sm tracking-wide mb-0.5">${paciente.nombre}</span>
            <span class="text-xs text-slate-500 font-mono flex items-center gap-1">
                <span class="font-semibold text-slate-400">RUT:</span> ${paciente.rut}
            </span>
        </div>
        <span class="text-[11px] font-semibold text-cyan-700 bg-cyan-100/70 px-3 py-1 rounded-full shrink-0">
            Seleccionar
        </span>
    `;

                                        item.onclick = function () {
                                            seleccionarPaciente(
                                                paciente.id,
                                                paciente.rut,
                                                paciente.nombre
                                            );
                                        };

                                        contenedorResultados.appendChild(item);
                                    });

                                    contenedorResultados.classList.remove(
                                        'hidden'
                                    );
                                })
                                .catch((error) => {
                                    console.error(
                                        'Error al buscar pacientes:',
                                        error
                                    );
                                });
                        }, 300);
                    });
                }

                window.seleccionarPaciente = function (id, rut, nombre) {
                    inputPacienteId.value = id;
                    inputBuscar.value = `${nombre} (RUT: ${rut})`;
                    inputBuscar.classList.add(
                        'bg-emerald-50',
                        'border-emerald-500',
                        'font-semibold',
                        'text-emerald-900'
                    );

                    contenedorResultados.classList.add('hidden');
                    contenedorResultados.innerHTML = '';
                };

                window.limpiarPaciente = function () {
                    inputPacienteId.value = '';
                    inputBuscar.value = '';

                    inputBuscar.classList.remove(
                        'bg-emerald-50',
                        'border-emerald-500',
                        'font-semibold',
                        'text-emerald-900'
                    );
                    inputBuscar.focus();
                };
            });
        </script>
    @endpush
</x-app-layout>
