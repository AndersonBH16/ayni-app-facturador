<?php

namespace App\Models;

use App\Traits\BelongsToEmpresa;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasUuids, BelongsToEmpresa, HasFactory;

    protected $fillable = [
        'empresa_id', 'sku', 'nombre', 'descripcion',
        'precio_base', 'unidad_medida', 'activo',
    ];

    protected $casts = [
        'precio_base' => 'decimal:2',
    ];

    public function listaPrecioItems()
    {
        return $this->hasMany(ListaPrecioItem::class);
    }
}
