<div>
    <!-- Banner rotativo -->
    <div
        x-data="{
        slide: 0,
        slides: [
            { img: 'https://picsum.photos/seed/banner1/1600/500', texto: 'Insumos frescos para tu restaurante, todos los días', overlay: 'from-brand-teal/90 to-transparent' },
            { img: 'https://picsum.photos/seed/banner2/1600/500', texto: 'Compara precios entre varios proveedores en un solo lugar', overlay: 'from-purple-700/90 to-transparent' },
            { img: 'https://picsum.photos/seed/banner3/1600/500', texto: 'Nuevos productos de tecnología para tu negocio', overlay: 'from-amber-600/90 to-transparent' },
        ],
        init() {
            setInterval(() => { this.slide = (this.slide + 1) % this.slides.length }, 5000);
        }
    }"
        class="relative h-40 sm:h-56 overflow-hidden"
    >
        <template x-for="(s, i) in slides" :key="i">
            <div x-show="slide === i" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0">
                <img :src="s.img" class="w-full h-full object-cover scale-110 animate-[kenburns_8s_ease-in-out_infinite_alternate]" alt="">
                <div class="absolute inset-0 bg-gradient-to-r flex items-center px-6 sm:px-12" :class="s.overlay">
                    <span class="text-white text-lg sm:text-3xl font-semibold max-w-lg" x-text="s.texto"></span>
                </div>
            </div>
        </template>
    </div>

    <!-- Barra de búsqueda y filtros -->
    <div class="sticky top-0 z-10 bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-700 px-4 py-3">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row gap-3 sm:items-center">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input
                    type="text"
                    wire:model.live.debounce.400ms="busqueda"
                    placeholder="Buscar productos..."
                    class="w-full pl-9 pr-3 py-2 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 focus:ring-brand-teal focus:border-brand-teal text-sm"
                >
            </div>

            <select wire:model.live="orden" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 text-sm">
                <option value="recientes">Más recientes</option>
                <option value="precio_asc">Precio: menor a mayor</option>
                <option value="precio_desc">Precio: mayor a menor</option>
            </select>

            <div wire:loading class="text-xs text-brand-teal flex items-center gap-1 shrink-0">
                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                Cargando...
            </div>
        </div>

        <div class="max-w-7xl mx-auto flex gap-2 mt-3 overflow-x-auto pb-1">
            @foreach ($categorias as $categoria)
                <button
                    wire:click="seleccionarCategoria('{{ $categoria->id }}')"
                    class="shrink-0 px-3 py-1.5 rounded-full text-xs font-medium border transition
                        {{ $categoriaId === $categoria->id
                            ? 'bg-brand-teal text-white border-brand-teal'
                            : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border-gray-200 dark:border-gray-600 hover:border-brand-teal' }}"
                >
                    {{ $categoria->nombre }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- Grid de productos -->
    <div class="max-w-7xl mx-auto px-4 py-6">
        <div wire:loading.class="opacity-50" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 transition-opacity">
            @forelse ($productosScroll as $producto)
                @php $masBarato = $producto->ofertas->first(); @endphp
                <div
                    x-data="{ show: false, modal: false }"
                    x-init="new IntersectionObserver(([e]) => { if (e.isIntersecting) show = true }, { threshold: 0.1 }).observe($el)"
                    class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-all duration-300"
                    :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                >
                    <button @click="modal = true" class="block w-full aspect-square bg-gray-50 dark:bg-gray-900 overflow-hidden">
                        <img src="{{ $producto->imagen }}" alt="{{ $producto->nombre }}" loading="lazy" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                    </button>

                    <div class="p-3">
                        <p class="text-xs text-brand-teal-dark dark:text-brand-teal-light font-medium mb-1">{{ $producto->categoria?->nombre }}</p>
                        <h3 class="text-sm font-medium text-gray-800 dark:text-gray-100 line-clamp-2 mb-2">{{ $producto->nombre }}</h3>

                        @if ($masBarato)
                            <div class="flex items-center gap-2 mb-1">
                <span class="bg-brand-teal text-white text-sm font-bold px-2 py-0.5 rounded-md">
                    S/ {{ number_format($masBarato->precio_mostrado, 2) }}
                </span>
                                <span class="text-xs text-gray-400">{{ $masBarato->empresa->razon_social }}</span>
                            </div>

                            @if ($producto->ofertas->count() > 1)
                                <p class="text-xs text-gray-400 mb-2">
                                    @foreach ($producto->ofertas->skip(1)->take(2) as $otra)
                                        <span class="line-through decoration-gray-300">S/ {{ number_format($otra->precio_mostrado, 2) }}</span>@if (!$loop->last), @endif
                                    @endforeach
                                    @if ($producto->ofertas->count() > 3)
                                        +{{ $producto->ofertas->count() - 3 }} más
                                    @endif
                                </p>
                            @endif

                            <button
                                wire:click="agregarAlCarrito('{{ $masBarato->id }}')"
                                @disabled($masBarato->controla_stock && $masBarato->stock_disponible <= 0)
                                class="w-full py-1.5 rounded-lg bg-brand-teal hover:bg-brand-teal-dark text-white text-xs font-medium transition disabled:opacity-40"
                            >
                                Agregar al carrito
                            </button>
                        @endif
                    </div>

                    <!-- Modal con todas las ofertas: este SÍ puede usar x-show, porque lo abre un click, no un observer -->
                    <div x-show="modal" x-cloak @click.self="modal = false" @keydown.escape.window="modal = false" class="fixed inset-0 bg-black/60 flex items-center justify-center z-30 px-4">
                        <div x-show="modal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="bg-white dark:bg-gray-800 rounded-xl max-w-lg w-full max-h-[85vh] overflow-y-auto">
                            <div class="aspect-video bg-gray-50 dark:bg-gray-900">
                                <img data-zoomable src="{{ $producto->imagen }}" alt="{{ $producto->nombre }}" class="w-full h-full object-cover cursor-zoom-in">
                            </div>
                            <div class="p-5">
                                <p class="text-xs text-brand-teal-dark dark:text-brand-teal-light font-medium mb-1">{{ $producto->categoria?->nombre }}</p>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $producto->nombre }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ $producto->descripcion }}</p>

                                <h4 class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">
                                    {{ $producto->ofertas->count() }} {{ $producto->ofertas->count() === 1 ? 'vendedor' : 'vendedores' }}
                                </h4>

                                <div class="space-y-2 mb-4">
                                    @foreach ($producto->ofertas as $oferta)
                                        <div class="flex items-center justify-between border border-gray-100 dark:border-gray-700 rounded-lg px-3 py-2">
                                            <span class="text-sm text-gray-700 dark:text-gray-200">{{ $oferta->empresa->razon_social }}</span>
                                            <div class="flex items-center gap-2">
                                                <span class="font-semibold text-gray-900 dark:text-white">S/ {{ number_format($oferta->precio_mostrado, 2) }}</span>
                                                <button
                                                    wire:click="agregarAlCarrito('{{ $oferta->id }}')"
                                                    @disabled($oferta->controla_stock && $oferta->stock_disponible <= 0)
                                                    class="px-3 py-1 rounded-md bg-brand-teal hover:bg-brand-teal-dark text-white text-xs disabled:opacity-40"
                                                >
                                                    Agregar
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="flex gap-2">
                                    <button @click="modal = false" class="flex-1 py-2 rounded-md border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 text-sm">
                                        Cerrar
                                    </button>
                                    <a href="{{ route('market.producto', $producto) }}" wire:navigate class="flex-1 py-2 rounded-md bg-gray-800 hover:bg-gray-900 dark:bg-gray-700 text-white text-sm text-center">
                                        Ver más
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="col-span-full text-center text-gray-400 py-12">No se encontraron productos.</p>
            @endforelse
        </div>

        @if (! $modoPaginado)
            <div
                wire:key="sentinel-{{ $mostrando }}"
                x-data
                x-init="new IntersectionObserver(([e]) => { if (e.isIntersecting) $wire.cargarMas() }, { threshold: 0.1 }).observe($el)"
                class="h-10"
            ></div>
        @endif

        @if ($modoPaginado && $productosPaginados)
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 mt-4">
                @foreach ($productosPaginados as $producto)
                    <a href="{{ route('market.producto', $producto) }}" wire:navigate class="block bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-shadow">
                        <div class="aspect-square bg-gray-50 dark:bg-gray-900 overflow-hidden">
                            <img src="{{ $producto->imagen }}" alt="{{ $producto->nombre }}" loading="lazy" class="w-full h-full object-cover">
                        </div>
                        <div class="p-3">
                            <p class="text-xs text-brand-teal-dark dark:text-brand-teal-light font-medium mb-1">{{ $producto->categoria?->nombre }}</p>
                            <h3 class="text-sm font-medium text-gray-800 dark:text-gray-100 line-clamp-2 mb-2">{{ $producto->nombre }}</h3>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">Desde S/ {{ number_format($producto->precio_desde, 2) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $productosPaginados->links() }}
            </div>
        @endif
    </div>
</div>
