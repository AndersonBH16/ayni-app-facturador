<?php

namespace App\Models;

use App\Traits\BelongsToEmpresa;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use HasUuids, BelongsToEmpresa, HasFactory;

    protected $table = 'sucursales';

    protected $fillable = ['empresa_id', 'codigo', 'nombre', 'direccion', 'activo'];
}
