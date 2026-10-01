<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-black leading-tight text-slate-800">
                    {{ __('Gestión de Odontólogos') }}
                </h2>
                <p class="mt-1 text-sm font-medium text-slate-500">Administración del cuerpo médico y asignación de especialidades</p>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-100/70 py-8">
        <div class="mx-auto max-w-7xl space-y-8 sm:px-6 lg:px-8">
            <!-- Alertas de éxito y error -->
            @if (session('success'))
                <div
                    class="flex items-center gap-3 rounded-2xl border-2 border-emerald-300 bg-emerald-50 p-4 text-sm font-bold text-emerald-900 shadow-sm"
                    role="alert"
                >
                    <svg class="h-6 w-6 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div
                    class="rounded-2xl border-2 border-red-300 bg-red-50 p-4 text-sm font-bold text-red-900 shadow-sm"
                >
                    <p class="mb-1 text-base font-extrabold">Por favor corrige los siguientes errores:</p>
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Formulario de Registro de Doctor -->
            <div
                class="relative overflow-hidden rounded-3xl border-2 border-cyan-100 bg-white p-6 shadow-md sm:p-8"
            >
                <div
                    class="mb-6 flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 pb-4"
                >
                    <div class="flex items-center gap-3.5">
                        <!-- Ícono SVG para la cabecera (Visibilidad Garantizada) -->
                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl shadow-md"
                            style="
                                background: linear-gradient(
                                    135deg,
                                    #03b1b1 0%,
                                    #072874 100%
                                );
                                border: 1px solid #a5f3fc;
                                margin-right: 15px;
                            "
                        >
                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h3
                                class="text-xl font-black tracking-tight text-slate-800"
                            >
                                Registrar Nuevo Odontólogo
                            </h3>
                            <p class="mt-0.5 text-sm font-medium text-slate-500">Completa los datos para dar de alta a un profesional en el sistema.</p>
                        </div>
                    </div>

                    <span
                        class="rounded-full border border-cyan-200/80 bg-cyan-50 px-3.5 py-1.5 text-xs font-extrabold uppercase tracking-wider text-cyan-700"
                    >
                        Módulo Médico
                    </span>
                </div>

                <form action="{{ route('doctores.store') }}" method="POST">
                    @csrf
                    <!-- Fondo suave para enmarcar las entradas -->
                    <div
                        class="grid grid-cols-1 gap-6 rounded-2xl border border-slate-200/80 bg-slate-50/80 p-5 md:grid-cols-3"
                    >
                        <!-- Selección de Usuario -->
                        <div>
                            <label
                                for="usuario_id"
                                class="mb-2 block text-sm font-bold text-slate-800"
                            >
                                Usuario Asociado
                                <span class="text-cyan-600">*</span>
                            </label>
                            <select
                                name="usuario_id"
                                id="usuario_id"
                                required
                                class="shadow-xs w-full rounded-xl border-2 border-slate-200 bg-white px-3.5 py-2.5 text-sm font-semibold text-slate-800 transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200"
                            >
                                <option value="">
                                    -- Seleccionar Usuario --
                                </option>
                                @foreach ($usuarios as $usuario)
                                    <option
                                        value="{{ $usuario->id }}"
                                        {{ old('usuario_id') == $usuario->id ? 'selected' : '' }}
                                    >
                                        {{ $usuario->name ?? $usuario->nombre }} ({{ $usuario->email ?? $usuario->correo }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- RUT -->
                        <div>
                            <label
                                for="rut"
                                class="mb-2 block text-sm font-bold text-slate-800"
                            >
                                RUT / Identificación
                                <span class="text-cyan-600">*</span>
                            </label>
                            <input
                                type="text"
                                name="rut"
                                id="rut"
                                value="{{ old('rut') }}"
                                placeholder="12345678-9"
                                required
                                class="shadow-xs w-full rounded-xl border-2 border-slate-200 bg-white px-3.5 py-2.5 text-sm font-semibold text-slate-800 transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200"
                            />
                        </div>

                        <!-- Especialidad con Selector -->
                        <div>
                            <label
                                for="especialidad"
                                class="mb-2 block text-sm font-bold text-slate-800"
                            >
                                Especialidad Médica
                                <span class="text-cyan-600">*</span>
                            </label>
                            <select
                                name="especialidad"
                                id="especialidad"
                                required
                                class="shadow-xs w-full rounded-xl border-2 border-slate-200 bg-white px-3.5 py-2.5 text-sm font-semibold text-slate-800 transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200"
                            >
                                <option value="">
                                    -- Seleccionar Especialidad --
                                </option>
                                <option
                                    value="Odontología General"
                                    {{ old('especialidad') == 'Odontología General' ? 'selected' : '' }}
                                >
                                    Odontología General
                                </option>
                                <option
                                    value="Ortodoncia y Ortopedia Dento Facial"
                                    {{ old('especialidad') == 'Ortodoncia y Ortopedia Dento Facial' ? 'selected' : '' }}
                                >
                                    Ortodoncia y Ortopedia Dento Facial
                                </option>
                                <option
                                    value="Endodoncia"
                                    {{ old('especialidad') == 'Endodoncia' ? 'selected' : '' }}
                                >
                                    Endodoncia
                                </option>
                                <option
                                    value="Periodoncia"
                                    {{ old('especialidad') == 'Periodoncia' ? 'selected' : '' }}
                                >
                                    Periodoncia
                                </option>
                                <option
                                    value="Rehabilitación Oral"
                                    {{ old('especialidad') == 'Rehabilitación Oral' ? 'selected' : '' }}
                                >
                                    Rehabilitación Oral
                                </option>
                                <option
                                    value="Odontopediatría"
                                    {{ old('especialidad') == 'Odontopediatría' ? 'selected' : '' }}
                                >
                                    Odontopediatría
                                </option>
                                <option
                                    value="Implantología Buco Maxilofacial"
                                    {{ old('especialidad') == 'Implantología Buco Maxilofacial' ? 'selected' : '' }}
                                >
                                    Implantología Buco Maxilofacial
                                </option>
                                <option
                                    value="Cirugía Bucal y Maxilofacial"
                                    {{ old('especialidad') == 'Cirugía Bucal y Maxilofacial' ? 'selected' : '' }}
                                >
                                    Cirugía Bucal y Maxilofacial
                                </option>
                                <option
                                    value="Radiología Oral y Maxilofacial"
                                    {{ old('especialidad') == 'Radiología Oral y Maxilofacial' ? 'selected' : '' }}
                                >
                                    Radiología Oral y Maxilofacial
                                </option>
                                <option
                                    value="Disfunción Cráneomandibular y Dolor Orofacial"
                                    {{ old('especialidad') == 'Disfunción Cráneomandibular y Dolor Orofacial' ? 'selected' : '' }}
                                >
                                    Disfunción Cráneomandibular y Dolor
                                    Orofacial
                                </option>
                                <option
                                    value="Patología Oral"
                                    {{ old('especialidad') == 'Patología Oral' ? 'selected' : '' }}
                                >
                                    Patología Oral
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button
                            type="submit"
                            class="inline-flex transform items-center gap-2 rounded-xl bg-molaris-dark px-6 py-3 text-sm font-bold text-white shadow-lg shadow-cyan-600/30 transition hover:-translate-y-0.5 hover:bg-black"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <span>Guardar Odontólogo</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabla de Doctores Registrados -->
            <div
                class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm sm:p-8"
            >
                <div
                    class="mb-5 flex items-center justify-between border-b border-slate-100 pb-3"
                >
                    <div>
                        <h3 class="text-lg font-black text-slate-800">
                            Nómina de Odontólogos
                        </h3>
                        <p class="text-sm font-medium text-slate-500">Listado de profesionales registrados en la clínica.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-700">
                        <thead
                            class="border-b border-slate-200 bg-slate-100/80 text-xs font-bold uppercase tracking-wider text-slate-600"
                        >
                            <tr>
                                <th class="px-5 py-3.5">Nombre Completo</th>
                                <th class="px-5 py-3.5">Correo</th>
                                <th class="px-5 py-3.5">RUT</th>
                                <th class="px-5 py-3.5">Especialidad</th>
                                <th class="px-5 py-3.5 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @forelse ($doctores as $doctor)
                                <tr class="transition hover:bg-slate-50">
                                    <td
                                        class="px-5 py-4 text-base font-bold text-slate-900"
                                    >
                                        {{ $doctor->usuario->name ?? $doctor->usuario->nombre ?? 'Sin Nombre' }}
                                    </td>
                                    <td
                                        class="px-5 py-4 font-medium text-slate-600"
                                    >
                                        {{ $doctor->usuario->email ?? $doctor->usuario->correo ?? 'Sin Correo' }}
                                    </td>
                                    <td
                                        class="px-5 py-4 font-mono font-bold text-slate-700"
                                    >
                                        {{ $doctor->rut }}
                                    </td>
                                    <td class="px-5 py-4">
                                        <span
                                            class="inline-flex items-center rounded-xl border border-cyan-200 bg-molaris-dark px-3 py-1 text-xs font-bold text-white"
                                        >
                                            {{ $doctor->especialidad }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <form
                                            action="{{ route('doctores.destroy', $doctor->id) }}"
                                            method="POST"
                                            class="inline-block"
                                            onsubmit="
                                                return confirm(
                                                    '¿Estás seguro de eliminar este odontólogo?'
                                                );
                                            "
                                        >
                                            @csrf
                                            @method ('DELETE')
                                            <button
                                                type="submit"
                                                class="shadow-xs rounded-xl border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-bold text-red-600 transition hover:bg-red-600 hover:text-white"
                                            >
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td
                                        colspan="5"
                                        class="px-5 py-10 text-center text-sm font-bold text-slate-400"
                                    >
                                        No hay odontólogos registrados
                                        actualmente.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- PUSH DE SCRIPTS PARA BLADE -->
    @push ('scripts')
        <script>
           

                // 2. Formateador Automático de RUT Chileno (XX.XXX.XXX-K)
                const inputRut = document.getElementById('rut');
                if (inputRut) {
                    inputRut.addEventListener('input', function () {
                        // Limpia caracteres no válidos y convierte K a mayúscula
                        let valor = this.value
                            .replace(/[^0-9kK]/g, '')
                            .toUpperCase();

                        // Limita a máximo 9 caracteres (8 dígitos + DV)
                        if (valor.length > 9) {
                            valor = valor.substring(0, 9);
                        }

                        if (valor.length === 0) {
                            this.value = '';
                            return;
                        }

                        // Si solo hay un carácter, lo deja sin formato
                        if (valor.length === 1) {
                            this.value = valor;
                            return;
                        }

                        // Separa cuerpo y dígito verificador para formatear con puntos y guion
                        let cuerpo = valor.slice(0, -1);
                        let dv = valor.slice(-1);
                        cuerpo = cuerpo.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

                        this.value = `${cuerpo}-${dv}`;
                    });
                }
            

        </script>
    @endpush
</x-app-layout>
