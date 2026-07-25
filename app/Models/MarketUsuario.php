<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class MarketUsuario extends Authenticatable
{
    use HasUuids, HasRoles, HasFactory;

    protected $table = 'market_usuarios';

    protected string $guard_name = 'market';

    protected $fillable = ['name', 'email', 'password', 'activo'];

    protected $hidden = ['password', 'remember_token'];

    public function clientes()
    {
        return $this->belongsToMany(Cliente::class, 'cliente_market_usuario');
    }
}
