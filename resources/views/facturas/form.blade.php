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
    <label for="numero">Número</label>
    <input type="text" name="numero" id="numero" maxlength="10"
        value="{{ isset($factura->numero) ? $factura->numero : old('numero') }}" 
        @if (isset($readonly)) {{ $readonly }} @endif
        class="form-control">
</div>
<br>

<div class="form-group">
    <label for="fecha">Fecha</label>
    <input type="date" name="fecha" id="fecha" maxlength="64"
        value="{{ isset($factura->fecha) ? $factura->fecha : old('fecha') }}" 
        @if (isset($readonly)) {{ $readonly }} @endif
        class="form-control">
</div>
<br>

<div class="form-group">
    <label for="cliente_id">Cliente</label>
    <select type="text" name="cliente_id" id="cliente_id"
        class="form-control">
        @foreach ($clientes as $cliente)
        <option value="{{ $cliente->id }}"
            @php
            // De esta forma va mejor que de la otra forma
            // 
            //  1º Si falla el formulario, 
            //           ... devuelvo selected para el anterior cliente_id que tenía
            //  2º Si estoy editando, 
            //           ... devuelvo selected el valor del cliente_id que estoy editando
            //  3º Si estoy creando uno nuevo, 
            //           ... devuelvo selected el cliente_id desde donde estoy creandolo
            @endphp
            {{ 
                old('cliente_id', $factura->cliente_id ?? session('last_client_id')) == $cliente->id 
                    ? 'selected' 
                    : '' 
            }}>
            {{ $cliente->nombre }}
        </option>
        @endforeach
    </select>
</div>
<br>

<div class="form-group">
    <label for="base">Base</label>
    <input type="number" name="base" id="base" maxlength="100"
        value="{{ isset($factura->base) ? $factura->base : old('base') }}" 
        @if (isset($readonly)) {{ $readonly }} @endif
        class="form-control">
</div>
<br>

<div class="form-group">
    <label for="importeiva">Importe I.V.A.</label>
    <input type="number" name="importeiva" id="importeiva" maxlength="11"
        value="{{ isset($factura->importeiva) ? $factura->importeiva : old('importeiva') }}" 
        @if (isset($readonly)) {{ $readonly }} @endif
        class="form-control">
</div>
<br>

<div class="form-group">
    <label for="importe">Importe</label>
    <input type="number" name="importe" id="importe" maxlength="11"
        value="{{ isset($factura->importe) ? $factura->importe : old('importe') }}" 
        @if (isset($readonly)) {{ $readonly }} @endif
        class="form-control">
</div>
<br>

@if (isset($submit))
<input type="submit" class="btn btn-primary" value="{{ $submit }}">
@else
<br>
@endif

<a href="{{ session('return_to', url('/facturas')) }}" class="btn btn-danger">
    {{ $cancel }}
</a>
