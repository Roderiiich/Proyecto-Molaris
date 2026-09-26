<x-guest-layout>
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-8">
        
        <!-- Header Molaris -->
        <div class="flex flex-col items-center text-center mb-6">
            <div class="w-14 h-14 bg-molaris-dark rounded-xl flex items-center justify-center shadow-md mb-3">
                <svg class="w-8 h-8 text-molaris-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-molaris-dark tracking-wide">MOLARIS</h1>
            <p class="text-xs font-semibold text-molaris-mint tracking-wider uppercase mt-0.5">Nueva Contraseña</p>
            <p class="text-xs text-slate-400 mt-0.5">Clínica Venedental</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-3.5">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Correo Electrónico -->
            <div>
                <label for="email" class="block text-xs font-medium text-slate-600 mb-1">Correo Electrónico</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                    </span>
                    <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                        placeholder="usuario@venedental.cl"
                        class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-slate-800 text-sm focus:ring-2 focus:ring-molaris-accent focus:border-molaris-accent outline-none transition">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-red-500" />
            </div>

            <!-- Nueva Contraseña -->
            <div>
                <label for="password" class="block text-xs font-medium text-slate-600 mb-1">Nueva Contraseña</label>
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
                <label for="password_confirmation" class="block text-xs font-medium text-slate-600 mb-1">Confirmar Nueva Contraseña</label>
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

            <div class="pt-3">
                <button type="submit" class="w-full py-2.5 px-4 bg-molaris-mint hover:bg-emerald-600 text-white font-semibold text-sm rounded-lg shadow-md transition">
                    Restablecer Contraseña
                </button>
            </div>
        </form>

        <div class="mt-6 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} Molaris - Clínica Dental Venedental.
        </div>

    </div>
</x-guest-layout>