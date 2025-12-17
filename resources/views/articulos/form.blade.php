@if(count($errors) > 0)
<div class="alert alert-danger" role="alert">
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif


<div class="form-group">
    <label for="codigo">Código</label>
    <input type="text" name="codigo" id="codigo" maxlength="10"
        value="{{ isset($articulo->codigo) ? $articulo->codigo : old('codigo') }}"
        class="form-control">
</div>
<br>

<div class="form-group">
    <label for="descripcion">Descripción</label>
    <input type="text" name="descripcion" id="descripcion" maxlength="50"
        value="{{ isset($articulo->descripcion) ? $articulo->descripcion : old('descripcion') }}"
        class="form-control">
</div>
<br>

<div class="form-group">
    <label for="cantidad">Cantidad</label>
    <input type="number" name="cantidad" id="cantidad" maxlength="11"
        value="{{ isset($articulo->cantidad) ? $articulo->cantidad : old('cantidad') }}"
        class="form-control">
</div>
<br>

<div class="form-group">
    <label for="precio">Precio</label>
    <input type="number" name="precio" id="precio" maxlength="11" step="0.01"
        value="{{ isset($articulo->precio) ? $articulo->precio : old('precio') }}"
        class="form-control">
</div>
<br>

<input type="submit" value="{{ $submit }}" class="btn btn-primary">
<a href="{{ url('articulos/') }}" class="btn btn-success">
    {{ $cancel }}
</a>