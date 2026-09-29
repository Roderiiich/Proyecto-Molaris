<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Listado de Pacientes') }}
            </h2>
            <a
                href="{{ route('pacientes.create') }}"
                class="inline-flex items-center rounded-lg bg-molaris-dark px-5 py-2.5 font-bold text-white shadow-md transition duration-200 ease-in-out hover:bg-black hover:-translate-y-0.5"
            >
                + Nuevo Paciente
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            @if (session('success'))
                <div
                    class="relative mb-4 rounded border border-green-400 bg-green-100 px-4 py-3 text-green-700"
                >
                    {{ session('success') }}
                </div>
            @endif

            <!-- BARRA SUPERIOR: BUSCADOR + CONTADOR -->
            <div
                class="mb-6 flex flex-col items-center justify-between gap-4 rounded-xl border border-gray-100 bg-white p-4 shadow-sm sm:flex-row"
            >
                <!-- Buscador -->
                <form
                    action="{{ route('pacientes.index') }}"
                    method="GET"
                    class="flex w-full flex-1 flex-wrap items-center gap-3 sm:w-auto"
                >
                    <div class="relative min-w-[260px] flex-1">
                        <div
                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search', $buscar ?? '') }}"
                            placeholder="Buscar por Nombre o RUT..."
                            style="padding-left: 42px !important"
                            class="w-full rounded-lg border border-slate-300 bg-slate-50 py-2 pr-4 text-sm text-slate-800 placeholder-slate-400 outline-none transition focus:ring-2 focus:ring-cyan-500"
                        />
                    </div>

                    <button
                        type="submit"
                        class="h-[38px] shrink-0 rounded-lg bg-slate-800 px-5 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-slate-900"
                    >
                        Buscar
                    </button>

                    @if (request('search') || !empty($buscar))
                        <a
                            href="{{ route('pacientes.index') }}"
                            class="flex h-[38px] shrink-0 items-center rounded-lg border border-slate-200 bg-slate-100 px-3.5 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-200"
                        >
                            Limpiar
                        </a>
                    @endif

                    <!-- Botón Exportar -->
                    <a
                        href="{{ route('pacientes.exportar') }}"
                        class="flex h-[38px] shrink-0 items-center gap-1.5 rounded-lg border border-emerald-200 bg-emerald-50 px-3.5 py-2 text-xs font-medium text-emerald-700 transition hover:bg-emerald-100"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Exportar
                    </a>
                </form>

                <!-- Contador de Pacientes -->
                <div
                    class="shrink-0 rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-500"
                >
                    Pacientes encontrados:
                    <span class="font-bold text-slate-800">
                        {{ method_exists($pacientes, 'total') ? $pacientes->total() : $pacientes->count() }}
                    </span>
                </div>
            </div>

            <!-- TABLA DE PACIENTES -->
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500"
                            >
                                RUT
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500"
                            >
                                NOMBRE
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500"
                            >
                                TELÉFONO
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500"
                            >
                                CORREO
                            </th>
                            <th
                                class="px-6 py-3 text-center text-xs font-medium uppercase text-gray-500"
                            >
                                ACCIONES
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($pacientes as $paciente)
                            <tr>
                                <td
                                    class="whitespace-nowrap px-6 py-4 text-sm text-gray-900"
                                >
                                    {{ $paciente->rut }}
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 text-sm text-gray-900"
                                >
                                    {{ $paciente->nombre }}
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 text-sm text-gray-900"
                                >
                                    {{ $paciente->telefono }}
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 text-sm text-gray-900"
                                >
                                    {{ $paciente->correo }}
                                </td>
                                <td
                                    class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium"
                                >
                                    <div
                                        class="flex items-center justify-end gap-2"
                                    >
                                        <!-- Botón Editar -->
                                        <a
                                            href="{{ route('pacientes.edit', $paciente->id) }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg border border-amber-200 px-3 py-1.5 text-xs font-semibold shadow-sm transition hover:-translate-y-0.5"
                                            style="
                                                background-color: #fffbeb;
                                                color: #92400e;
                                            "
                                        >
                                            <svg class="h-3.5 w-3.5" style="
                                                    color: #d97706;
                                                " fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Editar
                                        </a>

                                        <!-- Botón Ficha Clínica (Azul Oscuro consistente con + Nuevo Paciente) -->
                                        <a
                                            href="{{ route('pacientes.ficha', $paciente->id) }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-bold shadow-sm transition hover:bg-black hover:-translate-y-0.5"
                                            style="
                                                background-color: #0f2d4a;
                                                color: #ffffff;
                                            "
                                        >
                                            <svg class="h-3.5 w-3.5 " style="
                                                    color: #ffffff;
                                                " fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <span>Ficha Clínica</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="5"
                                    class="px-6 py-8 text-center text-sm text-slate-500"
                                >
                                    No se encontraron pacientes registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- ENLACES DE PAGINACIÓN -->
            @if (method_exists($pacientes, 'links'))
                <div class="mt-4">{{ $pacientes->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
