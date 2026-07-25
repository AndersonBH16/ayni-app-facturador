<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="flex flex-col sm:flex-row gap-6 mb-8">
        <div class="w-full sm:w-64 aspect-square bg-gray-50 dark:bg-gray-900 rounded-xl overflow-hidden shrink-0">
            <img data-zoomable src="{{ $producto->imagen }}" alt="{{ $producto->nombre }}" class="w-full h-full object-cover cursor-zoom-in">
        </div>

        <div>
            <p class="text-sm text-brand-teal-dark dark:text-brand-teal-light font-medium mb-1">{{ $producto->categoria?->nombre }}</p>
            <h1 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">{{ $producto->nombre }}</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm">{{ $producto->descripcion }}</p>
        </div>
    </div>

    @if ($mensaje)
        <div class="mb-4 text-sm text-brand-teal-dark bg-brand-teal-light/20 border border-brand-teal rounded-md px-4 py-2">
            {{ $mensaje }}
        </div>
    @endif

    <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">
        {{ $ofertas->count() }} {{ $ofertas->count() === 1 ? 'vendedor ofrece' : 'vendedores ofrecen' }} este producto
    </h2>

    <div class="space-y-3">
        @foreach ($ofertas as $oferta)
            <div class="flex items-center justify-between bg-white dark:bg-gray-800 rounded-lg border border-gray-100 dark:border-gray-700 px-4 py-3">
                <div>
                    <p class="font-medium text-gray-800 dark:text-gray-100">{{ $oferta->empresa->razon_social }}</p>
                    @if ($oferta->controla_stock && $oferta->stock_disponible <= 5 && $oferta->stock_disponible > 0)
                        <p class="text-xs text-amber-600">¡Solo quedan {{ $oferta->stock_disponible }}!</p>
                    @elseif ($oferta->controla_stock && $oferta->stock_disponible <= 0)
                        <p class="text-xs text-red-500">Sin stock</p>
                    @endif
                </div>

                <div class="flex items-center gap-4">
                    <span class="text-lg font-semibold text-gray-900 dark:text-white">
                        S/ {{ number_format($oferta->precio_mostrado, 2) }}
                    </span>
                    <button
                        wire:click="agregarAlCarrito('{{ $oferta->id }}')"
                        @disabled($oferta->controla_stock && $oferta->stock_disponible <= 0)
                        class="px-4 py-2 rounded-lg bg-brand-teal hover:bg-brand-teal-dark text-white text-sm font-medium transition disabled:opacity-40"
                    >
                        Agregar
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    @if ($mostrarFormularioRegistro)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-20 px-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 max-w-sm w-full">
                <h3 class="font-medium text-gray-800 dark:text-gray-100 mb-1">Es tu primera compra con este vendedor</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Necesitamos el RUC/DNI y razón social de tu empresa para esta compra.</p>

                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-300">RUC o DNI</label>
                        <input type="text" wire:model="rucInput" class="w-full mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
                        @error('rucInput') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-300">Razón social</label>
                        <input type="text" wire:model="razonSocialInput" class="w-full mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
                        @error('razonSocialInput') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex gap-2 mt-4">
                    <button wire:click="$set('mostrarFormularioRegistro', false)" class="flex-1 py-2 rounded-md border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 text-sm">
                        Cancelar
                    </button>
                    <button wire:click="confirmarRegistroYAgregar" class="flex-1 py-2 rounded-md bg-brand-teal hover:bg-brand-teal-dark text-white text-sm">
                        Confirmar y agregar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
