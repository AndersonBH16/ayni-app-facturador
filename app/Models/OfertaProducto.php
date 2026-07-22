<?php

namespace App\Models;

use App\Traits\BelongsToEmpresa;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfertaProducto extends Model
{
    use HasUuids, BelongsToEmpresa, HasFactory;

    protected $table = 'ofertas_producto';

    protected $fillable = ['empresa_id', 'producto_id', 'precio_base', 'controla_stock', 'stock_disponible', 'activo'];

    protected $casts = [
        'precio_base' => 'decimal:2',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function listaPrecioItems()
    {
        return $this->hasMany(ListaPrecioItem::class);
    }

    public function tieneStockPara(int $cantidad): bool
    {
        if (! $this->controla_stock) {
            return true;
        }

        return $this->stock_disponible >= $cantidad;
    }
}
