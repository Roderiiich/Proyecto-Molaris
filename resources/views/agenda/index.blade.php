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
                    class="relative rounded border border-green-400 bg-green-100 px-4 py-3 text-green-700"
                >
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->has('error'))
                <div
                    class="relative rounded border border-red-400 bg-red-100 px-4 py-3 text-red-700"
                >
                    {{ $errors->first('error') }}
                </div>
            @endif
            <!-- Formulario para Agendar -->
            <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                <div class="max-w-xl">
                    <h3 class="mb-4 text-lg font-bold text-gray-900">
                        Agendar Nueva Cita
                    </h3>

                    <form
                        action="{{ route('citas.store') }}"
                        method="POST"
                        class="space-y-4"
                    >
                        @csrf

                        <!-- ========================================== -->
                        <!-- 1. BUSCADOR DE PACIENTE POR RUT O NOMBRE   -->
                        <!-- ========================================== -->
                        <div class="relative mb-4">
                            <label
                                for="buscar_paciente"
                                class="mb-1 block text-xs font-semibold uppercase tracking-wider text-slate-700"
                            >
                                Buscar Paciente (RUT o Nombre)
                                <span class="text-red-500">*</span>
                            </label>

                            <div id="contenedor_input_buscar" class="relative">
                                <input
                                    type="text"
                                    id="buscar_paciente"
                                    autocomplete="off"
                                    placeholder="Ej: 15666777-8 o Juan Pérez..."
                                    class="w-full rounded-lg border border-slate-300 bg-slate-50 py-2 pl-3 pr-10 text-sm text-slate-800 outline-none transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500"
                                />

                                <!-- Botón para limpiar/resetear la selección -->
                                <button
                                    type="button"
                                    onclick="limpiarPaciente()"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 font-bold text-slate-400 transition hover:text-red-500"
                                >
                                    ✕
                                </button>
                            </div>

                            <!-- Menú desplegable flotante con resultados -->
                            <div
                                id="resultados_paciente"
                                class="absolute left-0 right-0 z-50 mt-2 hidden max-h-72 space-y-1 overflow-y-auto rounded-xl border border-slate-200 bg-white p-2 shadow-2xl"
                            ></div>

                            <!-- ID Oculto que se enviará al CitaController -->
                            <input
                                type="hidden"
                                name="paciente_id"
                                id="paciente_id"
                                value="{{ old('paciente_id') }}"
                                required
                            />

                            <!-- Confirmación del paciente seleccionado (Permanente) -->
                            <div
                                id="paciente_seleccionado"
                                class="mt-2 flex hidden items-center justify-between rounded-lg border border-emerald-200 bg-emerald-50 p-3 text-xs font-medium text-emerald-800 shadow-sm"
                            >
                                <div class="flex items-center space-x-2">
                                    <svg class="h-4 w-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span
                                        id="texto_paciente"
                                        class="text-sm font-semibold text-slate-800"
                                    ></span>
                                </div>
                                <button
                                    type="button"
                                    onclick="limpiarPaciente()"
                                    class="rounded-md bg-red-100 px-2.5 py-1 text-xs font-bold text-red-600 transition hover:bg-red-200 hover:text-red-800"
                                >
                                    ✕ Cambiar
                                </button>
                            </div>
                        </div>

                        <!-- ========================================== -->
                        <!-- 2. SELECCIONAR ODONTÓLOGO                  -->
                        <!-- ========================================== -->
                        <div class="mb-4">
                            <label
                                for="doctor_id"
                                class="mb-1 block text-xs font-semibold uppercase tracking-wider text-slate-700"
                                >Odontólogo
                                <span class="text-red-500">*</span></label
                            >
                            <select
                                name="doctor_id"
                                id="doctor_id"
                                class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800 outline-none transition focus:ring-2 focus:ring-cyan-500"
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

                        <!-- ========================================== -->
                        <!-- 3. SELECCIONAR BOX DE ATENCIÓN            -->
                        <!-- ========================================== -->
                        <div class="mb-4">
                            <label
                                for="box_id"
                                class="mb-1 block text-xs font-semibold uppercase tracking-wider text-slate-700"
                                >Box de Atención
                                <span class="text-red-500">*</span></label
                            >
                            <select
                                name="box_id"
                                id="box_id"
                                class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800 outline-none transition focus:ring-2 focus:ring-cyan-500"
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

                        <!-- ========================================== -->
                        <!-- 4. SELECCIÓN DE FECHA                      -->
                        <!-- ========================================== -->
                        <div class="mb-4">
                            <label
                                for="fecha"
                                class="mb-1 block text-xs font-semibold uppercase tracking-wider text-slate-700"
                                >Fecha de la Cita
                                <span class="text-red-500">*</span></label
                            >
                            <input
                                type="date"
                                id="fecha"
                                name="fecha"
                                value="{{ old('fecha', date('Y-m-d')) }}"
                                required
                                class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800 outline-none transition focus:ring-2 focus:ring-cyan-500"
                            />
                        </div>

                        <!-- Selección de Hora (Intervalos de 30 min: 09:00 a 19:00) -->
                        <div class="mb-4">
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
                                class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800 outline-none transition focus:ring-2 focus:ring-cyan-500"
                            >
                                <option value="" disabled selected>
                                    -- Selecciona un horario --
                                </option>

                                @php
            $inicio = \Carbon\Carbon::createFromTime(9, 0);  // 09:00 AM
            $fin = \Carbon\Carbon::createFromTime(19, 0);     // 07:00 PM
        @endphp

                                @while ($inicio <= $fin)
                                    <option
                                        value="{{ $inicio->format('H:i') }}"
                                        {{ old('hora') == $inicio->format('H:i') ? 'selected' : '' }}
                                    >
                                        {{ $inicio->format('h:i A') }} ({{ $inicio->format('H:i') }} hrs)
                                    </option>
                                    @php $inicio->addMinutes(30); @endphp
                                @endwhile
                            </select>
                        </div>

                        <!-- ========================================== -->
                        <!-- 6. BOTÓN DE AGENDAR CITA                  -->
                        <!-- ========================================== -->
                        <div class="pt-2">
                            <button
                                type="submit"
                                class="w-full cursor-pointer rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow transition duration-150 ease-in-out hover:bg-indigo-700"
                            >
                                Agendar Cita
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Listado de Citas Agendadas -->
            <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
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
                                            class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800"
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

                                        // Padding generoso (px-4 py-3) y bordes redondeados para separarlo del marco
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
                    // 1. Asignamos el ID oculto
                    inputPacienteId.value = id;

                    // 2. Colocamos el nombre y RUT dentro de la misma casilla de búsqueda
                    inputBuscar.value = `${nombre} (RUT: ${rut})`;

                    // 3. Cambiamos el estilo del input para indicar que está seleccionado
                    inputBuscar.classList.add(
                        'bg-emerald-50',
                        'border-emerald-500',
                        'font-semibold',
                        'text-emerald-900'
                    );

                    // 4. Ocultamos el menú desplegable de resultados
                    contenedorResultados.classList.add('hidden');
                    contenedorResultados.innerHTML = '';
                };

                window.limpiarPaciente = function () {
                    inputPacienteId.value = '';
                    inputBuscar.value = '';

                    // Restauramos el estilo original del input
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
