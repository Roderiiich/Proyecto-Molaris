<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Registrar Nuevo Paciente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-xl sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                
                <!-- ICONO SVG DE PERSONA DESTACADO -->
                <div class="flex flex-col items-center justify-center pb-6">
                    <div class="flex h-20 w-20 items-center justify-center rounded-full bg-molaris-dark text-white shadow-inner ">
                        <svg class="h-20 w-12 p-20 " fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="mt-6 text-xl font-bold text-slate-800 ">Ficha del Paciente</h3>
                    <p class="text-xs text-slate-500">Ingresa los datos para registrarlo en el sistema Molaris</p>
                </div>

                <form
                    action="{{ route('pacientes.store') }}"
                    method="POST"
                    class="space-y-4"
                >
                    @csrf

                    <!-- Campo RUT -->
                    <div>
                        <label
                            for="rut"
                            class="block text-sm font-medium text-gray-700"
                            >RUT <span class="text-red-500">*</span></label
                        >
                        <input
                            type="text"
                            name="rut"
                            id="rut"
                            value="{{ old('rut') }}"
                            placeholder="12.345.678-K"
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />
                        @error ('rut')
                            <span
                                class="text-xs text-red-500"
                                >{{ $message }}</span
                            >
                        @enderror
                    </div>

                    <!-- Campo Nombre Completo -->
                    <div>
                        <label
                            for="nombre"
                            class="block text-sm font-medium text-gray-700"
                            >Nombre Completo
                            <span class="text-red-500">*</span></label
                        >
                        <input
                            type="text"
                            name="nombre"
                            id="nombre"
                            value="{{ old('nombre') }}"
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />
                        @error ('nombre')
                            <span
                                class="text-xs text-red-500"
                                >{{ $message }}</span
                            >
                        @enderror
                    </div>

                    <!-- Campo del Teléfono -->
                    <div>
                        <label
                            for="telefono"
                            class="mb-1 block text-xs font-semibold uppercase text-slate-700"
                        >
                            Teléfono Móvil <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="telefono"
                            name="telefono"
                            value="{{ old('telefono', $paciente->telefono ?? '+56 9 ') }}"
                            placeholder="+56 9 1234 5678"
                            required
                            class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800 outline-none transition focus:ring-2 focus:ring-cyan-500"
                        />
                        @error ('telefono')
                            <span
                                class="text-xs text-red-500"
                                >{{ $message }}</span
                            >
                        @enderror
                    </div>

                    <!-- Campo Correo Electrónico -->
                    <div>
                        <label
                            for="correo"
                            class="block text-sm font-medium text-gray-700"
                            >Correo Electrónico</label
                        >
                        <input
                            type="email"
                            name="correo"
                            id="correo"
                            value="{{ old('correo') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />
                        @error ('correo')
                            <span
                                class="text-xs text-red-500"
                                >{{ $message }}</span
                            >
                        @enderror
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex items-center justify-end pt-4">
                        <a
                            href="{{ route('pacientes.index') }}"
                            class="mr-4 text-sm text-gray-600 hover:text-gray-900"
                            >Cancelar</a
                        >
                        <button
                            type="submit"
                            class="inline-flex items-center rounded-lg bg-molaris-dark px-5 py-1.5 font-bold text-white shadow-md transition duration-200 ease-in-out hover:bg-black hover:shadow-lg"
                        >
                            Guardar Paciente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- PUSH DE SCRIPTS PARA BLADE -->
    @push ('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // 1. Formateador Automático de Teléfono (+56 9)
                const inputTelefono = document.getElementById('telefono');
                if (inputTelefono) {
                    inputTelefono.addEventListener('input', function () {
                        let digitos = this.value.replace(/\D/g, '');

                        if (digitos.length === 0) {
                            this.value = '';
                            return;
                        }

                        if (!digitos.startsWith('569')) {
                            if (digitos.startsWith('9')) {
                                digitos = '56' + digitos;
                            } else {
                                digitos = '569' + digitos;
                            }
                        }

                        digitos = digitos.substring(0, 11);

                        let formateado = '+56 9';
                        if (digitos.length > 3) {
                            let resto = digitos.substring(3);
                            if (resto.length <= 4) {
                                formateado += ' ' + resto;
                            } else {
                                formateado +=
                                    ' ' +
                                    resto.substring(0, 4) +
                                    ' ' +
                                    resto.substring(4);
                            }
                        }

                        this.value = formateado;
                    });

                    inputTelefono.addEventListener('keydown', function (e) {
                        if (
                            (e.key === 'Backspace' || e.key === 'Delete') &&
                            this.value.length <= 6
                        ) {
                            this.value = '';
                        }
                    });
                }

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
            });
        </script>
    @endpush
</x-app-layout>