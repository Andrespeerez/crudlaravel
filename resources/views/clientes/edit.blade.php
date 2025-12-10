@extends('layouts.app')
@section('content')

<div class="container">

<h1>Clientes</h1>

<h2>Modificar Cliente Existente:</h2>

<p>Formulario para modificar un cliente</p>

<br>

<form action="{{ url('/clientes/' . $cliente->id) }}" method="post" enctype="multipart/form-data">
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
@include('clientes.form', ['submit' => 'Modificar cliente', 'cancel' => 'Cancelar la modificación'])
</form>

</div>

@endsection