<?php

namespace App\Http\Controllers;

use App\Models\Facturalinea;
use App\Models\Factura;
use App\Models\Articulo;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacturalineasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        if (session()->get('last_factura_id'))
            session()->forget('last_factura_id');

        $facturalineas = DB::table('facturalineas')
                            ->select('*')
                            ->paginate(10);
        
        return view('facturalineas.index', compact('facturalineas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $facturas = DB::table('facturas')
                        ->selectRaw("concat(fecha, '_', id) as etiqueta, id")
                        ->get();

        $articulos = Articulo::all();

        return view('facturalineas.create', compact('facturas', 'articulos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $campos = [
            'factura_id'    => 'required|exists:facturas,id',
            'articulo_id'   => 'required|exists:articulos,id',
            'codigo'        => 'required|numeric|min:0',
            'cantidad'      => 'required|numeric|min:1',
            'iva'           => 'required|numeric|min:0'
        ];

        $mensajes = [
            'required'              => 'El campo :attribute es requerido.',
            'articulo_id.exists'    => 'El artículo seleccionado no existe.',
            'factura_id.exists'     => 'La factura seleccionada no existe.',
            'iva.min'               => 'El iva no puede ser menor que 0.',
            'codigo.min'            => 'El código no puede ser menor que 0.',
            'cantidad.min'          => 'La cantidad no puede ser menor que 1.',
        ];

        $request->validate($campos, $mensajes);

        // Comprobar stock
        $articulo = Articulo::findOrFail($request->articulo_id);

        if ($articulo->cantidad < $request->cantidad)
            return back()->withErrors(['cantidad' => 'No hay suficiente stock. Disponible: ' . $articulo->cantidad])->withInput();

        // Calcular Base y Importe
        $base       = $request->cantidad * $articulo->precio;
        $importeiva = $base * ($request->iva / 100);
        $importe    = $base + $importeiva;

        // Actualiza el stock
        $articulo->cantidad -= $request->cantidad;
        $articulo->save();

        // Crea la línea factura
        Facturalinea::create([
            'factura_id'    => $request->factura_id,
            'articulo_id'   => $request->articulo_id,
            'codigo'        => $request->codigo,
            'cantidad'      => $request->cantidad,
            'iva'           => $request->iva,
            'precio'        => $articulo->precio,
            'descripcion'   => $articulo->descripcion,
            'base'          => $base,
            'importeiva'    => $importeiva,
            'importe'       => $importe,
        ]);

        // Actualizar Factura con total
        $factura = Factura::findOrFail($request->factura_id);
        $factura->base = $factura->facturalineas()->sum('base');
        $factura->importeiva = $factura->facturalineas()->sum('importeiva');
        $factura->importe = $factura->facturalineas()->sum('importe');
        $factura->save();

        return redirect()->route('facturalineas.factura', $request->factura_id)
                         ->with('mensaje', 'Línea añadida con éxito');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //

        $campos = [
            'factura_id'    => 'required|exists:facturas,id',
            'articulo_id'   => 'required|exists:articulos,id',
            'codigo'        => 'required|numeric|min:0',
            'cantidad'      => 'required|numeric|min:1',
            'iva'           => 'required|numeric|min:0'
        ];

        $mensajes = [
            'required'              => 'El campo :attribute es requerido.',
            'articulo_id.exists'    => 'El artículo seleccionado no existe.',
            'factura_id.exists'     => 'La factura seleccionada no existe.',
            'iva.min'               => 'El iva no puede ser menor que 0.',
            'codigo.min'            => 'El código no puede ser menor que 0.',
            'cantidad.min'          => 'La cantidad no puede ser menor que 1.',
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Display the listing of the resource that belongs to a current 'factura'
     */
    public function facturalineasfactura($factura_id)
    {
        //
        session()->put('last_factura_id', $factura_id);

        $facturalineas = DB::table('facturalineas')
                            ->select('*')
                            ->where('factura_id', '=', $factura_id)
                            ->paginate(10);
        
        return view('facturalineas.index', compact('facturalineas'));
    }
}
