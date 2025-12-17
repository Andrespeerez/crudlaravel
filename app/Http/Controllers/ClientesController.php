<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $buscar = trim($request->get('buscar'));
        $clientes = DB::table('clientes')
                      ->select('*')
                      ->where('nombre', 'LIKE', '%' . $buscar . '%')
                      ->orWhere('email', 'LIKE', '%' . $buscar . '%')
                      ->orderBy('nombre', 'asc')
                      ->paginate(10);

        return view('clientes.index', compact('clientes', 'buscar'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

        return view('clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $campos = [
            'nombre' => 'required|string|max:64',
            'direccion' => 'required|string|max:64',
            'email' => 'required|email|max:100',
            'telefono' => 'required|max:11',
            'logo' => 'max:50000|mimes:jpg,jpeg,png'
        ];

        $mensajes = [
            'required' => 'El :attribute es requerido.',
            'direccion.required' => 'La :attribute es requerida.'
        ];

        $this->validate($request, $campos, $mensajes);


        $datos = $request->except('_token');

        if ($request->hasFile('logo'))
            $datos['logo'] = $request->file('logo')->store('uploads', 'public');

        Cliente::insert($datos);

        return redirect('clientes')->with('mensaje', 'Cliente creado');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $cliente = Cliente::findOrFail($id);

        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $campos = [
            'nombre' => 'required|string|max:64',
            'direccion' => 'required|string|max:64',
            'email' => 'required|email|max:100',
            'telefono' => 'required|max:11',
        ];

        $mensajes = [
            'required' => 'El :attribute es requerido.',
            'direccion.required' => 'La :attribute es requerida.'
        ];

        if ($request->hasFile('logo'))
        {
            $campos = ['logo' => 'max:50000|mimes:jpg,jpeg,png'];
            $mensajes = ['logo.required' => 'El logo es requerido'];
        }

        $this->validate($request, $campos, $mensajes);

        $cliente = Cliente::findOrFail($id);

        $datos = $request->except(['_token', '_method']);

        if ($request->hasFile('logo'))
        {
            if ($cliente->logo)
            {
                // Storage::delete($cliente->logo); // solo funciona si configuras el disk default como 'public'
                Storage::disk('public')->delete($cliente->logo);
            }

            $datos['logo'] = $request->file('logo')->store('uploads', 'public');
        }


        Cliente::where('id', '=', $id)->update($datos);

        return redirect('clientes')->with('mensaje', 'Cliente modificado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $datos = Cliente::findOrFail($id);

        if ($datos->logo != '')
            Storage::disk('public')->delete($datos->logo);

        Cliente::destroy($id);

        return redirect('clientes')->with('mensaje', 'Cliente borrado');
    }
}
