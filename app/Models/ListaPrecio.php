<?php

namespace App\Models;

use App\Traits\BelongsToEmpresa;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListaPrecio extends Model
{
    use HasUuids, BelongsToEmpresa, HasFactory;

    protected $table = 'listas_precio';

    protected $fillable = ['empresa_id', 'nombre', 'activo'];

    public function items()
    {
        return $this->hasMany(ListaPrecioItem::class);
    }

    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }
}
