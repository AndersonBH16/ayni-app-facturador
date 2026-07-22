<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class PortalUsuario extends Authenticatable
{
    use HasUuids, HasRoles;

    protected string $guard_name = 'portal';

    protected $fillable = ['cliente_id', 'name', 'email', 'password', 'activo'];

    protected $hidden = ['password', 'remember_token'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
