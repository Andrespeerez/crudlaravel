@extends('layouts.app')
@section('content')

<div class="container">

<h1>Lineas Factura</h1>

<h2>Modificar Linea Factura Existente:</h2>

<p>Formulario para modificar una linea factura</p>

<br>

<form action="{{ route('facturalineas.update', $facturalinea->id) }}" method="post" enctype="multipart/form-data">
@csrf
@php
    /*
    csrf (Cross-Site Request Forgery):
    Añadimos un token para asegurarnos que el formulario que recibimos
    proviene de nuestra aplicación y no es un formulario que provenga
    de otra aplicación a nuestro endpoint.

    Un atacante podría intentar engañar a un usuario para que cambie
    la contraseña o haga algo malicioso. De esta forma, comparando
    los tokens sabemos si el fomulario lo ha solicitado legitimamente
    o es un intento malicioso.
    */
@endphp
{{ method_field('PATCH') }}
@include('facturalineas.form', ['submit' => 'Modificar Línea factura', 'cancel' => 'Cancelar la modificación'])
</form>

</div>

@endsection