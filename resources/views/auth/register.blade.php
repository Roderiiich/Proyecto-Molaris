<x-guest-layout>
    <!-- Tarjeta centrada con sombra suave -->
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-8">
        
        <!-- Header Molaris con Logo Oficial -->
        <div class="flex flex-col items-center text-center mb-6">
            <div class="w-20 h-20 mb-3 flex items-center justify-center">
                <img src="{{ asset('img/logo.png') }}" alt="Logo Molaris" class="max-h-full max-w-full object-contain">
            </div>
            
            <p class="text-xs font-semibold text-molaris-mint tracking-wider uppercase mt-0.5">Crear Nueva Cuenta</p>
            <p class="text-xs text-slate-400 mt-0.5">Clínica Venedental</p>
        </div>

        <!-- Formulario -->
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Nombre Completo -->
            <div>
                <label for="name" class="block text-xs font-medium text-slate-600 mb-1">Nombre Completo</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </span>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                        placeholder="Ej: Juan Pérez"
                        class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-slate-800 text-sm focus:ring-2 focus:ring-molaris-accent focus:border-molaris-accent outline-none transition">
                </div>
                <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs text-red-500" />
            </div>

            <!-- Correo Electrónico -->
            <div>
                <label for="email" class="block text-xs font-medium text-slate-600 mb-1">Correo Electrónico</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                    </span>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                        placeholder="usuario@venedental.cl"
                        class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-slate-800 text-sm focus:ring-2 focus:ring-molaris-accent focus:border-molaris-accent outline-none transition">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-red-500" />
            </div>

            <!-- Contraseña -->
            <div>
                <label for="password" class="block text-xs font-medium text-slate-600 mb-1">Contraseña</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </span>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        placeholder="••••••••"
                        class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-slate-800 text-sm focus:ring-2 focus:ring-molaris-accent focus:border-molaris-accent outline-none transition">
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-red-500" />
            </div>

            <!-- Confirmar Contraseña -->
            <div>
                <label for="password_confirmation" class="block text-xs font-medium text-slate-600 mb-1">Confirmar Contraseña</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </span>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                        placeholder="••••••••"
                        class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-slate-800 text-sm focus:ring-2 focus:ring-molaris-accent focus:border-molaris-accent outline-none transition">
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs text-red-500" />
            </div>

            <!-- Botón de Registro -->
            <div class="pt-2">
                <button type="submit" class="w-full py-2.5 px-4 bg-molaris-dark hover:bg-molaris-primary text-white font-semibold text-sm rounded-lg shadow-md transition">
                    Registrar Cuenta
                </button>
            </div>

            <!-- Opción para Usuarios que ya tienen cuenta -->
            <div class="pt-3 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-500">
                    ¿Ya tienes una cuenta? 
                    <a href="{{ route('login') }}" class="font-semibold text-molaris-primary hover:text-molaris-dark transition underline ml-1">
                        Inicia sesión aquí
                    </a>
                </p>
            </div>
        </form>

        <!-- Footer -->
        <div class="mt-6 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} Molaris - Clínica Dental Venedental.
        </div>

    </div>
</x-guest-layout>