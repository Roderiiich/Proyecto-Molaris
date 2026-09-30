<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InsumosReposicionExport;

class InventarioController extends Controller
{
    // Muestra la vista principal del inventario con métricas resumidas
    public function index()
    {
        $articulos = Articulo::orderBy('nombre', 'asc')->get();
        
        $totalArticulos = $articulos->count();
        $stockBajo = $articulos->filter(fn($item) => $item->stock_actual <= $item->stock_minimo && $item->stock_actual > 0)->count();
        $agotados = $articulos->where('stock_actual', '<=', 0)->count();

        return view('inventario.index', compact('articulos', 'totalArticulos', 'stockBajo', 'agotados'));
    }

    // Registra un nuevo insumo/artículo
    public function store(Request $request)
    {
        $request->validate([
            'nombre'        => 'required|string|max:255',
            'categoria'     => 'required|string|max:100',
            'unidad_medida' => 'required|string|max:50',
            'stock_actual'  => 'required|integer|min:0',
            'stock_minimo'  => 'required|integer|min:0',
            'precio_costo'  => 'nullable|numeric|min:0',
        ]);

        $articulo = Articulo::create([
            'nombre'        => $request->nombre,
            'codigo_barras' => $request->codigo_barras,
            'categoria'     => $request->categoria,
            'unidad_medida' => $request->unidad_medida,
            'stock_actual'  => $request->stock_actual,
            'stock_minimo'  => $request->stock_minimo,
            'precio_costo'  => $request->precio_costo ?? 0.00,
            'estado'        => $request->stock_actual > 0 ? 'Disponible' : 'Agotado',
        ]);

        return redirect()->route('inventario.index')->with('success', 'Insumo registrado correctamente.');
    }

   // En app/Http/Controllers/InventarioController.php

public function actualizarStock(Request $request, $id)
{
    // 1. Validar que llegue la nueva variable stock_actual
    $request->validate([
        'stock_actual' => 'required|integer|min:0',
    ]);

    // 2. Buscar el artículo
    $articulo = Articulo::findOrFail($id);

    // 3. Asignar y guardar el nuevo valor directamente
    $articulo->stock_actual = $request->stock_actual;
    $articulo->save();

    // 4. Actualizar el estado si tienes el método en el modelo
    if (method_exists($articulo, 'actualizarEstado')) {
        $articulo->actualizarEstado();
    }

    // 5. Redireccionar con mensaje de éxito
    return redirect()->route('inventario.index')->with('success', 'Stock actualizado correctamente.');
}

    // Eliminar un artículo
    public function destroy($id)
    {
        $articulo = Articulo::findOrFail($id);
        $articulo->delete();

        return redirect()->route('inventario.index')->with('success', 'Insumo eliminado del inventario.');
    }

    public function exportExcel()
    {
        $fecha = date('d-m-Y');
        return Excel::download(new InsumosReposicionExport, "Lista_Reposicion_Insumos_{$fecha}.xlsx");
    }
}