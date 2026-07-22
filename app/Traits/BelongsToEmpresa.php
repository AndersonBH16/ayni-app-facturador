<?php

namespace App\Traits;

use App\Models\Empresa;
use App\Scopes\EmpresaScope;
use Illuminate\Support\Facades\Auth;

trait BelongsToEmpresa
{
    protected static function bootBelongsToEmpresa(): void
    {
        static::addGlobalScope(new EmpresaScope);

        static::creating(function ($model) {
            if (empty($model->empresa_id) && Auth::guard('web')->check()) {
                $model->empresa_id = Auth::guard('web')->user()->empresa_id;
            }
        });
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
