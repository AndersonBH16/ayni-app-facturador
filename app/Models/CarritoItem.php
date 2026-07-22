<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CarritoItem extends Model
{
    use HasUuids;

    protected $fillable = ['carrito_id', 'oferta_producto_id', 'cantidad'];

    public function ofertaProducto()
    {
        return $this->belongsTo(OfertaProducto::class);
    }
}
