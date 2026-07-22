<?php

namespace App\Models;

use App\Traits\BelongsToEmpresa;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rubro extends Model
{
    use HasUuids, BelongsToEmpresa, HasFactory;

    protected $fillable = ['empresa_id', 'nombre'];

    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }
}
