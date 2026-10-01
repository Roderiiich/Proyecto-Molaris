<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-black leading-tight text-slate-800">
                    {{ __('Gestión de Boxes de Atención') }}
                </h2>
                <p class="mt-1 text-sm font-medium text-slate-500">Administración de disponibilidad y estado operativo de pabellones y boxes</p>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-100/70 py-8">
        <div class="mx-auto max-w-7xl space-y-8 sm:px-6 lg:px-8">
            <!-- Alertas de Estado -->
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
                    <p class="mb-1 text-base font-extrabold">Error en la operación:</p>
                    <ul class="list-inside list-disc space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <!-- RESUMEN DE ESTADO DE BOXES (1 FILA FORZADA) -->
            <div
                style="
                    display: flex;
                    gap: 1rem;
                    width: 100%;
                    margin-bottom: 1.5rem;
                "
            >
                <!-- Tarjeta: Disponibles -->
                <div
                    style="flex: 1; min-width: 0"
                    class="shadow-xs flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-5"
                >
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Disponibles</p>
                        <h3
                            class="mt-0.5 text-2xl font-extrabold text-slate-800"
                        >
                            {{ $boxes->where('estado', 'Disponible')->count() }}
                        </h3>
                        <span
                            class="mt-1 flex items-center gap-1 text-[11px] font-semibold text-emerald-600"
                        >
                            <span
                                class="h-2 w-2 rounded-full bg-emerald-500"
                            ></span>
                            Listos para atención
                        </span>
                    </div>
                    <div
                        class="shadow-xs flex shrink-0 items-center justify-center bg-emerald-50 text-emerald-600"
                        style="
                            width: 44px;
                            height: 44px;
                            min-width: 44px;
                            min-height: 44px;
                            border-radius: 9999px;
                            aspect-ratio: 1 / 1;
                        "
                    >
                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            style="display: block"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>

                <!-- Tarjeta: Mantenimiento -->
                <div
                    style="flex: 1; min-width: 0"
                    class="shadow-xs flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-5"
                >
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">En Mantenimiento</p>
                        <h3
                            class="mt-0.5 text-2xl font-extrabold text-slate-800"
                        >
                            {{ $boxes->where('estado', 'Mantenimiento')->count() }}
                        </h3>
                        <span
                            class="mt-1 flex items-center gap-1 text-[11px] font-semibold text-amber-600"
                        >
                            <span
                                class="h-2 w-2 rounded-full bg-amber-500"
                            ></span>
                            En revisión técnica
                        </span>
                    </div>
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-amber-50 text-amber-600"
                        style="
                            width: 44px;
                            height: 44px;
                            min-width: 44px;
                            min-height: 44px;
                            border-radius: 9999px;
                            aspect-ratio: 1 / 1;
                        "
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </div>
                </div>

                <!-- Tarjeta: Inactivos -->
                <div
                    style="flex: 1; min-width: 0"
                    class="shadow-xs flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-5"
                >
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Inactivos</p>
                        <h3
                            class="mt-0.5 text-2xl font-extrabold text-slate-800"
                        >
                            {{ $boxes->where('estado', 'Inactivo')->count() }}
                        </h3>
                        <span
                            class="mt-1 flex items-center gap-1 text-[11px] font-semibold text-rose-600"
                        >
                            <span
                                class="h-2 w-2 rounded-full bg-rose-500"
                            ></span>
                            Fuera de servicio
                        </span>
                    </div>
                    <div
                        class="bg-red flex h-11 w-11 shrink-0 items-center justify-center rounded-full text-rose-600"
                        style="
                            width: 44px;
                            height: 44px;
                            min-width: 44px;
                            min-height: 44px;
                            border-radius: 9999px;
                            aspect-ratio: 1 / 1;
                            background-color: #f35757; /* Fondo rojo claro */
                        "
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Formulario de Registro de Nuevo Box -->
            <div
                class="relative overflow-hidden rounded-3xl border-2 border-cyan-100 bg-white p-6 shadow-md sm:p-8"
            >
                

                <div
                    class="mb-6 flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 pb-4"
                >
                    <div class="flex items-center gap-3.5">
                        <!-- Ícono SVG para la cabecera con visibilidad garantizada -->
                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl shadow-md shadow-cyan-500/20"
                            style="
                                background: linear-gradient(
                                    135deg,
                                    #06b6d4 0%,
                                    #2563eb 100%
                                );
                                border: 1px solid #a5f3fc;
                                margin-right: 15px;
                            "
                        >
                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0h4m-4 0H9" />
                            </svg>
                        </div>
                        <div>
                            <h3
                                class="text-xl font-black tracking-tight text-slate-800"
                            >
                                Registrar Nuevo Box
                            </h3>
                            <p class="mt-0.5 text-sm font-medium text-slate-500">Agrega una nueva sala o box de atención clínica al sistema.</p>
                        </div>
                    </div>

                    <span
                        class="rounded-full border border-cyan-200/80 bg-cyan-50 px-3.5 py-1.5 text-xs font-extrabold uppercase tracking-wider text-cyan-700"
                    >
                        Infraestructura
                    </span>
                </div>

                <form action="{{ route('boxes.store') }}" method="POST">
                    @csrf
                    <div
                        class="grid grid-cols-1 gap-6 rounded-2xl border border-slate-200/80 bg-slate-50/80 p-5 md:grid-cols-2"
                    >
                        <!-- Nombre del Box -->
                        <div>
                            <label
                                for="nombre"
                                class="mb-2 block text-sm font-bold text-slate-800"
                            >
                                Nombre o Identificador del Box
                                <span class="text-cyan-600">*</span>
                            </label>
                            <input
                                type="text"
                                name="nombre"
                                id="nombre"
                                value="{{ old('nombre') }}"
                                placeholder="Ej: Box 3 - Implantología"
                                required
                                class="shadow-xs w-full rounded-xl border-2 border-slate-200 bg-white px-3.5 py-2.5 text-sm font-semibold text-slate-800 transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200"
                            />
                        </div>

                        <!-- Estado Inicial -->
                        <div>
                            <label
                                for="estado"
                                class="mb-2 block text-sm font-bold text-slate-800"
                            >
                                Estado Inicial
                                <span class="text-cyan-600">*</span>
                            </label>
                            <select
                                name="estado"
                                id="estado"
                                required
                                class="shadow-xs w-full rounded-xl border-2 border-slate-200 bg-white px-3.5 py-2.5 text-sm font-semibold text-slate-800 transition focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200"
                            >
                                <option value="Disponible">Disponible</option>
                                <option value="Mantenimiento">
                                    Mantenimiento
                                </option>
                                <option value="Inactivo">Inactivo</option>
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
                            <span>Crear Box</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Listado y Control de Estado de Boxes -->
            <div
                class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm sm:p-8"
            >
                <div
                    class="mb-6 flex items-center justify-between border-b border-slate-100 pb-3"
                >
                    <div>
                        <h3 class="text-xl font-black text-slate-800">
                            Estado de los Boxes de Atención
                        </h3>
                        <p class="mt-0.5 text-sm font-medium text-slate-500">Gestión en tiempo real de operatividad y asignación.</p>
                    </div>
                </div>

                <div
                    class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3"
                >
                    @forelse ($boxes as $box)
                        <div
                            class="shadow-xs relative flex flex-col justify-between space-y-4 rounded-2xl border border-slate-200/90 bg-slate-50/60 p-5 transition hover:border-cyan-300"
                        >
                            <div>
                                <!-- Nombre del Box y Botón de Eliminar esquinado -->
                                <div
                                    class="mb-2 flex items-start justify-between gap-2"
                                >
                                    <h4
                                        class="text-lg font-black leading-snug text-slate-900"
                                    >
                                        {{ $box->nombre }}
                                    </h4>

                                    <!-- Botón de Eliminar compacto y esquinado en rojo -->
                                    <form
                                        action="{{ route('boxes.destroy', $box->id) }}"
                                        method="POST"
                                        onsubmit="
                                            return confirm(
                                                '¿Estás seguro de que deseas eliminar este box?'
                                            );
                                        "
                                        class="shrink-0"
                                    >
                                        @csrf
                                        @method ('DELETE')
                                        <button
                                            type="submit"
                                            title="Eliminar Box"
                                            class="shadow-xs inline-flex items-center gap-1 rounded-lg border border-red-200 bg-red-50 px-2 py-1 text-[11px] font-extrabold text-red-600 transition hover:bg-red-600 hover:text-white"
                                        >
                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            <span>Eliminar</span>
                                        </button>
                                    </form>
                                </div>

                                <!-- Badge de Estado -->
                                <div class="mb-2">
                                    @if ($box->estado == 'Disponible')
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800"
                                        >
                                            <span
                                                class="h-2 w-2 rounded-full bg-emerald-500"
                                            ></span>
                                            Disponible
                                        </span>
                                    @elseif ($box->estado == 'Mantenimiento')
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-100 px-3 py-1 text-xs font-bold text-amber-800"
                                        >
                                            <span
                                                class="h-2 w-2 rounded-full bg-amber-500"
                                            ></span>
                                            Mantenimiento
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full border border-rose-200 bg-rose-100 px-3 py-1 text-xs font-bold text-rose-800"
                                        >
                                            <span
                                                class="h-2 w-2 rounded-full bg-rose-500"
                                            ></span>
                                            Inactivo
                                        </span>
                                    @endif
                                </div>

                                <p class="mt-1 text-xs font-semibold text-slate-400">
                                    Última actualización: {{ $box->updated_at ? $box->updated_at->diffForHumans() : 'Sin registro' }}
                                </p>
                            </div>

                            <!-- Cambiar Estado Rápido -->
                            <div class="border-t border-slate-200/80 pt-3">
                                <form
                                    action="{{ route('boxes.updateEstado', $box->id) }}"
                                    method="POST"
                                >
                                    @csrf
                                    @method ('PATCH')
                                    <label
                                        for="estado_{{ $box->id }}"
                                        class="mb-1.5 block text-xs font-bold text-slate-600"
                                        >Cambiar Estado:</label
                                    >
                                    <div class="flex items-center gap-2">
                                        <select
                                            name="estado"
                                            id="estado_{{ $box->id }}"
                                            class="shadow-xs w-full rounded-xl border-2 border-slate-200 bg-white py-1.5 text-xs font-semibold text-slate-800 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200"
                                        >
                                            <option
                                                value="Disponible"
                                                {{ $box->estado == 'Disponible' ? 'selected' : '' }}
                                            >
                                                Disponible
                                            </option>
                                            <option
                                                value="Mantenimiento"
                                                {{ $box->estado == 'Mantenimiento' ? 'selected' : '' }}
                                            >
                                                Mantenimiento
                                            </option>
                                            <option
                                                value="Inactivo"
                                                {{ $box->estado == 'Inactivo' ? 'selected' : '' }}
                                            >
                                                Inactivo
                                            </option>
                                        </select>
                                        <button
                                            type="submit"
                                            class="shadow-xs shrink-0 rounded-xl bg-slate-800 px-3 py-2 text-xs font-bold text-white transition hover:bg-slate-900"
                                        >
                                            Actualizar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div
                            class="col-span-full rounded-2xl border border-dashed border-slate-200 bg-slate-50/50 py-12 text-center text-sm font-bold text-slate-400"
                        >
                            No hay boxes configurados actualmente.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
