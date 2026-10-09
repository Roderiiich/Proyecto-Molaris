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
                {{-- Banner Superior con colores estándar de Tailwind (Garantizado que Render los renderiza) --}}
                <div
                    class="h-32 w-full bg-gradient-to-r from-slate-900 via-slate-800 to-emerald-900"
                ></div>

                <div class="relative px-6 pb-6 sm:px-8">
                    <div
                        class="flex flex-col items-center justify-between gap-6 sm:flex-row sm:items-end"
                    >
                        {{-- AVATAR Y NOMBRE CON ESPACIADO CÓMODO --}}
                        <div
                            class="flex min-w-0 flex-col items-center gap-5 text-center sm:-mt-12 sm:flex-row sm:items-end sm:text-left"
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

                            {{-- CONTENEDOR DEL NOMBRE: Con padding superior (pt-2 sm:pt-4) para dar respiración al texto --}}
                            <div class="min-w-0 space-y-1 pb-1 pt-2 sm:pt-4">
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
                            class="flex items-center gap-6 border-t border-slate-100 pt-4 text-xs font-medium text-slate-500 sm:border-t-0 sm:pb-1 sm:pt-0"
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

                {{-- CAMBIO DE CONTRASEÑA --}}
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
                            class="space-y-4"
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

                            <div class="pt-4">
                                <button
                                    type="submit"
                                    class="w-full rounded-lg bg-slate-800 px-4 py-2.5 text-xs font-bold uppercase tracking-wider text-white shadow transition hover:bg-slate-900"
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
