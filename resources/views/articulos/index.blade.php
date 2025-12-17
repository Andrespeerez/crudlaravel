@extends('layouts.app')
@section('content')

<div class="container">
<h1>Artículos</h1>

<p>Gestión de los artículos</p>

@if (Session::has('mensaje'))
<div class="alert alert-success alert-dismissible" role="alert">
    {{ Session::get('mensaje') }}

    <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<table class="table table-light table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Código</th>
            <th>Descripción</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @if (isset($articulos) && (count($articulos) > 0))
            @foreach ($articulos as $articulo)
                <tr>
                    <td>{{ $articulo->id }}</td>
                    <td>{{ $articulo->codigo }}</td>
                    <td>{{ $articulo->descripcion }}</td>
                    <td>{{ $articulo->cantidad }}</td>
                    <td>{{ $articulo->precio }}</td>
                    <td>
                        <a href="{{ route("articulos.edit", $articulo->id) }}"
                            class="btn btn-success">
                            Editar
                        </a>

                        <form action="{{ route("articulos.destroy", $articulo->id) }}" style="display:inline;" method="post">
                            @csrf
                            {{ method_field('DELETE') }}
                            <input type="submit" class="btn btn-danger" value="Borrar"
                                    onclick="return confirm('¿Quiere borrar el cliente seleccionado?')">
                        </form>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="6" class="text-center">No hay artículos</td>
            </tr>
        @endif
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6">
                <a href="{{ route("articulos.create") }}" class="btn btn-primary">Nuevo Artículo</a>
            </td>
        </tr>
    </tfoot>
</table>

@endsection