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
        $facturalinea = Facturalinea::findOrFail($id);

        $facturas = DB::table('facturas')
                        ->selectRaw("concat(fecha, '_', id) as etiqueta, id")
                        ->get();

        $articulos = Articulo::all();

        return view('facturalineas.edit', compact('facturalinea', 'facturas',  'articulos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        /*
        En el update debemos de actualizar varias cosas:

            Facturalineas
        
            Facturas 
                - si se cambia de factura_id debemos de actualizar la vieja y la nueva

            Articulos
                - si se cambia la cantidad o se cambia de artículo, modificar stock de ambos

            {
                Previo a actualizar:
                    - calcular el stock que se recupera al artículo viejo
                    - comprobar que el nuevo stock en el artículo nuevo sea válido 
                    (puede ser el mismo articulo que el viejo)

                Postactualización:
                    - recalcular factura nueva (base, importeiva, iva)
                    - si factura nueva != factura vieja; recalcular también en vieja
                    
                redirigir al index de facturalinea/factura/factura_id
            }
        */

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

        $this->validate($request, $campos, $mensajes);

        // facturalinea
        $facturalinea = Facturalinea::findOrFail($id);

        $facturavieja = $facturalinea->factura;
        $articuloviejo = $facturalinea->articulo;

        $facturanueva = Factura::findOrFail($request->factura_id);
        $articulonuevo = Articulo::findOrFail($request->articulo_id);

        // Comprobar si hay Stock suficiente
        $stockDisponibleEfectivo = ($articulonuevo->id == $articuloviejo->id) 
                                 ? $articuloviejo->cantidad + $facturalinea->cantidad 
                                 : $articulonuevo->cantidad;

        if ($stockDisponibleEfectivo < $request->cantidad) 
            return back()->withErrors(['cantidad' => "Stock insuficiente. Disponible: $stockDisponibleEfectivo"])->withInput();
        

        // Cambiamos los Stocks en artículos  
        $articuloviejo->cantidad += $facturalinea->cantidad; // Devolvemos cantidad en viejo
        $articuloviejo->save();

        $articulonuevo->refresh(); 
        $articulonuevo->cantidad -= $request->cantidad; // Quitamos cantidad en nuevo
        $articulonuevo->save();

        // Agregamos los cambios en FacturasLineas
        $base       = $request->cantidad * $articulonuevo->precio;
        $importeiva = $base * ($request->iva / 100);

        $facturalinea->update([
            'factura_id'  => $request->factura_id,
            'articulo_id' => $request->articulo_id,
            'codigo'      => $request->codigo,
            'cantidad'    => $request->cantidad,
            'iva'         => $request->iva,
            'precio'      => $articulonuevo->precio,
            'descripcion' => $articulonuevo->descripcion,
            'base'        => $base,
            'importeiva'  => $importeiva,
            'importe'     => $base + $importeiva,
        ]);

        $this->recalcularTotalesFactura($facturanueva);
        
        // recalcula base importeiva importe post cambio en FacturaLineas
        if ($facturanueva->id != $facturavieja->id)
            $this->recalcularTotalesFactura($facturavieja);
        

        return redirect()->route('facturalineas.factura', $request->factura_id)
                         ->with('mensaje', 'Línea modificada con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        // Ojo: que hay que restaurar el stock y recalcular los campos de factura 
        $facturalinea = Facturalinea::findOrFail($id);

        $factura_id = $facturalinea->factura_id;

        $articulo = $facturalinea->articulo;
        $factura = $facturalinea->factura;

        // Restaura el stock
        $articulo->cantidad += $facturalinea->cantidad;
        $articulo->save();

        // Actualiza la línea        
        $facturalinea->delete();

        // Actualiza los valores de la factura
        $this->recalcularTotalesFactura($factura);
            
        return redirect()->route('facturalineas.factura', $factura_id)
                         ->with('mensaje', 'Línea eliminada y stock restaurado');
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

    /**
     * Función que recalcula los datos de la factura
     */
    private function recalcularTotalesFactura($factura)
    {
        $factura->base = $factura->facturalineas()->sum('base');
        $factura->importeiva = $factura->facturalineas()->sum('importeiva');
        $factura->importe = $factura->facturalineas()->sum('importe');
        $factura->save();
    }
}
