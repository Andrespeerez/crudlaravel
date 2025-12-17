<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticulosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $articulos = DB::table('articulos')
                        ->select('*')
                        ->paginate(10);

        return view('articulos.index')->with('articulos', $articulos);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

        return view('articulos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        
        $campos = [
            'codigo'        => 'required|string|max:10',
            'descripcion'   => 'required|string|max:50',
            'cantidad'      => 'required|numeric|min:1',
            'precio'        => 'required|numeric|min:0',
        ];

        $mensajes = [
            'required'              => 'El :attribute es requerido.',
            'descripcion.required'  => 'La :attribute es requirida.',
            'cantidad.required'     => 'La :attribute es requirida.',
            'numeric'               => 'El :attribute debe ser válido.',
            'cantidad.numeric'      => 'La :attribute debe ser válida.',
        ];

        $this->validate($request, $campos, $mensajes);


        $datos = $request->except('_token');

        Articulo::insert($datos);

        return redirect('articulos')->with('mensaje', 'Artículo creado');
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

        $articulo = Articulo::findOrFail($id);

        return view('articulos.edit', compact('articulo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $campos = [
            'codigo'        => 'required|string|max:10',
            'descripcion'   => 'required|string|max:50',
            'cantidad'      => 'required|numeric|min:1',
            'precio'        => 'required|numeric|min:0',
        ];

        $mensajes = [
            'required'              => 'El :attribute es requerido.',
            'descripcion.required'  => 'La :attribute es requirida.',
            'cantidad.required'     => 'La :attribute es requirida.',
            'numeric'               => 'El :attribute debe ser válido.',
            'cantidad.numeric'      => 'La :attribute debe ser válida.',
        ];

        $this->validate($request, $campos, $mensajes);

        $datos = $request->except('_token', '_method');

        Articulo::where('id', '=', $id)->update($datos);

        return redirect('articulos')->with('mensaje', 'Artículo modificado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        Articulo::destroy($id);
      
        return redirect('articulos')->with('mensaje', 'Artículo borrado');
    }
}
