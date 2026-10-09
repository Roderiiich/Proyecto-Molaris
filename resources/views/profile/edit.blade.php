<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-black">
            {{ __('Mi Perfil Profesional') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-slate-50 py-8">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            @if (session('status') === 'profile-updated')
                <div
                    class="flex items-center gap-2 rounded-xl border border-emerald-600/30 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800 shadow-sm"
                >
                    <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ __('Perfil actualizado con éxito.') }}</span>
                </div>
            @endif

            {{-- CABECERA DEL PERFIL --}}
            <div
                class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
            >
                <div
                    class="h-28 w-full bg-gradient-to-r from-slate-900 via-slate-800 to-emerald-900"
                ></div>

                <div class="relative px-6 pb-6 pt-0 sm:px-8">
                    <div
                        class="flex flex-col items-center justify-between gap-6 sm:flex-row sm:items-start"
                    >
                        {{-- AVATAR Y DATOS --}}
                        <div
                            class="flex min-w-0 flex-col items-center gap-5 text-center sm:-mt-10 sm:flex-row sm:items-start sm:text-left"
                        >
                            {{-- AVATAR PRINCIPAL --}}
                            <div
                                style="
                                    width: 96px;
                                    height: 96px;
                                    min-width: 96px;
                                    min-height: 96px;
                                    overflow: hidden;
                                "
                                class="relative shrink-0 rounded-2xl border-4 border-white bg-slate-100 shadow-md"
                            >
                                @if (auth()->user()->avatar)
                                    <img
                                        src="{{ auth()->user()->avatar }}"
                                        alt="{{ auth()->user()->name }}"
                                        style="
                                            display: block;
                                            width: 100%;
                                            height: 100%;
                                            max-width: 100%;
                                            object-fit: cover;
                                            object-position: center;
                                        "
                                    />
                                @else
                                    <span
                                        class="flex h-full w-full items-center justify-center bg-emerald-100 text-2xl font-bold uppercase text-emerald-800"
                                    >
                                        {{ substr(auth()->user()->name ?? auth()->user()->nombre ?? 'U', 0, 2) }}
                                    </span>
                                @endif
                            </div>

                            {{-- CONTENEDOR DEL NOMBRE --}}
                            <div class="min-w-0 space-y-1 sm:mt-12">
                                <div
                                    class="flex flex-wrap items-center justify-center gap-2.5 sm:justify-start"
                                >
                                    <h3
                                        class="break-words text-xl font-bold text-slate-900 sm:text-2xl"
                                    >
                                        {{ auth()->user()->name ?? auth()->user()->nombre }}
                                    </h3>

                                    <span
                                        class="inline-block rounded-full px-3 py-0.5 text-xs font-bold text-white shadow-sm
                                        @if(auth()->user()->rol?->nombre === 'Administrador') bg-red-600
                                        @elseif(auth()->user()->rol?->nombre === 'Dentista') bg-blue-600
                                        @elseif(in_array(auth()->user()->rol?->nombre, ['Recepción', 'Recepcionista'])) bg-emerald-600
                                        @else bg-slate-600 @endif"
                                    >
                                        {{ auth()->user()->rol?->nombre ?? 'Sin Rol' }}
                                    </span>
                                </div>

                                <p class="break-all text-xs font-medium text-slate-500 sm:text-sm">
                                    {{ auth()->user()->email }}
                                </p>
                            </div>
                        </div>

                        {{-- INFORMACIÓN SECUNDARIA --}}
                        <div
                            class="flex items-center gap-6 border-t border-slate-100 pt-4 text-xs font-medium text-slate-500 sm:border-t-0 sm:pt-4"
                        >
                            <div>
                                <span
                                    class="block text-[10px] uppercase tracking-wider text-slate-400"
                                >
                                    Miembro Desde
                                </span>
                                <span class="text-sm font-bold text-slate-800">
                                    {{ auth()->user()->created_at?->format('M Y') ?? 'Oct 2026' }}
                                </span>
                            </div>

                            <div class="h-8 w-px bg-slate-200"></div>

                            <div>
                                <span
                                    class="block text-[10px] uppercase tracking-wider text-slate-400"
                                >
                                    Estado
                                </span>
                                <span
                                    class="inline-flex items-center gap-1.5 text-sm font-bold text-emerald-600"
                                >
                                    <span
                                        class="h-2 w-2 animate-pulse rounded-full bg-emerald-500"
                                    ></span>
                                    Activo
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CONFIGURACIÓN DEL PERFIL --}}
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                {{-- INFORMACIÓN PERSONAL --}}
                <div
                    class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
                >
                    <div
                        class="flex items-center justify-between border-b border-slate-200 bg-molaris-dark px-6 py-4 text-white"
                    >
                        <div>
                            <h4
                                class="text-sm font-bold uppercase tracking-wider"
                            >
                                {{ __('Información del Perfil') }}
                            </h4>
                            <p class="text-xs text-slate-300">
                                {{ __('Actualiza tu avatar e información básica.') }}
                            </p>
                        </div>

                        <svg class="h-5 w-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>

                    <div class="p-6">
                        <form
                            method="post"
                            action="{{ route('profile.update') }}"
                            enctype="multipart/form-data"
                            class="space-y-6"
                            x-data="{ photoPreview: null }"
                        >
                            @csrf
                            @method ('patch')

                            {{-- FOTO DE PERFIL --}}
                            <div>
                                <label
                                    class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-700"
                                >
                                    Foto de Perfil
                                </label>
                                <div class="flex items-center gap-4">
                                    {{-- PREVISUALIZACIÓN DE IMAGEN --}}
                                    <div
                                        style="
                                            width: 64px;
                                            height: 64px;
                                            min-width: 64px;
                                            min-height: 64px;
                                            overflow: hidden;
                                        "
                                        class="relative shrink-0 rounded-xl border-2 border-dashed border-slate-300 bg-slate-50"
                                    >
                                        <template x-if="photoPreview">
                                            <img
                                                :src="photoPreview"
                                                alt="Vista previa"
                                                style="
                                                    display: block;
                                                    width: 100%;
                                                    height: 100%;
                                                    max-width: 100%;
                                                    object-fit: cover;
                                                    object-position: center;
                                                "
                                            />
                                        </template>

                                        <template x-if="!photoPreview">
                                            @if (auth()->user()->avatar)
                                                <img
                                                    src="{{ auth()->user()->avatar }}"
                                                    alt="Foto actual"
                                                    style="
                                                        display: block;
                                                        width: 100%;
                                                        height: 100%;
                                                        max-width: 100%;
                                                        object-fit: cover;
                                                        object-position: center;
                                                    "
                                                />
                                            @else
                                                <div
                                                    class="flex h-full w-full items-center justify-center text-slate-400"
                                                >
                                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </template>
                                    </div>

                                    <div class="min-w-0 space-y-1">
                                        <input
                                            type="file"
                                            id="avatar"
                                            name="avatar"
                                            class="hidden"
                                            accept="image/jpeg,image/png,image/webp"
                                            x-ref="avatar"
                                            @change="
                                                const file =
                                                    $refs.avatar.files[0];
                                                if (file) {
                                                    if (
                                                        file.size >
                                                        2 * 1024 * 1024
                                                    ) {
                                                        alert(
                                                            'La imagen no debe superar los 2 MB.'
                                                        );
                                                        $refs.avatar.value = '';
                                                        photoPreview = null;
                                                        return;
                                                    }

                                                    const reader =
                                                        new FileReader();
                                                    reader.onload = (e) => {
                                                        photoPreview =
                                                            e.target.result;
                                                    };
                                                    reader.readAsDataURL(file);
                                                }
                                            "
                                        />

                                        <button
                                            type="button"
                                            @click="$refs.avatar.click()"
                                            class="rounded-lg border border-slate-300 bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-200"
                                        >
                                            {{ __('Seleccionar Imagen') }}
                                        </button>

                                        <p class="text-[11px] text-slate-400">JPG, PNG o WEBP. Máximo 2 MB.</p>
                                    </div>
                                </div>

                                <x-input-error
                                    class="mt-2"
                                    :messages="$errors->get('avatar')"
                                />
                            </div>

                            {{-- CAMPOS PERSONALES --}}
                            <div class="space-y-4">
                                <div>
                                    <x-input-label
                                        for="name"
                                        :value="__('Nombre Completo')"
                                        class="text-xs font-bold uppercase text-slate-700"
                                    />

                                    <x-text-input
                                        id="name"
                                        name="name"
                                        type="text"
                                        class="mt-1 block w-full rounded-lg border-slate-300 text-sm text-slate-900 focus:border-emerald-600 focus:ring-emerald-600"
                                        :value="old('name', $user->name ?? $user->nombre)"
                                        required
                                    />

                                    <x-input-error
                                        class="mt-1"
                                        :messages="$errors->get('name')"
                                    />
                                </div>

                                <div>
                                    <x-input-label
                                        for="email"
                                        :value="__('Correo Electrónico')"
                                        class="text-xs font-bold uppercase text-slate-700"
                                    />

                                    <x-text-input
                                        id="email"
                                        name="email"
                                        type="email"
                                        class="mt-1 block w-full rounded-lg border-slate-300 text-sm text-slate-900 focus:border-emerald-600 focus:ring-emerald-600"
                                        :value="old('email', $user->email)"
                                        required
                                    />

                                    <x-input-error
                                        class="mt-1"
                                        :messages="$errors->get('email')"
                                    />
                                </div>
                            </div>

                            <div class="flex justify-end pt-2">
                                <button
                                    type="submit"
                                    class="rounded-lg bg-emerald-600 px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-white shadow transition hover:bg-emerald-700 active:bg-emerald-800"
                                >
                                    {{ __('Guardar Cambios') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- MÓDULO ADAPTATIVO POR ROL CON RUTAS REALES --}}
                @if(auth()->user()->rol?->nombre === 'Dentista')
                    {{-- DENTISTA: FICHA Y MI AGENDA --}}
                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between border-b border-slate-200 bg-molaris-dark px-6 py-4 text-white">
                                <div>
                                    <h4 class="text-sm font-bold uppercase tracking-wider">
                                        {{ __('Ficha Profesional Odontológica') }}
                                    </h4>
                                    <p class="text-xs text-slate-300">
                                        {{ __('Acreditación clínica y accesos directos.') }}
                                    </p>
                                </div>

                                <svg class="h-5 w-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>

                            <div class="p-6 space-y-4">
                                <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
                                    @csrf
                                    @method('patch')

                                    <div>
                                        <x-input-label for="especialidad" :value="__('Especialidad Principal')" class="text-xs font-bold uppercase text-slate-700" />
                                        <x-text-input 
                                            id="especialidad" 
                                            name="especialidad" 
                                            type="text" 
                                            placeholder="Ej. Ortodoncia, Endodoncia, Odontopediatría"
                                            class="mt-1 block w-full rounded-lg border-slate-300 text-sm text-slate-900 focus:border-emerald-600 focus:ring-emerald-600" 
                                            :value="old('especialidad', $user->especialidad)" 
                                        />
                                        <x-input-error class="mt-1" :messages="$errors->get('especialidad')" />
                                    </div>

                                    <div>
                                        <x-input-label for="numero_colegiado" :value="__('N° Registro Salud / Registro Médico')" class="text-xs font-bold uppercase text-slate-700" />
                                        <x-text-input 
                                            id="numero_colegiado" 
                                            name="numero_colegiado" 
                                            type="text" 
                                            placeholder="Ej. REG-48102"
                                            class="mt-1 block w-full rounded-lg border-slate-300 text-sm text-slate-900 focus:border-emerald-600 focus:ring-emerald-600" 
                                            :value="old('numero_colegiado', $user->numero_colegiado)" 
                                        />
                                        <x-input-error class="mt-1" :messages="$errors->get('numero_colegiado')" />
                                    </div>

                                    <div class="flex justify-end pt-2">
                                        <button type="submit" class="rounded-lg bg-emerald-600 px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-white shadow transition hover:bg-emerald-700">
                                            {{ __('Guardar Ficha') }}
                                        </button>
                                    </div>
                                </form>

                                <div class="border-t border-slate-100 pt-4">
                                    <a href="{{ route('agenda.personal') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-xs font-bold uppercase text-white shadow hover:bg-blue-700 transition">
                                        <span>Ir a Mi Agenda Personal</span>
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                @elseif(auth()->user()->rol?->nombre === 'Administrador')
                    {{-- ADMINISTRADOR: ATAJOS DE USUARIOS, ROLES E INVENTARIO --}}
                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between border-b border-slate-200 bg-molaris-dark px-6 py-4 text-white">
                                <div>
                                    <h4 class="text-sm font-bold uppercase tracking-wider">
                                        {{ __('Panel de Administración') }}
                                    </h4>
                                    <p class="text-xs text-slate-300">
                                        {{ __('Gestión de personal e infraestructura.') }}
                                    </p>
                                </div>

                                <svg class="h-5 w-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                </svg>
                            </div>

                            <div class="p-6 space-y-4">
                                <div class="grid grid-cols-2 gap-3">
                                    <a href="{{ route('admin.usuarios.index') }}" class="flex flex-col justify-between rounded-xl border border-slate-200 bg-slate-50 p-4 transition hover:bg-emerald-50 hover:border-emerald-300">
                                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Usuarios & Roles</span>
                                        <span class="text-xs font-bold text-slate-800 mt-2 flex items-center justify-between">
                                            <span>Gestionar</span>
                                            <svg class="h-3.5 w-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </span>
                                    </a>

                                    <a href="{{ route('inventario.index') }}" class="flex flex-col justify-between rounded-xl border border-slate-200 bg-slate-50 p-4 transition hover:bg-emerald-50 hover:border-emerald-300">
                                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Inventario</span>
                                        <span class="text-xs font-bold text-slate-800 mt-2 flex items-center justify-between">
                                            <span>Ver Stock</span>
                                            <svg class="h-3.5 w-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </span>
                                    </a>
                                </div>

                                <div class="grid grid-cols-2 gap-3 pt-1">
                                    <a href="{{ route('doctores.index') }}" class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 p-3 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition">
                                        <span>Lista de Doctores</span>
                                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>

                                    <a href="{{ route('boxes.index') }}" class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 p-3 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition">
                                        <span>Boxes de Atención</span>
                                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                @else
                    {{-- RECEPCIÓN: AGENDA GENERAL, PACIENTES Y CAJA --}}
                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between border-b border-slate-200 bg-molaris-dark px-6 py-4 text-white">
                                <div>
                                    <h4 class="text-sm font-bold uppercase tracking-wider">
                                        {{ __('Atención & Recepción') }}
                                    </h4>
                                    <p class="text-xs text-slate-300">
                                        {{ __('Atajos de agendamiento y caja de la clínica.') }}
                                    </p>
                                </div>

                                <svg class="h-5 w-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>

                            <div class="p-6 space-y-4">
                                <a href="{{ route('agenda.general') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-xs font-bold uppercase text-white shadow hover:bg-emerald-700 transition">
                                    <span>Ir a la Agenda General</span>
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>

                                <div class="grid grid-cols-2 gap-3">
                                    <a href="{{ route('pacientes.index') }}" class="flex flex-col justify-between rounded-xl border border-slate-200 bg-slate-50 p-4 transition hover:bg-emerald-50 hover:border-emerald-300">
                                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Pacientes</span>
                                        <span class="text-xs font-bold text-slate-800 mt-2 flex items-center justify-between">
                                            <span>Directorio</span>
                                            <svg class="h-3.5 w-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </span>
                                    </a>

                                    <a href="{{ route('financiero.pagos.cierre') }}" class="flex flex-col justify-between rounded-xl border border-slate-200 bg-slate-50 p-4 transition hover:bg-emerald-50 hover:border-emerald-300">
                                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Caja Diaria</span>
                                        <span class="text-xs font-bold text-slate-800 mt-2 flex items-center justify-between">
                                            <span>Cierre de Caja</span>
                                            <svg class="h-3.5 w-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- CAMBIO DE CONTRASEÑA --}}
                <div
                    class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm lg:col-span-2"
                >
                    <div
                        class="flex items-center justify-between border-b border-slate-200 bg-molaris-dark px-6 py-4 text-white"
                    >
                        <div>
                            <h4
                                class="text-sm font-bold uppercase tracking-wider"
                            >
                                {{ __('Seguridad y Clave') }}
                            </h4>
                            <p class="text-xs text-slate-300">
                                {{ __('Actualiza tu contraseña de acceso.') }}
                            </p>
                        </div>

                        <svg class="h-5 w-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>

                    <div class="p-6">
                        <form
                            method="post"
                            action="{{ route('password.update') }}"
                            class="grid grid-cols-1 gap-4 sm:grid-cols-3"
                        >
                            @csrf
                            @method ('put')

                            <div>
                                <x-input-label
                                    for="current_password"
                                    :value="__('Contraseña Actual')"
                                    class="text-xs font-bold uppercase text-slate-700"
                                />

                                <x-text-input
                                    id="current_password"
                                    name="current_password"
                                    type="password"
                                    class="mt-1 block w-full rounded-lg border-slate-300 text-sm text-slate-900 focus:border-emerald-600 focus:ring-emerald-600"
                                />

                                <x-input-error
                                    :messages="$errors->updatePassword->get('current_password')"
                                    class="mt-1"
                                />
                            </div>

                            <div>
                                <x-input-label
                                    for="password"
                                    :value="__('Nueva Contraseña')"
                                    class="text-xs font-bold uppercase text-slate-700"
                                />

                                <x-text-input
                                    id="password"
                                    name="password"
                                    type="password"
                                    class="mt-1 block w-full rounded-lg border-slate-300 text-sm text-slate-900 focus:border-emerald-600 focus:ring-emerald-600"
                                />

                                <x-input-error
                                    :messages="$errors->updatePassword->get('password')"
                                    class="mt-1"
                                />
                            </div>

                            <div>
                                <x-input-label
                                    for="password_confirmation"
                                    :value="__('Confirmar Nueva Contraseña')"
                                    class="text-xs font-bold uppercase text-slate-700"
                                />

                                <x-text-input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    class="mt-1 block w-full rounded-lg border-slate-300 text-sm text-slate-900 focus:border-emerald-600 focus:ring-emerald-600"
                                />

                                <x-input-error
                                    :messages="$errors->updatePassword->get('password_confirmation')"
                                    class="mt-1"
                                />
                            </div>

                            <div class="sm:col-span-3 flex justify-end pt-2">
                                <button
                                    type="submit"
                                    class="rounded-lg bg-slate-800 px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-white shadow transition hover:bg-slate-900"
                                >
                                    {{ __('Actualizar Clave') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>