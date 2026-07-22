<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = ['nombre', 'slug', 'icono', 'orden'];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}
