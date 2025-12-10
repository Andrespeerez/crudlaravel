@extends('layouts.app')
@section('content')

<div class="container">
<h1>Clientes</h1>

<p>Gestión de los clientes</p>

@if (Session::has('mensaje'))
<div class="alert alert-success alert-dismissible" role="alert">
    {{ Session::get('mensaje') }}

    <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<div class="row">
    <form action="{{ route('clientes.index') }}" method="get">
        <div class="form-row">
            <div class="col-sm-8 my-1">
                <input type="text" class="form-control" name="buscar" value="{{ $buscar }}">
            </div>
            <div class="col-auto my-1">
                <input type="submit" class="btn btn-primary" name="buscar_btn" value="Buscar">
            </div>
        </div>
    </form>
</div>


<table class="table table-light">
    <thead class="thead-light">
        <tr>
            <th>Id</th>
            <th>Nombre</th>
            <th>Dirección</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Logo</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($clientes as $cliente)
        <tr>
            <td>{{ $cliente->id }}</td>
            <td>{{ $cliente->nombre }}</td>
            <td>{{ $cliente->direccion }}</td>
            <td>{{ $cliente->email }}</td>
            <td>{{ $cliente->telefono }}</td>
            <td>
                @if($cliente->logo)
                <img src="{{ asset('storage') . '/' . $cliente->logo }}" 
                    class="img-thumbnail" width="50" height="50">
                @endif
            </td>
            <td> 
                <a href="{{ url('/clientes/' . $cliente->id . '/edit') }}" class="btn btn-success">
                    Editar
                </a>
                 
                <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" style="display:inline;">
                    @csrf
                    {{ method_field('DELETE') }}
                    <input type="submit" 
                           onclick="return confirm('¿Quiere borrar el cliente seleccionado?')"
                           value="Borrar" 
                           class="btn btn-danger">
                </form>

                <a href="{{ url('/facturas/cliente') . '/' . $cliente->id }}" class="btn btn-secondary">
                    Facturas
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="7">
                <a href="{{ url('clientes/create') }}">
                    <button type="button" class="btn btn-primary">Nuevo</button>
                </a>
            </td>
        </tr>
    </tfoot>
</table>

{!! $clientes->links() !!}

</div>

@endsection