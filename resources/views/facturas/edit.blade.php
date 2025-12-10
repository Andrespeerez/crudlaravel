@extends('layouts.app')
@section('content')

<div class="container">

<h1>Facturas</h1>

<h2>Modificar Factura Existente:</h2>

<p>Formulario para modificar un facturas</p>

<br>

<form action="{{ url('/facturas') . '/' . $factura->id }}" method="post" enctype="multipart/form-data">
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
@include('facturas.form', ['submit' => 'Modificar factura', 'cancel' => 'Cancelar la modificación'])
</form>

</div>

@endsection