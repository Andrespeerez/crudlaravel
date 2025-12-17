@extends('layouts.app')
@section('content')

<div class="container">

<h1>Artículos</h1>

<h2>Crear Artículo Nuevo:</h2>

<p>Formulario para insertar un artículo</p>

<br>

<form action="{{ route("articulos.update", $articulo->id) }}" method="post" enctype="multipart/form-data">
@csrf
{{ method_field('PATCH') }}
@include('articulos.form', ['submit' => 'Editar Artículo', 'cancel' => 'Cancelar la edición'])
</form>

@endsection