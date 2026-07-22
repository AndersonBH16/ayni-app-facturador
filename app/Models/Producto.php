<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = ['categoria_id', 'sku', 'nombre', 'descripcion', 'imagen', 'unidad_medida', 'activo'];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function ofertas()
    {
        return $this->hasMany(OfertaProducto::class);
    }
}
