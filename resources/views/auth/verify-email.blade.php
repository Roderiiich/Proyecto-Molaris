<x-guest-layout>
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-8">
        
        <!-- Header Molaris -->
        <div class="flex flex-col items-center text-center mb-6">
            <div class="w-14 h-14 bg-molaris-dark rounded-xl flex items-center justify-center shadow-md mb-3">
                <svg class="w-8 h-8 text-molaris-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-molaris-dark tracking-wide">MOLARIS</h1>
            <p class="text-xs font-semibold text-molaris-primary tracking-wider uppercase mt-0.5">Verificación de Cuenta</p>
            <p class="text-xs text-slate-400 mt-0.5">Clínica Venedental</p>
        </div>

        <div class="mb-5 text-xs text-slate-600 bg-slate-50 p-3.5 rounded-lg border border-slate-200 leading-relaxed">
            {{ __('¡Gracias por registrarte! Antes de comenzar, por favor verifica tu dirección de correo haciendo clic en el enlace que te acabamos de enviar. Si no lo recibiste, con gusto te enviaremos otro.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-xs text-emerald-600 bg-emerald-50 p-3 rounded-lg border border-emerald-200">
                {{ __('Un nuevo enlace de verificación ha sido enviado al correo electrónico que proporcionaste durante el registro.') }}
            </div>
        @endif

        <div class="mt-4 flex flex-col space-y-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full py-2.5 px-4 bg-molaris-dark hover:bg-molaris-primary text-white font-semibold text-sm rounded-lg shadow-md transition">
                    Reenviar Correo de Verificación
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="text-center">
                @csrf
                <button type="submit" class="text-xs text-slate-500 hover:text-molaris-dark font-medium underline transition">
                    Cerrar Sesión
                </button>
            </form>
        </div>

        <div class="mt-6 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} Molaris - Clínica Dental Venedental.
        </div>

    </div>
</x-guest-layout>