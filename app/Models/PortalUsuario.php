<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class PortalUsuario extends Authenticatable
{
    use HasUuids, HasRoles, HasFactory;

    protected string $guard_name = 'portal';

    protected $fillable = ['name', 'email', 'password', 'activo'];

    protected $hidden = ['password', 'remember_token'];

    public function clientes()
    {
        return $this->belongsToMany(Cliente::class, 'cliente_portal_usuario');
    }
}
