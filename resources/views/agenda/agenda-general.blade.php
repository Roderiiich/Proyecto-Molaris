<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold leading-tight text-slate-800">
                    {{ __('Agenda General de la Clínica') }}
                </h2>
                <p class="text-xs text-slate-500">
                    Visualización completa de horarios, boxes y atenciones programadas.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('agenda.personal') }}" 
                   class="inline-flex items-center gap-1 rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    <svg class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Mi Agenda Personal
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- BARRA DE FILTROS Y BÚSQUEDA -->
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <form method="GET" action="{{ route('agenda.index') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-5">
                    
                    <!-- Fecha -->
                    <div>
                        <label for="fecha" class="block text-xs font-semibold text-slate-600 mb-1">Fecha</label>
                        <input type="date" 
                               id="fecha" 
                               name="fecha" 
                               value="{{ request('fecha', now()->format('Y-m-d')) }}" 
                               class="w-full rounded-lg border-slate-300 text-xs text-slate-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Doctor / Odontólogo -->
                    <div>
                        <label for="doctor_id" class="block text-xs font-semibold text-slate-600 mb-1">Doctor / Odontólogo</label>
                        <select id="doctor_id" 
                                name="doctor_id" 
                                class="w-full rounded-lg border-slate-300 text-xs text-slate-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Todos los doctores</option>
                            @foreach ($doctores ?? [] as $doc)
                                <option value="{{ $doc->id }}" {{ request('doctor_id') == $doc->id ? 'selected' : '' }}>
                                    {{ $doc->usuario->name ?? $doc->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Box de Atención -->
                    <div>
                        <label for="box_id" class="block text-xs font-semibold text-slate-600 mb-1">Box de Atención</label>
                        <select id="box_id" 
                                name="box_id" 
                                class="w-full rounded-lg border-slate-300 text-xs text-slate-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Todos los Boxes</option>
                            @foreach ($boxes ?? [] as $box)
                                <option value="{{ $box->id }}" {{ request('box_id') == $box->id ? 'selected' : '' }}>
                                    {{ $box->nombre ?? 'Box ' . $box->numero }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Estado -->
                    <div>
                        <label for="estado" class="block text-xs font-semibold text-slate-600 mb-1">Estado Cita</label>
                        <select id="estado" 
                                name="estado" 
                                class="w-full rounded-lg border-slate-300 text-xs text-slate-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Todos los estados</option>
                            <option value="programada" {{ request('estado') === 'programada' ? 'selected' : '' }}>Programada</option>
                            <option value="confirmada" {{ request('estado') === 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                            <option value="completada" {{ request('estado') === 'completada' ? 'selected' : '' }}>Atendida</option>
                            <option value="cancelada" {{ request('estado') === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                    </div>

                    <!-- Botón Filtrar -->
                    <div class="flex items-end gap-2">
                        <button type="submit" 
                                class="w-full rounded-lg bg-indigo-600 px-3 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-indigo-700">
                            Filtrar
                        </button>
                        <a href="{{ route('agenda.index') }}" 
                           class="rounded-lg border border-slate-300 bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-600 transition hover:bg-slate-200">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            <!-- TABLA / LISTADO DE HORARIOS GENERALES -->
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 bg-slate-50/50 px-6 py-3 flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Citas Programadas para: <span class="text-indigo-600">{{ \Carbon\Carbon::parse(request('fecha', now()))->isoFormat('D [de] MMMM [de] YYYY') }}</span>
                    </span>
                    <span class="rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-bold text-indigo-700">
                        Total: {{ count($citas ?? []) }} Citas
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-100 text-[11px] font-semibold uppercase text-slate-500 border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-3">Horario</th>
                                <th class="px-4 py-3">Paciente</th>
                                <th class="px-4 py-3">Doctor</th>
                                <th class="px-4 py-3">Box</th>
                                <th class="px-4 py-3">Tratamiento / Motivo</th>
                                <th class="px-4 py-3">Estado</th>
                                <th class="px-4 py-3 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @forelse ($citas ?? [] as $cita)
                                <tr class="transition hover:bg-slate-50/80">
                                    
                                    <!-- Horario -->
                                    <td class="whitespace-nowrap px-4 py-3 font-mono font-bold text-slate-800">
                                        {{ \Carbon\Carbon::parse($cita->hora_inicio)->format('H:i') }} - 
                                        {{ \Carbon\Carbon::parse($cita->hora_fin)->format('H:i') }}
                                    </td>

                                    <!-- Paciente -->
                                    <td class="px-4 py-3">
                                        @if ($cita->paciente)
                                            <div class="font-bold text-slate-900">{{ $cita->paciente->nombre }}</div>
                                            <div class="text-[10px] text-slate-400">RUT: {{ $cita->paciente->rut ?? 'N/A' }}</div>
                                        @else
                                            <span class="italic text-slate-400">Sin Paciente Asignado</span>
                                        @endif
                                    </td>

                                    <!-- Doctor -->
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="font-semibold text-slate-700">
                                            Dr(a). {{ $cita->doctor->usuario->name ?? $cita->doctor->nombre ?? 'N/A' }}
                                        </span>
                                    </td>

                                    <!-- Box -->
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-700">
                                            {{ $cita->box->nombre ?? 'Box ' . ($cita->box->numero ?? 'N/A') }}
                                        </span>
                                    </td>

                                    <!-- Tratamiento -->
                                    <td class="px-4 py-3">
                                        <span class="text-slate-700 truncate max-w-[200px] block" title="{{ $cita->motivo ?? $cita->tratamiento }}">
                                            {{ $cita->motivo ?? $cita->tratamiento ?? 'Consulta General' }}
                                        </span>
                                    </td>

                                    <!-- Estado -->
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        @php
                                            $estadoClasses = [
                                                'programada' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                'confirmada' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'completada' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                'cancelada' => 'bg-rose-50 text-rose-700 border-rose-200',
                                            ];
                                            $class = $estadoClasses[$cita->estado] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                                        @endphp
                                        <span class="inline-flex rounded-full border px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide {{ $class }}">
                                            {{ ucfirst($cita->estado) }}
                                        </span>
                                    </td>

                                    <!-- Acciones -->
                                    <td class="whitespace-nowrap px-4 py-3 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            @if ($cita->paciente_id)
                                                <!-- Ficha Clínica -->
                                                <a href="{{ route('fichas.show', $cita->paciente_id) }}" 
                                                   class="rounded border border-slate-200 bg-white px-2 py-1 text-[11px] font-semibold text-slate-700 shadow-sm transition hover:bg-slate-100"
                                                   title="Ver Ficha Clínica">
                                                    Ficha
                                                </a>

                                                <!-- Crear Presupuesto -->
                                                <a href="{{ route('presupuestos.create', ['paciente_id' => $cita->paciente_id]) }}" 
                                                   class="rounded border border-emerald-200 bg-emerald-50 px-2 py-1 text-[11px] font-semibold text-emerald-700 shadow-sm transition hover:bg-emerald-100"
                                                   title="Crear Presupuesto">
                                                    + Presupuesto
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-slate-400">
                                        <svg class="mx-auto h-8 w-8 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p class="text-sm font-semibold">No hay citas registradas para los filtros seleccionados.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>