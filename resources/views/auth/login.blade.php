<x-guest-layout>
    <!-- Tarjeta con sombra suave y borde elegante -->
    <div class="rounded-2xl border border-slate-100 bg-white p-8 shadow-xl">
        <!-- Header Molaris con Logo Principal Destacado -->
        <div class="mb-8 flex flex-col items-center text-center">
            <!-- Envoltorio del Logo limpio sin sombras ni resplandores de fondo -->
            <div class="relative mb-4 flex items-center justify-center">
                <div class="flex h-60 w-60 items-center justify-center p-4">
                    <img
                        src="{{ asset('img/logo.png') }}"
                        alt="Logo Molaris Dental"
                        class="max-h-full max-w-full object-contain"
                    />
                </div>
            </div>

            <!-- Textos descriptivos del encabezado -->
            <p class="text-xs font-extrabold uppercase tracking-widest text-molaris-mint">Gestión Odontológica Integral</p>
            <p class="mt-1 text-sm font-medium text-slate-400">Clínica Venedental</p>

            <!-- Separador sutil hacia el formulario de login -->
            <div
                class="mt-5 w-16 rounded-full border-b-2 border-cyan-500/30"
            ></div>
        </div>

        <!-- Estado de Sesión -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Formulario -->
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Correo Electrónico -->
            <div>
                <label
                    for="email"
                    class="mb-1 block text-xs font-medium text-slate-600"
                    >Correo Electrónico</label
                >
                <div class="relative">
                    <span
                        class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                    </span>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="usuario@venedental.cl"
                        class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-sm text-slate-800 outline-none transition focus:border-molaris-accent focus:ring-2 focus:ring-molaris-accent"
                    />
                </div>
                <x-input-error
                    :messages="$errors->get('email')"
                    class="mt-1 text-xs text-red-500"
                />
            </div>

            <!-- Contraseña -->
            <div>
                <label
                    for="password"
                    class="mb-1 block text-xs font-medium text-slate-600"
                    >Contraseña</label
                >
                <div class="relative">
                    <span
                        class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </span>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-sm text-slate-800 outline-none transition focus:border-molaris-accent focus:ring-2 focus:ring-molaris-accent"
                    />
                </div>
                <x-input-error
                    :messages="$errors->get('password')"
                    class="mt-1 text-xs text-red-500"
                />
            </div>

            <!-- Recordarme & Olvidé contraseña -->
            <div class="flex items-center justify-between pt-1 text-xs">
                <label
                    for="remember_me"
                    class="inline-flex cursor-pointer items-center"
                >
                    <input
                        id="remember_me"
                        type="checkbox"
                        name="remember"
                        class="h-3.5 w-3.5 rounded border-slate-300 text-molaris-dark focus:ring-molaris-accent"
                    />
                    <span class="ms-2 text-slate-500">Recordar sesión</span>
                </label>

                @if (Route::has('password.request'))
                    <a
                        class="font-medium text-molaris-primary transition hover:text-molaris-dark"
                        href="{{ route('password.request') }}"
                    >
                        ¿Olvidaste tu clave?
                    </a>
                @endif
            </div>

            <!-- Botón Principal Iniciar Sesión -->
            <div class="pt-2">
                <button
                    type="submit"
                    class="w-full rounded-lg bg-molaris-dark px-4 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-molaris-primary"
                >
                    Iniciar Sesión
                </button>
            </div>

            <!-- Opción para Usuarios No Registrados -->
            @if (Route::has('register'))
                <div class="border-t border-slate-100 pt-3 text-center">
                    <p class="text-xs text-slate-500">
                        ¿Aún no tienes una cuenta?
                        <a
                            href="{{ route('register') }}"
                            class="ml-1 font-semibold text-molaris-mint underline transition hover:text-emerald-600"
                        >
                            Regístrate aquí
                        </a>
                    </p>
                </div>
            @endif
        </form>

        <!-- Footer -->
        <div class="mt-6 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} Molaris - Clínica Dental Venedental.
        </div>
    </div>
</x-guest-layout>
