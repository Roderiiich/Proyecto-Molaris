<x-guest-layout>
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-8">
        
        <!-- Header Molaris -->
        <div class="flex flex-col items-center text-center mb-6">
            <div class="w-14 h-14 bg-molaris-dark rounded-xl flex items-center justify-center shadow-md mb-3">
                <svg class="w-8 h-8 text-molaris-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-molaris-dark tracking-wide">MOLARIS</h1>
            <p class="text-xs font-semibold text-molaris-primary tracking-wider uppercase mt-0.5">Área Segura</p>
            <p class="text-xs text-slate-400 mt-0.5">Clínica Venedental</p>
        </div>

        <div class="mb-5 text-xs text-slate-600 bg-amber-50 p-3.5 rounded-lg border border-amber-200 leading-relaxed">
            {{ __('Esta es una zona segura de la aplicación. Por favor, confirma tu contraseña antes de continuar.') }}
        </div>

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
            @csrf

            <!-- Contraseña -->
            <div>
                <label for="password" class="block text-xs font-medium text-slate-600 mb-1">Contraseña</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </span>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        placeholder="••••••••"
                        class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-slate-800 text-sm focus:ring-2 focus:ring-molaris-accent focus:border-molaris-accent outline-none transition">
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-red-500" />
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-2.5 px-4 bg-molaris-dark hover:bg-molaris-primary text-white font-semibold text-sm rounded-lg shadow-md transition">
                    Confirmar Contraseña
                </button>
            </div>
        </form>

        <div class="mt-6 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} Molaris - Clínica Dental Venedental.
        </div>

    </div>
</x-guest-layout>