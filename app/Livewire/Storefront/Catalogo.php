<?php

namespace App\Livewire\Storefront;

use App\Models\Categoria;
use App\Models\Producto;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.storefront')]
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

    public function updatingBusqueda(): void
    {
        $this->reiniciarListado();
    }

    public function seleccionarCategoria(?string $categoriaId): void
    {
        $this->categoriaId = $this->categoriaId === $categoriaId ? null : $categoriaId;
        $this->reiniciarListado();
    }

    public function updatedOrden(): void
    {
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
        if ($this->modoPaginado) {
            return;
        }

        $this->mostrando += 12;

        if ($this->mostrando >= 50) {
            $this->mostrando = 50;
            $this->modoPaginado = true;
        }
    }

    protected function baseQuery()
    {
        return Producto::query()
            ->where('activo', true)
            ->whereHas('ofertas', fn ($q) => $q->withoutGlobalScopes()->where('activo', true))
            ->withMin(['ofertas as precio_desde' => fn ($q) => $q->withoutGlobalScopes()->where('activo', true)], 'precio_base')
            ->withCount(['ofertas as oferentes_count' => fn ($q) => $q->withoutGlobalScopes()->where('activo', true)])
            ->when($this->busqueda, fn ($q) => $q->where('nombre', 'like', "%{$this->busqueda}%"))
            ->when($this->categoriaId, fn ($q) => $q->where('categoria_id', $this->categoriaId))
            ->when($this->orden === 'precio_asc', fn ($q) => $q->orderBy('precio_desde'))
            ->when($this->orden === 'precio_desc', fn ($q) => $q->orderByDesc('precio_desde'))
            ->when($this->orden === 'recientes', fn ($q) => $q->latest());
    }

    public function render()
    {
        if ($this->modoPaginado) {
            $productosScroll = (clone $this->baseQuery())->take(50)->get();
            $productosPaginados = (clone $this->baseQuery())->skip(50)->paginate(24);
        } else {
            $productosScroll = (clone $this->baseQuery())->take($this->mostrando)->get();
            $productosPaginados = null;
        }

        return view('livewire.storefront.catalogo', [
            'productosScroll' => $productosScroll,
            'productosPaginados' => $productosPaginados,
            'categorias' => Categoria::orderBy('orden')->get(),
        ]);
    }
}
