<?php

namespace App\Livewire\Storefront;

use App\Models\Carrito;
use App\Models\Cliente;
use App\Models\OfertaProducto;
use App\Models\Producto;
use App\Services\PricingService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.storefront')]
class VerProducto extends Component
{
    public Producto $producto;

    public bool $mostrarFormularioRegistro = false;
    public ?string $ofertaPendienteId = null;
    public string $rucInput = '';
    public string $razonSocialInput = '';
    public string $mensaje = '';

    public function mount(Producto $producto): void
    {
        $this->producto = $producto;
    }

    public function agregarAlCarrito(string $ofertaId): void
    {
        if (! Auth::guard('portal')->check()) {
            $this->redirect(route('portal.login'), navigate: true);
            return;
        }

        $portalUsuario = Auth::guard('portal')->user();
        $oferta = OfertaProducto::withoutGlobalScopes()->findOrFail($ofertaId);

        $cliente = $portalUsuario->clientes()->where('empresa_id', $oferta->empresa_id)->first();

        if (! $cliente) {
            $this->ofertaPendienteId = $ofertaId;
            $this->mostrarFormularioRegistro = true;
            return;
        }

        $this->agregarConCliente($oferta, $cliente);
    }

    public function confirmarRegistroYAgregar(): void
    {
        $this->validate([
            'rucInput' => ['required', 'digits:11'],
            'razonSocialInput' => ['required', 'string', 'max:255'],
        ]);

        $oferta = OfertaProducto::withoutGlobalScopes()->findOrFail($this->ofertaPendienteId);
        $portalUsuario = Auth::guard('portal')->user();

        $cliente = Cliente::create([
            'empresa_id' => $oferta->empresa_id,
            'tipo_cliente' => 'general',
            'tipo_documento' => '6',
            'ruc_dni' => $this->rucInput,
            'razon_social' => $this->razonSocialInput,
        ]);

        $portalUsuario->clientes()->attach($cliente->id);

        $this->agregarConCliente($oferta, $cliente);

        $this->mostrarFormularioRegistro = false;
        $this->rucInput = '';
        $this->razonSocialInput = '';
    }

    protected function agregarConCliente(OfertaProducto $oferta, Cliente $cliente): void
    {
        $carrito = Carrito::withoutGlobalScopes()->firstOrCreate(
            ['cliente_id' => $cliente->id],
            ['empresa_id' => $cliente->empresa_id]
        );

        $item = $carrito->items()->where('oferta_producto_id', $oferta->id)->first();
        $cantidadDeseada = $item ? $item->cantidad + 1 : 1;

        if (! $oferta->tieneStockPara($cantidadDeseada)) {
            $this->mensaje = 'No hay stock suficiente de este vendedor.';
            return;
        }

        $item ? $item->increment('cantidad') : $carrito->items()->create(['oferta_producto_id' => $oferta->id, 'cantidad' => 1]);

        $this->mensaje = 'Agregado al carrito.';
        $this->dispatch('carrito-actualizado');
    }

    public function render()
    {
        $portalUsuario = Auth::guard('portal')->user();
        $pricing = app(PricingService::class);

        $ofertas = OfertaProducto::withoutGlobalScopes()
            ->where('producto_id', $this->producto->id)
            ->where('activo', true)
            ->with('empresa')
            ->orderBy('precio_base')
            ->get()
            ->map(function ($oferta) use ($portalUsuario, $pricing) {
                $cliente = $portalUsuario?->clientes->firstWhere('empresa_id', $oferta->empresa_id);
                $oferta->precio_mostrado = $cliente ? $pricing->precioParaCliente($oferta, $cliente) : $oferta->precio_base;
                return $oferta;
            });

        return view('livewire.storefront.ver-producto', ['ofertas' => $ofertas]);
    }
}
