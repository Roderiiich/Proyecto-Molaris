<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Ficha Clínica y Atención - MOLARIS</title>
    <style>
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            color: #1e293b; 
            font-size: 11px; 
            line-height: 1.4;
            margin: 15px; 
        }

        /* ENCABEZADO CENTRADO */
        .header { 
            text-align: center; 
            border-bottom: 2px solid #0f2d4a; 
            padding-bottom: 12px; 
            margin-bottom: 15px; 
        }
        .header img { 
            width: 120px; 
            height: auto; 
            margin-bottom: 4px; 
        }
        .header h1 { 
            color: #0f2d4a; 
            margin: 0; 
            font-size: 18px; 
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .header .subtitle { 
            color: #0284c7; 
            font-size: 11px; 
            font-weight: bold; 
            text-transform: uppercase; 
            margin: 2px 0 0 0;
            letter-spacing: 1px;
        }
        .header .doc-title { 
            color: #64748b; 
            font-size: 10px; 
            margin-top: 4px; 
            font-weight: 500;
        }

        /* ESTILOS DE CAJAS Y TABLAS */
        .box { 
            background: #ffffff; 
            border: 1px solid #cbd5e1; 
            padding: 10px 12px; 
            border-radius: 6px; 
            margin-bottom: 12px; 
        }
        .box-title { 
            font-weight: bold; 
            color: #0f2d4a; 
            text-transform: uppercase; 
            font-size: 10px; 
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 4px;
            margin-bottom: 8px; 
        }

        /* ALERTAS MÉRICAS (CAJA DESTACADA) */
        .box-alertas {
            background-color: #fef2f2;
            border: 1px solid #fca5a5;
            color: #991b1b;
            padding: 8px 12px;
            border-radius: 6px;
            margin-bottom: 12px;
        }
        .box-alertas .title-alerta {
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            margin-bottom: 4px;
            color: #991b1b;
        }

        /* TABLA DE DATOS DEL PACIENTE */
        .patient-table {
            width: 100%;
            border-collapse: collapse;
        }
        .patient-table td {
            padding: 3px 0;
            vertical-align: top;
        }

        /* CONTENIDO DE LA ATENCIÓN */
        .content-p {
            margin: 0;
            font-size: 11px;
            color: #334155;
            white-space: pre-line;
        }

        /* FIRMA Y TIMBRE DEL PROFESIONAL */
        .signature-container {
            margin-top: 50px;
            width: 100%;
            text-align: center;
        }
        .signature-box {
            display: inline-block;
            width: 220px;
            border-top: 1px solid #0f2d4a;
            padding-top: 6px;
        }
        .signature-title {
            font-size: 10px;
            font-weight: bold;
            color: #0f2d4a;
            text-transform: uppercase;
            margin: 0;
        }
        .signature-sub {
            font-size: 9px;
            color: #64748b;
            margin-top: 2px;
        }

        /* PIE DE PÁGINA */
        .footer { 
            margin-top: 30px; 
            text-align: center; 
            font-size: 9px; 
            color: #94a3b8; 
            border-top: 1px dashed #e2e8f0;
            padding-top: 8px;
        }
    </style>
</head>
<body>

    <!-- 1. ENCABEZADO CENTRADO CON LOGO Y SUBTÍTULO -->
    <div class="header">
        <img src="{{ public_path('img/logo-sinfondo.png') }}" alt="MOLARIS Logo">
        <h1>MOLARIS</h1>
        <div class="subtitle">Software Dental</div>
        <div class="doc-title">REGISTRO DE ATENCIÓN Y FICHA CLÍNICA</div>
    </div>

    <!-- 2. DATOS DEL PACIENTE Y ATENCIÓN -->
    <div class="box">
        <div class="box-title">Información del Paciente</div>
        <table class="patient-table">
            <tr>
                <td style="width: 50%;"><strong>PACIENTE:</strong> {{ $paciente->nombre }} {{ $paciente->apellido }}</td>
                <td style="width: 50%;"><strong>RUT:</strong> {{ $paciente->rut ?? 'Sin registrar' }}</td>
            </tr>
            <tr>
                <td><strong>TELÉFONO:</strong> {{ $paciente->telefono ?? 'Sin registrar' }}</td>
                <td><strong>FECHA ATENCIÓN:</strong> {{ $ficha->created_at->format('d/m/Y - H:i') }} hrs</td>
            </tr>
            <tr>
                <td colspan="2"><strong>CORREO:</strong> {{ $paciente->correo ?? 'Sin registrar' }}</td>
            </tr>
        </table>
    </div>

    <!-- 3. ALERTAS MÉDICAS / ANAMNESIS -->
    @if(!empty($paciente->alergias) || !empty($paciente->enfermedades_cronicas))
        <div class="box-alertas">
            <div class="title-alerta">⚠️ Alertas Médicas / Anamnesis</div>
            <table class="patient-table">
                <tr>
                    <td style="width: 50%;"><strong>Alergias:</strong> {{ $paciente->alergias ?: 'Ninguna registrada' }}</td>
                    <td style="width: 50%;"><strong>Enf. Crónicas:</strong> {{ $paciente->enfermedades_cronicas ?: 'Sin registros' }}</td>
                </tr>
            </table>
        </div>
    @endif

    <!-- 4. DETALLE DE LA CONSULTA Y TRATAMIENTO -->
    <div class="box">
        <div class="box-title">Motivo de Consulta</div>
        <p class="content-p">{{ $ficha->motivo_consulta ?? 'Sin motivo especificado' }}</p>
    </div>

    <div class="box">
        <div class="box-title">Diagnóstico</div>
        <p class="content-p">{{ $ficha->diagnostico ?? 'Sin diagnóstico especificado' }}</p>
    </div>

    <div class="box">
        <div class="box-title">Tratamiento Realizado</div>
        <p class="content-p">{{ $ficha->tratamiento ?? 'Sin tratamiento especificado' }}</p>
    </div>

    <div class="box" style="min-height: 80px;">
        <div class="box-title">Indicaciones / Prescripción Médica</div>
        <p class="content-p">{{ $ficha->observaciones ?? 'Sin indicaciones adicionales' }}</p>
    </div>

    <!-- 5. TIMBRE Y FIRMA DEL PROFESIONAL -->
    <div class="signature-container">
        <div class="signature-box">
            <p class="signature-title">Firma y Timbre del Profesional</p>
            <p class="signature-sub">Cirujano Dentista</p>
        </div>
    </div>

    <!-- 6. PIE DE PÁGINA -->
    <div class="footer">
        Documento clínico generado automáticamente por MOLARIS Software Dental © {{ date('Y') }}
    </div>

</body>
</html>