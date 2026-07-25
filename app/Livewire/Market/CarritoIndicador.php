<?php

namespace App\Livewire\Market;

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
        $marketUsuario = Auth::guard('market')->user();

        if (! $marketUsuario) {
            $this->cantidad = 0;
            return;
        }

        $carritos = Carrito::withoutGlobalScopes()
            ->whereIn('cliente_id', $marketUsuario->clientes->pluck('id'))
            ->with('items')
            ->get();

        $this->cantidad = $carritos->flatMap->items->sum('cantidad');
    }

    public function render()
    {
        return view('livewire.market.carrito-indicador');
    }
}
