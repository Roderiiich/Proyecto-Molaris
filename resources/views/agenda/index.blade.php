<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Agenda Clínica - Molaris') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Mensajes de alerta -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ $errors->first('error') }}
                </div>
            @endif

            <!-- Formulario para Agendar -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Agendar Nueva Cita</h3>

                    <form action="{{ route('citas.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <!-- Seleccionar Paciente -->
                        <div>
                            <label for="paciente_id" class="block text-sm font-medium text-gray-700">Paciente</label>
                            <select name="paciente_id" id="paciente_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Seleccione un paciente</option>
                                @foreach(\App\Models\Paciente::all() as $paciente)
                                    <option value="{{ $paciente->id }}">{{ $paciente->nombre }} ({{ $paciente->rut }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Seleccionar Odontólogo -->
                        <div>
                            <label for="doctor_id" class="block text-sm font-medium text-slate-700">Odontólogo</label>
                            <select name="doctor_id" id="doctor_id" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-molaris-primary focus:ring-molaris-primary" required>
                                <option value="">Seleccione un odontólogo</option>
                                @foreach(\App\Models\Doctor::with('usuario')->get() as $doctor)
                                    <option value="{{ $doctor->id }}">
                                        Dr. {{ $doctor->usuario->name ?? $doctor->usuario->nombre ?? 'Sin nombre' }} - {{ $doctor->especialidad }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Seleccionar Box -->
                        <div>
                            <label for="box_id" class="block text-sm font-medium text-gray-700">Box de Atención</label>
                            <select name="box_id" id="box_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">Seleccione un box</option>
                                @foreach(\App\Models\Box::all() as $box)
                                    <option value="{{ $box->id }}">{{ $box->nombre }} ({{ $box->estado }})</option>
                                @endforeach
                            </select>
                        </div>

                       <!-- Fecha y Hora -->
                        <div>
                            <label for="fecha_hora" class="block text-sm font-medium text-gray-700">Fecha y Hora</label>
                            <input type="datetime-local" name="fecha_hora" id="fecha_hora" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <!-- Botón de Agendar Cita -->
                        <div class="pt-4">
                            <button type="submit" style="background-color: #4f46e5; color: white; padding: 10px 20px; border-radius: 6px; font-weight: bold; cursor: pointer;">
                                Agendar Cita
                            </button>
                        </div>

                    </form>
                    </form>
                </div>
            </div>

            <!-- Listado de Citas Agendadas -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Citas Programadas</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paciente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Doctor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Box</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha y Hora</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse(\App\Models\Cita::with(['paciente', 'doctor.usuario', 'box'])->get() as $cita)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $cita->paciente->nombre ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $cita->doctor->usuario->nombre ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $cita->box->nombre ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $cita->fecha_hora }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $cita->estado }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No hay citas agendadas aún.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>