<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Cita - Molaris</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen p-4">

    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl border border-slate-200 p-6 text-center">
        
        <!-- LOGO DE MOLARIS -->
        <div class="flex flex-col items-center justify-center mb-6">
            <img 
                src="{{ asset('img/logo.png') }}" 
                alt="Molaris Clínica Dental" 
                class="h-30 w-auto object-contain mb-2"
            >
            <p class="text-slate-500 text-xs font-semibold tracking-wide uppercase">Cita Médica Agendada</p>
        </div>

        {{-- Alerta de respuesta reciente --}}
        @if (session('status'))
            <div class="p-4 rounded-xl mb-5 text-sm font-medium {{ session('estado_actual') === 'confirmada' ? 'bg-emerald-500 text-white border border-emerald-200' : 'bg-red-500 text-white border border-rose-200' }}">
                {{ session('status') }}
            </div>
        @endif

        {{-- Mensajes según el estado guardado --}}
        @if ($cita->estado === 'confirmada' && !session('status'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl mb-6 text-sm font-medium">
                ✓ Esta cita ya se encuentra <strong>CONFIRMADA</strong>.
            </div>
        @elseif ($cita->estado === 'rechazada' && !session('status'))
            <div class="bg-red-500 border border-rose-200 text-white p-4 rounded-xl mb-6 text-sm font-medium">
                ✕ Esta cita fue <strong>RECHAZADA </strong>.
            </div>
        @endif

        {{-- Tarjeta con el Resumen de la Cita --}}
        <div class="bg-slate-50 rounded-xl p-4 text-left border border-slate-200/80 mb-6 space-y-2.5 text-sm text-slate-600">
            <p><strong>Paciente:</strong> {{ $cita->paciente->nombre }} {{ $cita->paciente->apellido }}</p>
            <p>
                <strong>Fecha y Hora:</strong> 
                {{ $cita->fecha_hora ? \Carbon\Carbon::parse($cita->fecha_hora)->locale('es')->translatedFormat('l d \d\e F \a \l\a\s H:i \h\r\s') : 'Por definir' }}
            </p>
            <p><strong>Especialista:</strong> {{ $cita->doctor->usuario->name ?? $cita->doctor->usuario->nombre ?? 'Especialista' }}</p>
            <p><strong>Box:</strong> {{ $cita->box->nombre ?? 'Por asignar' }}</p>
        </div>

        {{-- Botones de Acción: Verde (Confirmar) y Rojo (Rechazar) --}}
        @if (!in_array($cita->estado, ['confirmada', 'rechazada']))
    <form action="{{ request()->fullUrl() }}" method="POST" class="flex flex-col items-center space-y-3">
        @csrf
        
        <!-- Botón Confirmar (Verde) -->
        <button 
            type="submit" 
            name="accion" 
            value="confirmar"
            class="w-full max-w-xs mx-auto bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-2.5 px-4 rounded-xl transition-colors duration-200 shadow-sm cursor-pointer text-sm flex items-center justify-center gap-2"
        >
            ✓ Confirmar Asistencia
        </button>

        <!-- Botón Rechazar (Rojo) -->
        <button 
            type="submit" 
            name="accion" 
            value="rechazar"
            onclick="return confirm('¿Seguro que deseas rechazar/cancelar esta cita?');"
            class="w-full max-w-xs mx-auto bg-red-600 hover:bg-red-500 text-white font-bold py-2.5 px-4 rounded-xl transition-colors duration-200 shadow-sm cursor-pointer text-sm flex items-center justify-center gap-2"
        >
            ✕ No podré asistir (Rechazar)
        </button>
    </form>
@endif

    </div>

</body>
</html>