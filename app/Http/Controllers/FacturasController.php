<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacturasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        // Factura::with() devuelve la búsqueda junto con el resultado de la relación
        // Así, puedo acceder a $factura->cliente->nombre
        $facturas['facturas'] = Factura::with('cliente')->paginate(10);

        return view('facturas.index', $facturas);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $clientes = Cliente::all();

        return view('facturas.create', compact('clientes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $campos = [
            'numero' => 'required|numeric',
            'fecha' => 'required|date',
            'cliente_id' => 'required|exists:clientes,id',
            'base' => 'nullable|numeric|min:0',
            'importeiva' => 'nullable|numeric|min:0',
            'importe' => 'nullable|numeric|min:0',
        ];

        $mensajes = [
            'required' => 'El :attribute es requerido.',
            'fecha.required' => 'La :attribute es requerida.',
            'cliente_id.exists' => 'El cliente seleccionado no existe.',
            'base.numeric' => 'La base debe ser un número válido.',
        ];

        $this->validate($request, $campos, $mensajes);

        $datos = $request->except('_token');

        Factura::insert($datos);

        return redirect(to: session('return_to', 'facturas'))->with('mensaje', 'Factura insertada');
    }

    /**
     * Display the specified resource.
     */
    public function show(Factura $factura)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $factura = Factura::findOrFail($id);
        $clientes = Cliente::all();

        return view('facturas.edit', compact('factura', 'clientes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $campos = [
            'numero' => 'required|numeric',
            'fecha' => 'required|date',
            'cliente_id' => 'required|exists:clientes,id',
            'base' => 'nullable|numeric|min:0',
            'importeiva' => 'nullable|numeric|min:0',
            'importe' => 'nullable|numeric|min:0',
        ];

        $mensajes = [
            'required' => 'El :attribute es requerido.',
            'fecha.required' => 'La :attribute es requerida.',
            'cliente_id.exists' => 'El cliente seleccionado no existe.',
            'base.numeric' => 'La base debe ser un número válido.',
        ];

        $this->validate($request, $campos, $mensajes);

        $datos = $request->except(['_token', '_method']);

        Factura::where('id', '=', $id)->update($datos);

        return redirect(to: session('return_to', 'facturas'))->with('mensaje', 'Factura modificada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        Factura::destroy($id);

        return redirect(to: session('return_to', 'facturas'))->with('mensaje', 'Factura borrada');
    }

    public function facturascliente($cliente_id)
    {
        // Usando la variable de sesión, podemos saber de que origen ha venido el usuario
        // si cancela o realiza un formulario, lo podemos mandar al último origen de donde vino
        session()->put('return_to', url("/facturas/cliente/$cliente_id"));
        session()->put('last_client_id', $cliente_id);

        // Factura::with() devuelve las facturas junto con la relación.
        $facturas = Factura::with('cliente')
                            ->where('cliente_id', $cliente_id)
                            ->orderBy('id')
                            ->paginate(10);

        return view('facturas.index', compact('facturas'));
    }
}
