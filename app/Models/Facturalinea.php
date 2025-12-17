<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facturalinea extends Model
{
    //
    protected $fillable = [
        'factura_id', 'articulo_id', 'codigo', 'cantidad', 'iva', 
        'precio', 'descripcion', 'base', 'importeiva', 'importe'
    ];

    public function factura()
    {
        return $this->belongsTo(Factura::class, 'factura_id');
    }

    public function articulo()
    {
        return $this->belongsTo(Articulo::class, 'articulo_id');
    }
}
