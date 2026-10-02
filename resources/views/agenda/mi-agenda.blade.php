<x-app-layout>
    <x-slot name="header">
        <div
            class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center"
        >
            <div>
                <h2 class="text-xl font-semibold leading-tight text-slate-800">
                    Mi Agenda Personal
                </h2>
                <p class="text-sm text-slate-500">
                    Dr(a). {{ $doctor->usuario->name ?? auth()->user()->name }} — {{ $doctor->especialidad }}
                </p>
            </div>

            {{-- Navegador de Semanas --}}
            <div
                class="flex items-center space-x-2 rounded-lg border border-slate-200 bg-white p-1.5 shadow-sm"
            >
                <a
                    href="{{ route('agenda.personal', ['fecha_inicio' => $fechaInicio->copy()->subWeek()->format('Y-m-d')]) }}"
                    class="rounded-md bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-200"
                >
                    &larr; Semana Anterior
                </a>

                <span class="px-3 text-xs font-bold text-slate-800">
                    {{ $fechaInicio->format('d/m/Y') }} al {{ $fechaFin->format('d/m/Y') }}
                </span>

                <a
                    href="{{ route('agenda.personal', ['fecha_inicio' => $fechaInicio->copy()->addWeek()->format('Y-m-d')]) }}"
                    class="rounded-md bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-200"
                >
                    Semana Siguiente &rarr;
                </a>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <div
            class="overflow-hidden border border-slate-100 bg-white p-6 shadow-xl sm:rounded-xl"
        >
            {{-- Tabla de Horario Semanal (Lunes a Sábado) --}}
            <div class="overflow-x-auto">
                <table
                    class="w-full border-collapse overflow-hidden rounded-lg border border-slate-200 text-sm"
                >
                    <thead>
                        <tr class="bg-slate-800 text-white">
                            <th
                                class="w-24 border-r border-slate-700 p-3 text-center"
                            >
                                Hora
                            </th>
                            @for ($i = 0; $i < 6; $i++)
                                @php $dia = $fechaInicio->copy()->addDays($i); @endphp
                                <th
                                    class="p-3 text-center border-r border-slate-700 {{ $dia->isToday() ? 'bg-indigo-600 font-bold' : '' }}"
                                >
                                    <div
                                        class="text-xs uppercase tracking-wider opacity-80"
                                    >
                                        {{ $dia->isoFormat('dddd') }}
                                    </div>
                                    <div class="text-base font-semibold">
                                        {{ $dia->format('d/m') }}
                                    </div>
                                </th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $bloquesHorarios = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00'];
                        @endphp

                        @foreach ($bloquesHorarios as $hora)
                            <tr
                                class="border-b border-emerald-600 transition hover:bg-slate-50/50"
                            >
                                <td
                                    class="border-r border-slate-200 bg-slate-50 p-2 text-center text-xs font-medium text-slate-500"
                                >
                                    {{ $hora }}
                                </td>

                                @for ($i = 0; $i < 6; $i++)
                                    @php
        $fechaDia = $fechaInicio->copy()->addDays($i)->format('Y-m-d');
        
        // Filtrar citas correspondientes a este día y hora
        // Se excluyen únicamente las rechazadas y canceladas para liberar la hora
        $citasBloque = $citas->filter(function ($c) use ($fechaDia, $hora) {
            $fechaCita = \Carbon\Carbon::parse($c->fecha_hora)->format('Y-m-d');
            $horaCita = \Carbon\Carbon::parse($c->fecha_hora)->format('H:i');
            
            $estadoClean = strtolower(trim($c->estado));
            $esRechazadaOCancelada = in_array($estadoClean, ['rechazada', 'cancelada']);
            
            return $fechaCita === $fechaDia && $horaCita === $hora && !$esRechazadaOCancelada;
        });
    @endphp

                                    <td
                                        class="vertical-top h-24 min-w-[140px] border-r border-slate-200 p-1.5 text-center"
                                    >
                                        @forelse ($citasBloque as $cita)
                                            @php
                $estadoClean = strtolower(trim($cita->estado));
            @endphp
                                            <div
                                                class="p-2.5 rounded-lg text-left shadow-sm mb-1 text-xs border-l-4 transition hover:shadow-md
                {{ in_array($estadoClean, ['confirmada', 'confirmado', 'aceptada']) ? 'bg-emerald-50 text-emerald-900 border-emerald-500' : '' }}
                {{ in_array($estadoClean, ['pendiente', 'por confirmar']) ? 'bg-slate-100 text-slate-800 border-slate-400' : '' }}
                {{ $estadoClean === 'completada' ? 'bg-sky-50 text-sky-900 border-sky-500' : '' }}"
                                            >
                                                <div class="truncate font-bold">
                                                    {{ $cita->paciente->nombre ?? 'Paciente Sin Nombre' }}
                                                </div>
                                                <div
                                                    class="mt-0.5 truncate text-[11px] opacity-80"
                                                >
                                                    {{ $cita->tratamiento ?? 'Consulta General' }}
                                                </div>

                                                <div
                                                    class="mt-2 flex items-center justify-between gap-1 border-t border-black/5 pt-1"
                                                >
                                                    <span
                                                        class="font-mono text-[10px] opacity-70"
                                                    >
                                                        {{ $cita->box->nombre ?? $cita->box->numero ?? 'N/A' }}
                                                    </span>

                                                    <div
                                                        class="flex items-center gap-1"
                                                    >
                                                        @if ($cita->paciente_id)
                                                            {{-- Botón para ir a la Ficha del Paciente --}}
                                                            <a
                                                                href="{{ route('fichas.show', $cita->paciente_id) }}"
                                                                class="rounded border border-slate-200 bg-white px-1.5 py-0.5 text-[10px] font-semibold shadow-sm transition hover:bg-slate-50"
                                                                title="Ver Ficha"
                                                            >
                                                                Ficha
                                                            </a>

                                                            {{-- Botón para crear Presupuesto --}}
                                                            <a
                                                                href="{{ route('financiero.presupuestos.create', ['paciente_id' => $cita->paciente_id]) }}"
                                                                class="rounded border border-emerald-200 bg-emerald-50 px-1.5 py-0.5 text-[10px] font-semibold text-emerald-700 shadow-sm transition hover:bg-emerald-100"
                                                                title="Crear Presupuesto"
                                                            >
                                                                + Presupuesto
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>

                                        @empty
                                            <div
                                                class="flex h-full w-full items-center justify-center text-[11px] font-light italic text-slate-300"
                                            >
                                                Disponible
                                            </div>
                                        @endforelse
                                    </td>
                                @endfor
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
