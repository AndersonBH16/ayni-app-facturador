<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListaPrecioItem extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = ['lista_precio_id', 'oferta_producto_id', 'precio'];

    protected $casts = [
        'precio' => 'decimal:2',
    ];

    public function listaPrecio()
    {
        return $this->belongsTo(ListaPrecio::class);
    }

    public function ofertaProducto()
    {
        return $this->belongsTo(OfertaProducto::class);
    }
}
