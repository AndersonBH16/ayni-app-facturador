<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\OfertaProducto;

class PricingService
{
    public function precioParaCliente(OfertaProducto $oferta, Cliente $cliente): float
    {
        if ($cliente->esEspecial() && $cliente->lista_precio_id) {
            $item = $oferta->listaPrecioItems()
                ->where('lista_precio_id', $cliente->lista_precio_id)
                ->first();

            if ($item) {
                return (float) $item->precio;
            }
        }

        return (float) $oferta->precio_base;
    }
}
