<?php

namespace App\Livewire\Market;

use App\Models\Carrito;
use App\Models\Categoria;
use App\Models\OfertaProducto;
use App\Models\Producto;
use App\Services\PricingService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.market')]
class Catalogo extends Component
{
    use WithPagination;

    #[Url(as: 'q', history: true)]
    public string $busqueda = '';

    #[Url(as: 'cat', history: true)]
    public ?string $categoriaId = null;

    public string $orden = 'recientes';

    public int $mostrando = 12;
    public bool $modoPaginado = false;

    protected $paginationTheme = 'tailwind';

    public function updatingBusqueda(): void { $this->reiniciarListado(); }
    public function updatedOrden(): void { $this->reiniciarListado(); }

    public function seleccionarCategoria(?string $categoriaId): void
    {
        $this->categoriaId = $this->categoriaId === $categoriaId ? null : $categoriaId;
        $this->reiniciarListado();
    }

    protected function reiniciarListado(): void
    {
        $this->mostrando = 12;
        $this->modoPaginado = false;
        $this->resetPage();
    }

    public function cargarMas(): void
    {
        if ($this->modoPaginado) return;

        $this->mostrando += 12;

        if ($this->mostrando >= 50) {
            $this->mostrando = 50;
            $this->modoPaginado = true;
        }
    }

    public function agregarAlCarrito(string $ofertaId): void
    {
        if (! Auth::guard('market')->check()) {
            $this->redirect(route('portal.login'), navigate: true);
            return;
        }

        $marketUsuario = Auth::guard('market')->user();
        $oferta = OfertaProducto::withoutGlobalScopes()->findOrFail($ofertaId);
        $cliente = $marketUsuario->clientes()->where('empresa_id', $oferta->empresa_id)->first();

        if (! $cliente) {
            $this->redirect(route('market.producto', $oferta->producto_id), navigate: true);
            return;
        }

        $carrito = Carrito::withoutGlobalScopes()->firstOrCreate(
            ['cliente_id' => $cliente->id],
            ['empresa_id' => $cliente->empresa_id]
        );

        $item = $carrito->items()->where('oferta_producto_id', $oferta->id)->first();
        $cantidadDeseada = $item ? $item->cantidad + 1 : 1;

        if (! $oferta->tieneStockPara($cantidadDeseada)) {
            $this->dispatch('sin-stock');
            return;
        }

        $item ? $item->increment('cantidad') : $carrito->items()->create(['oferta_producto_id' => $oferta->id, 'cantidad' => 1]);

        $this->dispatch('carrito-actualizado');
    }

    protected function baseQuery()
    {
        $marketUsuario = Auth::guard('market')->user();

        return Producto::query()
            ->where('activo', true)
            ->whereHas('ofertas', fn ($q) => $q->withoutGlobalScopes()->where('activo', true))
            ->withMin(['ofertas as precio_desde' => fn ($q) => $q->withoutGlobalScopes()->where('activo', true)], 'precio_base')
            ->withCount(['ofertas as oferentes_count' => fn ($q) => $q->withoutGlobalScopes()->where('activo', true)])
            ->with(['ofertas' => fn ($q) => $q->withoutGlobalScopes()->where('activo', true)->orderBy('precio_base')->with('empresa')])
            ->when($this->busqueda, fn ($q) => $q->where('nombre', 'like', "%{$this->busqueda}%"))
            ->when($this->categoriaId, fn ($q) => $q->where('categoria_id', $this->categoriaId))
            ->when($this->orden === 'precio_asc', fn ($q) => $q->orderBy('precio_desde'))
            ->when($this->orden === 'precio_desc', fn ($q) => $q->orderByDesc('precio_desde'))
            ->when($this->orden === 'recientes', fn ($q) => $q->latest());
    }

    protected function resolverPrecios($productos)
    {
        $marketUsuario = Auth::guard('market')->user();
        $pricing = app(PricingService::class);

        return $productos->each(function ($producto) use ($marketUsuario, $pricing) {
            $producto->ofertas->each(function ($oferta) use ($marketUsuario, $pricing) {
                $cliente = $marketUsuario?->clientes->firstWhere('empresa_id', $oferta->empresa_id);
                $oferta->precio_mostrado = $cliente ? $pricing->precioParaCliente($oferta, $cliente) : $oferta->precio_base;
            });
        });
    }

    public function render()
    {
        if ($this->modoPaginado) {
            $productosScroll = $this->resolverPrecios((clone $this->baseQuery())->take(50)->get());
            $productosPaginados = (clone $this->baseQuery())->skip(50)->paginate(24);
            $this->resolverPrecios(collect($productosPaginados->items()));
        } else {
            $productosScroll = $this->resolverPrecios((clone $this->baseQuery())->take($this->mostrando)->get());
            $productosPaginados = null;
        }

        return view('livewire.market.catalogo', [
            'productosScroll' => $productosScroll,
            'productosPaginados' => $productosPaginados,
            'categorias' => Categoria::orderBy('orden')->get(),
        ]);
    }
}
