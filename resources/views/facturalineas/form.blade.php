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
    <input type="number" name="codigo" id="codigo" maxlength="10"
        value="{{ old('codigo', $facturalinea->codigo ?? '') }}" 
        @if (isset($readonly)) {{ $readonly }} @endif
        class="form-control">
</div>
<br>

<div class="form-group">
    <label for="factura_id">Factura</label>
    <select type="text" name="factura_id" id="factura_id"
        class="form-control">
        @foreach ($facturas as $factura)
        <option value="{{ $factura->id }}"
            @php
                /*
                Comprobamos si tenemos un old (formulario anterior con errores), 
                    ... en caso de que no, usamos la $facturalinea->factura_id (si existe es que venimos de edit)
                    ... en caso de que no, usamos la session 'last_factura_id' (venimos de index)
                        ... si coincide el id con el de la opción actual, ---> selected, sino nada : ''
                */ 
            @endphp
            {{ old('factura_id', $facturalinea->factura_id ?? session('last_factura_id')) == $factura->id ? 'selected' : '' }}>
            {{ $factura->etiqueta }}
        </option>
        @endforeach
    </select>
</div>
<br>

<div class="form-group">
    <label for="articulo_id">Articulo</label>
    <select type="text" name="articulo_id" id="articulo_id"
        class="form-control">
        @foreach ($articulos as $articulo)
        <option value="{{ $articulo->id }}"
            {{ old('articulo_id', $facturalinea->articulo_id ?? '') == $articulo->id ? 'selected' : '' }}>
            {{ $articulo->descripcion . ' - ' . $articulo->precio . ' €'}}
        </option>
        @endforeach
    </select>
</div>
<br>

<div class="form-group">
    <label for="cantidad">Cantidad</label>
    <input type="number" name="cantidad" id="cantidad" maxlength="100"
        value="{{ old('cantidad', $facturalinea->cantidad ?? '') }}" 
        @if (isset($readonly)) {{ $readonly }} @endif
        class="form-control">
</div>
<br>

<div class="form-group">
    <label for="iva">I.V.A.</label>
    <input type="number" name="iva" id="iva" maxlength="11"
        value="{{ old('iva', $facturalinea->iva ?? '') }}" 
        @if (isset($readonly)) {{ $readonly }} @endif
        class="form-control">
</div>
<br>

@if (isset($submit))
<input type="submit" class="btn btn-primary" value="{{ $submit }}">
@else
<br>
@endif

@php
    // Determina si vengo de editar, de la vista facturalineas o de index (null)
    $idRetorno = $facturaLinea->factura_id ?? session('last_factura_id');
@endphp
@if($idRetorno)
<a href="{{ route('facturalineas.factura', $idRetorno) }}" class="btn btn-danger">
    {{ $cancel }}
</a>   
@else
<a href="{{ route('facturalineas.index') }}" class="btn btn-danger">
    {{ $cancel }}
</a>
@endif
