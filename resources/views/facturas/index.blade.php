@extends('layouts.app')
@section('content')

<div class="container">
<h1>Facturas</h1>

<p>Gestión de las facturas</p>

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
        <th>Número</th>
        <th>Fecha</th>
        <th>Cliente</th>
        <th>Base</th>
        <th>Importe I.V.A.</th>
        <th>Importe</th>
        <th></th>
    </thead>
    <tbody>
        @if (isset($facturas) && (count($facturas) > 0))
            @foreach ($facturas as $factura)
            <tr>
                <td>{{ $factura->id }}</td>
                <td>{{ $factura->numero }}</td>
                <td>{{ $factura->fecha }}</td>
                <td>{{ $factura->cliente->nombre }}</td>
                <td>{{ $factura->base }}</td>
                <td>{{ $factura->importeiva }}</td>
                <td>{{ $factura->importe }}</td>
                <td>
                    <a href="{{ url('/facturas') . '/' . $factura->id . '/edit' }}" class="btn btn-success">Editar</a>
                    <a href="{{ route('facturalineas.factura', $factura->id) }}" class="btn btn-secondary">Ver Facturas</a>

                    <form action="{{ url('/facturas') . '/' . $factura->id }}" method="post" style="display:inline;">
                        @csrf
                        {{ method_field('DELETE') }}
                        <input type="submit"
                               onclick="return confirm('¿Quiere borrar la factura seleccionada?')"
                               value="Borrar"
                               class="btn btn-danger">
                    </form>
                </td>
            </tr>
            @endforeach
        @else
            <tr>
                <td colspan="8">Sin facturas</td>
            </tr>
        @endif
    </tbody>
    <tfoot>
        <tr>
            <td colspan="8">
                <a href="{{ url('facturas/create') }}" class="btn btn-primary">Nueva</a>
            </td>
        </tr>
    </tfoot>
</table>


{!! $facturas->links() !!}

</div>

@endsection