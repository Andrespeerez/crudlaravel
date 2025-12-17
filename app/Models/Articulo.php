<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    //

    public function facturalineas()
    {
        return $this->hasMany(Facturalinea::class);
    }
}
