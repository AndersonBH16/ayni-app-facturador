<?php

namespace App\Livewire\Storefront;

use App\Models\Carrito;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class CarritoIndicador extends Component
{
    public int $cantidad = 0;

    public function mount(): void
    {
        $this->actualizarCantidad();
    }

    #[On('carrito-actualizado')]
    public function actualizarCantidad(): void
    {
        $portalUsuario = Auth::guard('portal')->user();

        if (! $portalUsuario) {
            $this->cantidad = 0;
            return;
        }

        $carritos = Carrito::withoutGlobalScopes()
            ->whereIn('cliente_id', $portalUsuario->clientes->pluck('id'))
            ->with('items')
            ->get();

        $this->cantidad = $carritos->flatMap->items->sum('cantidad');
    }

    public function render()
    {
        return view('livewire.storefront.carrito-indicador');
    }
}
