<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = ['ruc', 'razon_social', 'nombre_comercial', 'activo'];

    public function sucursales()
    {
        return $this->hasMany(Sucursal::class);
    }

    public function rubros()
    {
        return $this->hasMany(Rubro::class);
    }

    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    public function listasPrecio()
    {
        return $this->hasMany(ListaPrecio::class);
    }
}
