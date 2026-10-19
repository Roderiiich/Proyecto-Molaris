<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Proveedor;
use App\Models\Articulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CompraController extends Controller
{
    public function index(Request $request)
{
    $query = Compra::with('proveedor')->withCount('detalles');

    if ($request->filled('proveedor_id')) {
        $query->where('proveedor_id', $request->proveedor_id);
    }

    if ($request->filled('numero_factura')) {
        $query->where('numero_factura', 'like', '%' . $request->numero_factura . '%');
    }

    if ($request->filled('fecha_desde')) {
        $query->whereDate('fecha_compra', '>=', $request->fecha_desde);
    }

    if ($request->filled('fecha_hasta')) {
        $query->whereDate('fecha_compra', '<=', $request->fecha_hasta);
    }

    $compras = $query->orderBy('fecha_compra', 'desc')->paginate(10);
    $proveedores = Proveedor::orderBy('nombre')->get();

    return view('financiero.compras.index', compact('compras', 'proveedores'));
}

    public function create()
    {
        $hoy = Carbon::now()->format('Y-m-d');
        $proveedores = Proveedor::orderBy('nombre')->get();

        return view('financiero.compras.create', compact('proveedores', 'hoy'));
    }

    public function store(Request $request)
    {
        $hoy = Carbon::now()->format('Y-m-d');

        $request->validate([
            'proveedor_id'   => 'required|exists:proveedores,id',
            'numero_factura' => 'nullable|string|max:255',
            'fecha_compra'   => 'required|date|before_or_equal:' . $hoy,
            'descripcion'    => 'nullable|string',
            'items'          => 'required|array|min:1',
            'items.*.nombre_insumo'   => 'required|string',
            'items.*.cantidad'        => 'required|integer|min:1',
            'items.*.precio_unitario' => 'required|numeric|min:0',
        ], [
            'fecha_compra.before_or_equal' => 'La fecha de compra no puede ser posterior a hoy.',
        ]);

        DB::transaction(function () use ($request) {
            $montoTotal = 0;

            foreach ($request->items as $item) {
                $montoTotal += $item['cantidad'] * $item['precio_unitario'];
            }

            // 1. Crear la cabecera de la compra
            $compra = Compra::create([
                'proveedor_id'   => $request->proveedor_id,
                'numero_factura' => $request->numero_factura,
                'fecha_compra'   => $request->fecha_compra,
                'monto_total'    => $montoTotal,
                'descripcion'    => $request->descripcion,
            ]);

            // 2. Registrar cada detalle y actualizar inventario
            foreach ($request->items as $item) {
                // Busca si el insumo existe en el inventario por nombre (coincidencia insensible a mayúsculas/minúsculas)
                $articulo = Articulo::where('nombre', 'ILIKE', trim($item['nombre_insumo']))->first();

                $compra->detalles()->create([
                    'inventario_id'   => $articulo?->id,
                    'nombre_insumo'   => $item['nombre_insumo'],
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                    'subtotal'        => $item['cantidad'] * $item['precio_unitario'],
                ]);

                // Si el artículo existe, incrementa el stock y actualiza su precio de costo
                if ($articulo) {
                    $articulo->stock_actual += $item['cantidad'];
                    $articulo->precio_costo  = $item['precio_unitario'];
                    $articulo->actualizarEstado(); // Transiciona a 'Disponible' si estaba 'Agotado'
                }
            }
        });

        return redirect()->route('financiero.compras.index')
            ->with('success', 'Compra registrada e inventario actualizado exitosamente.');
    }

    public function buscarInsumos(Request $request)
    {
        $buscar = $request->input('q');

        if (!$buscar) {
            return response()->json([]);
        }

        // Selecciona precio_costo de acuerdo a la estructura de la tabla articulos
        $insumos = Articulo::where('nombre', 'ILIKE', "%{$buscar}%")
            ->select('id', 'nombre', 'precio_costo')
            ->limit(8)
            ->get();

        return response()->json($insumos);
    }

    
    /**
     * Muestra el detalle de una compra específica.
     */
    public function show(Compra $compra)
    {
        $compra->load(['proveedor', 'detalles.articulo']);

        return view('financiero.compras.show', compact('compra'));
    }
}