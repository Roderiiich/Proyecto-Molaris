<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Editar Paciente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-xl sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form
                    action="{{ route('pacientes.update', $paciente->id) }}"
                    method="POST"
                    class="space-y-4"
                >
                    @csrf
                    @method('PUT')

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
                            value="{{ old('rut', $paciente->rut) }}"
                            placeholder="12.345.678-K"
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />
                        @error ('rut')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo Nombre Completo -->
                    <div>
                        <label
                            for="nombre"
                            class="block text-sm font-medium text-gray-700"
                            >Nombre Completo <span class="text-red-500">*</span></label
                        >
                        <input
                            type="text"
                            name="nombre"
                            id="nombre"
                            value="{{ old('nombre', $paciente->nombre) }}"
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />
                        @error ('nombre')
                            <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Campo Teléfono -->
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
                            value="{{ old('telefono', $paciente->telefono) }}"
                            placeholder="+56 9 1234 5678"
                            required
                            class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800 outline-none transition focus:ring-2 focus:ring-cyan-500"
                        />
                        @error ('telefono')
                            <span class="text-xs text-red-500">{{ $message }}</span>
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
                            value="{{ old('correo', $paciente->correo) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />
                        @error ('correo')
                            <span class="text-xs text-red-500">{{ $message }}</span>
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
                            class="inline-flex items-center rounded-lg bg-molaris-mint px-5 py-2.5 font-bold text-white shadow-md transition duration-200 ease-in-out hover:bg-emerald-600 hover:shadow-lg"
                        >
                            Actualizar Paciente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!-- SCRIPTS PARA RUT Y TELÉFONO -->
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Formateador Automático de Teléfono (+56 9)
        const inputTelefono = document.getElementById('telefono');
        if (inputTelefono) {
            inputTelefono.addEventListener('input', function() {
                let digitos = this.value.replace(/\D/g, '');

                // Permite vaciar el campo por completo
                if (digitos.length === 0) {
                    this.value = '';
                    return;
                }

                // Normaliza el prefijo +569
                if (!digitos.startsWith('569')) {
                    if (digitos.startsWith('9')) {
                        digitos = '56' + digitos;
                    } else {
                        digitos = '569' + digitos;
                    }
                }

                // Limita a 11 dígitos (+56 9 XXXX XXXX)
                digitos = digitos.substring(0, 11);

                let formateado = '+56 9';
                if (digitos.length > 3) {
                    let resto = digitos.substring(3);
                    if (resto.length <= 4) {
                        formateado += ' ' + resto;
                    } else {
                        formateado += ' ' + resto.substring(0, 4) + ' ' + resto.substring(4);
                    }
                }

                this.value = formateado;
            });

            // Evita bloqueos al borrar con Backspace o Delete cuando sólo queda el prefijo
            inputTelefono.addEventListener('keydown', function(e) {
                if ((e.key === 'Backspace' || e.key === 'Delete') && this.value.length <= 6) {
                    this.value = '';
                }
            });
        }

        // 2. Formateador Automático de RUT Chileno (XX.XXX.XXX-K)
        const inputRut = document.getElementById('rut');
        if (inputRut) {
            inputRut.addEventListener('input', function() {
                // Elimina caracteres no válidos y convierte 'k' a mayúscula
                let valor = this.value.replace(/[^0-9kK]/g, '').toUpperCase();

                // Máximo 9 caracteres (8 dígitos + 1 DV)
                if (valor.length > 9) {
                    valor = valor.substring(0, 9);
                }

                if (valor.length === 0) {
                    this.value = '';
                    return;
                }

                // Si se escribe un solo carácter, lo deja limpio
                if (valor.length === 1) {
                    this.value = valor;
                    return;
                }

                // Separa cuerpo y dígito verificador para aplicar puntos y guion
                let cuerpo = valor.slice(0, -1);
                let dv = valor.slice(-1);
                cuerpo = cuerpo.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

                this.value = `${cuerpo}-${dv}`;
            });
        }
    });
</script>
@endpush
    
</x-app-layout>