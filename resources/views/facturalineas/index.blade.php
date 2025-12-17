@extends('layouts.app')
@section('content')

<div class="container">
<h1>Lineas Factura</h1>

<p>Gestión de las Lineas factura</p>

@if (Session::has('mensaje'))
<div class="alert alert-success alert-dismissible" role="alert">
    {{ Session::get('mensaje') }}

    <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<table class="table table-light table-striped">
    <thead class="thead-light">
        <th>Id</th>
        <th>Factura_id</th>
        <th>Código</th>
        <th>Cantidad</th>
        <th>Descripción</th>
        <th>Precio</th>
        <th>Base</th>
        <th>I.V.A.</th>
        <th>Importe I.V.A</th>
        <th>Importe</th>
        <th></th>
    </thead>
    <tbody>
        @if (isset($facturalineas) && (count($facturalineas) > 0))
            @foreach ($facturalineas as $facturalinea)
            <tr>
                <td>{{ $facturalinea->id }}</td>
                <td>{{ $facturalinea->factura_id }}</td>
                <td>{{ $facturalinea->codigo }}</td>
                <td>{{ $facturalinea->cantidad }}</td>
                <td>{{ $facturalinea->descripcion }}</td>
                <td>{{ $facturalinea->precio }}</td>
                <td>{{ $facturalinea->base }}</td>
                <td>{{ $facturalinea->iva }}</td>
                <td>{{ $facturalinea->importeiva }}</td>
                <td>{{ $facturalinea->importe }}</td>
                <td>
                    <a href="{{ route('facturalineas.edit', $facturalinea->id) }}" class="btn btn-success">Editar</a>

                    <form action="{{ url('/facturalineas') . '/' . $facturalinea->id }}" method="post" style="display:inline;">
                        @csrf
                        {{ method_field('DELETE') }}
                        <input type="submit"
                               onclick="return confirm('¿Quiere borrar la linea factura seleccionada?')"
                               value="Borrar"
                               class="btn btn-danger">
                    </form>
                </td>
            </tr>
            @endforeach
        @else
            <tr>
                <td colspan="11">Sin Lineas Factura</td>
            </tr>
        @endif
    </tbody>
    <tfoot>
        <tr>
            <td colspan="11">
                <a href="{{ route('facturalineas.create') }}" class="btn btn-primary">Nueva</a>
                <a href="{{ route('facturas.index') }}" class="btn btn-secondary">Volver</a>
            </td>
        </tr>
    </tfoot>
</table>


{!! $facturalineas->links() !!}

</div>

@endsection