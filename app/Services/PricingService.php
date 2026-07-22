<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\Producto;

/**
 * Resuelve el precio final que debe ver un cliente para un producto:
 *
 * 1. Si el cliente es 'general' -> siempre precio_base.
 * 2. Si el cliente es 'especial' y tiene lista_precio asignada ->
 *    busca el producto en esa lista; si no está, cae a precio_base.
 */
class PricingService
{
    public function precioParaCliente(Producto $producto, Cliente $cliente): float
    {
        if ($cliente->esEspecial() && $cliente->lista_precio_id) {
            $item = $producto->listaPrecioItems()
                ->where('lista_precio_id', $cliente->lista_precio_id)
                ->first();

            if ($item) {
                return (float) $item->precio;
            }
        }

        return (float) $producto->precio_base;
    }
}
