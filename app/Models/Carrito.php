<?php

namespace App\Models;

use App\Traits\BelongsToEmpresa;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    use HasUuids, BelongsToEmpresa;

    protected $fillable = ['empresa_id', 'cliente_id'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function items()
    {
        return $this->hasMany(CarritoItem::class);
    }
}
