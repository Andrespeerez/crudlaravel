@extends('layouts.app')
@section('content')

<div class="container">

<h1>Artículos</h1>

<h2>Crear Artículo Nuevo:</h2>

<p>Formulario para insertar un artículo</p>

<br>

<form action="{{ url('/articulos') }}" method="post" enctype="multipart/form-data">
@csrf

@include('articulos.form', ['submit' => 'Crear Artículo', 'cancel' => 'Cancelar la insersión'])
</form>

@endsection