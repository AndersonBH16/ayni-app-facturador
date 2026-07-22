<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

/**
 * Filtra automáticamente cualquier consulta por la empresa (tenant)
 * del usuario interno autenticado. No aplica a usuarios de portal
 * (esos se filtran por cliente_id en el módulo de Ecommerce).
 */
class EmpresaScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (Auth::guard('web')->check() && Auth::guard('web')->user()->empresa_id) {
            $builder->where($model->getTable().'.empresa_id', Auth::guard('web')->user()->empresa_id);
        }
    }
}
