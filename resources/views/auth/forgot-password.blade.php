<x-guest-layout>
    <!-- Tarjeta centrada con sombra suave -->
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-8">
        
        <!-- Header Molaris -->
        <div class="flex flex-col items-center text-center mb-6">
            <div class="w-14 h-14 bg-molaris-dark rounded-xl flex items-center justify-center shadow-md mb-3">
                <svg class="w-8 h-8 text-molaris-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-molaris-dark tracking-wide">MOLARIS</h1>
            <p class="text-xs font-semibold text-molaris-primary tracking-wider uppercase mt-0.5">Recuperar Contraseña</p>
            <p class="text-xs text-slate-400 mt-0.5">Clínica Venedental</p>
        </div>

        <!-- Mensaje Informativo -->
        <div class="mb-5 text-xs text-slate-600 bg-slate-50 p-3.5 rounded-lg border border-slate-200 leading-relaxed">
            {{ __('¿Olvidaste tu contraseña? No hay problema. Ingresa tu correo electrónico y te enviaremos un enlace seguro para restablecerla.') }}
        </div>

        <!-- Estado de Sesión (Confirmación de envío de correo) -->
        <x-auth-session-status class="mb-4 text-xs font-medium text-emerald-600 bg-emerald-50 p-3 rounded-lg border border-emerald-200" :status="session('status')" />

        <!-- Formulario -->
        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <!-- Correo Electrónico -->
            <div>
                <label for="email" class="block text-xs font-medium text-slate-600 mb-1">Correo Electrónico</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                    </span>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        placeholder="usuario@venedental.cl"
                        class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-slate-800 text-sm focus:ring-2 focus:ring-molaris-accent focus:border-molaris-accent outline-none transition">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-red-500" />
            </div>

            <!-- Botones -->
            <div class="pt-2 space-y-3">
                <button type="submit" class="w-full py-2.5 px-4 bg-molaris-dark hover:bg-molaris-primary text-white font-semibold text-sm rounded-lg shadow-md transition">
                    Enviar Enlace de Recuperación
                </button>

                <div class="text-center">
                    <a class="text-xs text-slate-500 hover:text-molaris-dark font-medium transition" href="{{ route('login') }}">
                        Volver al <span class="text-molaris-primary underline">Inicio de Sesión</span>
                    </a>
                </div>
            </div>
        </form>

        <!-- Footer -->
        <div class="mt-6 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} Molaris - Clínica Dental Venedental.
        </div>

    </div>
</x-guest-layout>