<?php

namespace App\Models;

use App\Traits\BelongsToEmpresa;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasUuids, BelongsToEmpresa, HasFactory;

    protected $fillable = [
        'empresa_id', 'rubro_id', 'lista_precio_id', 'tipo_cliente',
        'tipo_documento', 'ruc_dni', 'razon_social', 'email', 'activo',
    ];

    public function rubro()
    {
        return $this->belongsTo(Rubro::class);
    }

    public function listaPrecio()
    {
        return $this->belongsTo(ListaPrecio::class);
    }

    public function portalUsuarios()
    {
        return $this->hasMany(PortalUsuario::class);
    }

    public function esEspecial(): bool
    {
        return $this->tipo_cliente === 'especial';
    }
}
