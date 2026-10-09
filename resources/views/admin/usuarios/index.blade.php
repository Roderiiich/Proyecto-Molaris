<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-black">
            {{ __('Gestión de Roles y Usuarios') }}
        </h2>
    </x-slot>

    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        
        @if (session('success'))
            <div class="mb-4 rounded-lg border border-emerald-600/50 bg-emerald-950/50 p-4 text-sm text-emerald-300">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-lg">
            <!-- Encabezado de la Tarjeta -->
            <div class="flex items-center justify-between border-b border-slate-200 bg-molaris-dark p-6 text-white">
                <h3 class="text-lg font-bold">Usuarios Registrados en la Plataforma</h3>
                <span class="text-xs text-slate-300">Total: {{ $usuarios->count() }} usuarios</span>
            </div>

            <!-- Tabla de Usuarios -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-800">
                    <thead class="border-b border-slate-200 bg-slate-100 text-xs uppercase text-slate-600">
                        <tr>
                            <th class="px-6 py-3 font-bold">Nombre</th>
                            <th class="px-6 py-3 font-bold">Correo Electrónico</th>
                            <th class="px-6 py-3 font-bold">Rol Actual</th>
                            <th class="px-6 py-3 text-center font-bold">Asignar Nuevo Rol</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @foreach ($usuarios as $usuario)
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-6 py-4 font-semibold text-slate-900">
                                    {{ $usuario->name }}
                                </td>
                                <td class="px-6 py-4 text-slate-600">
                                    {{ $usuario->email }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block rounded-full px-3 py-1 text-xs font-bold text-white shadow-sm
                                        @if($usuario->rol?->nombre === 'Administrador') bg-red-600
                                        @elseif($usuario->rol?->nombre === 'Dentista') bg-molaris-dark
                                        @elseif(in_array($usuario->rol?->nombre, ['Recepción', 'Recepcionista'])) bg-emerald-600
                                        @else bg-slate-500 @endif">
                                        {{ $usuario->rol?->nombre ?? 'Sin Rol Asignado' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('admin.usuarios.update-rol', $usuario->id) }}" method="POST" class="flex items-center justify-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        
                                        <select name="rol_id" class="rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-800 shadow-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600">
                                            @foreach ($roles as $rol)
                                                <option value="{{ $rol->id }}" {{ $usuario->rol_id == $rol->id ? 'selected' : '' }}>
                                                    {{ $rol->nombre }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <button type="submit" class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white shadow transition hover:bg-emerald-700 active:bg-emerald-800">
                                            Guardar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>