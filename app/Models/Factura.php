<?php

namespace App\Models;

use App\Models\Cliente;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    //
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function facturalineas()
    {
        return $this->hasMany(Facturalinea::class);
    }
}
