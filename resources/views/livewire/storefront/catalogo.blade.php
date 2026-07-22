<div>
    <!-- Banner rotativo -->
    <div
        x-data="{
            slide: 0,
            slides: [
                { texto: 'Insumos frescos para tu restaurante, todos los días', color: 'from-brand-teal to-brand-teal-dark' },
                { texto: 'Compara precios entre varios proveedores en un solo lugar', color: 'from-brand-teal-dark to-gray-800' },
                { texto: 'Nuevos productos de tecnología para tu negocio', color: 'from-gray-800 to-brand-teal' },
            ],
            init() {
                setInterval(() => { this.slide = (this.slide + 1) % this.slides.length }, 4000);
            }
        }"
        class="relative h-32 sm:h-40 overflow-hidden"
    >
        <template x-for="(s, i) in slides" :key="i">
            <div
                x-show="slide === i"
                x-transition:enter="transition ease-out duration-700"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-500"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="absolute inset-0 flex items-center justify-center bg-gradient-to-r text-white text-lg sm:text-2xl font-semibold px-6 text-center"
                :class="s.color"
            >
                <span x-text="s.texto"></span>
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
                <div
                    x-data="{ show: false, modal: false }"
                    x-intersect="show = true"
                    x-show="show"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-shadow"
                >
                    <button @click="modal = true" class="block w-full aspect-square bg-gray-50 dark:bg-gray-900 overflow-hidden">
                        <img
                            src="{{ $producto->imagen }}"
                            alt="{{ $producto->nombre }}"
                            loading="lazy"
                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                        >
                    </button>

                    <div class="p-3">
                        <p class="text-xs text-brand-teal-dark dark:text-brand-teal-light font-medium mb-1">
                            {{ $producto->categoria?->nombre }}
                        </p>
                        <h3 class="text-sm font-medium text-gray-800 dark:text-gray-100 line-clamp-2 mb-2">
                            {{ $producto->nombre }}
                        </h3>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">
                            Desde S/ {{ number_format($producto->precio_desde, 2) }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            {{ $producto->oferentes_count }} {{ $producto->oferentes_count === 1 ? 'vendedor' : 'vendedores' }}
                        </p>
                    </div>

                    <!-- Modal de detalle rápido -->
                    <div
                        x-show="modal"
                        x-cloak
                        @click.self="modal = false"
                        @keydown.escape.window="modal = false"
                        class="fixed inset-0 bg-black/60 flex items-center justify-center z-30 px-4"
                    >
                        <div
                            x-show="modal"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            class="bg-white dark:bg-gray-800 rounded-xl max-w-lg w-full overflow-hidden"
                        >
                            <div class="aspect-video bg-gray-50 dark:bg-gray-900">
                                <img src="{{ $producto->imagen }}" alt="{{ $producto->nombre }}" class="w-full h-full object-cover">
                            </div>
                            <div class="p-5">
                                <p class="text-xs text-brand-teal-dark dark:text-brand-teal-light font-medium mb-1">
                                    {{ $producto->categoria?->nombre }}
                                </p>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $producto->nombre }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ $producto->descripcion }}</p>
                                <p class="text-sm text-gray-500 mb-4">
                                    Desde <span class="font-semibold text-gray-900 dark:text-white">S/ {{ number_format($producto->precio_desde, 2) }}</span>
                                    · {{ $producto->oferentes_count }} {{ $producto->oferentes_count === 1 ? 'vendedor' : 'vendedores' }}
                                </p>
                                <div class="flex gap-2">
                                    <button @click="modal = false" class="flex-1 py-2 rounded-md border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 text-sm">
                                        Cerrar
                                    </button>
                                    <a href="{{ route('storefront.producto', $producto) }}" wire:navigate class="flex-1 py-2 rounded-md bg-brand-teal hover:bg-brand-teal-dark text-white text-sm text-center">
                                        Ver ofertas y comprar
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
            <div wire:key="sentinel-{{ $mostrando }}" x-intersect="$wire.cargarMas()" class="h-10"></div>
        @endif

        @if ($modoPaginado && $productosPaginados)
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 mt-4">
                @foreach ($productosPaginados as $producto)
                    <a href="{{ route('storefront.producto', $producto) }}" wire:navigate class="block bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-shadow">
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
