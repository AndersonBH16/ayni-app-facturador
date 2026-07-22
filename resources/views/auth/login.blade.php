<div class="max-w-sm mx-auto mt-16 p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md border-t-4 border-brand-teal">
    <h1 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Inicia sesión</h1>

    @if ($error)
        <p class="text-sm text-red-500 mb-4">{{ $error }}</p>
    @endif

    <form wire:submit="ingresar" class="space-y-4">
        <div>
            <label class="text-sm text-gray-600 dark:text-gray-300">Correo electrónico</label>
            <input type="email" wire:model="email" class="w-full mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
        </div>

        <div>
            <label class="text-sm text-gray-600 dark:text-gray-300">Contraseña</label>
            <input type="password" wire:model="password" class="w-full mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
        </div>

        <button type="submit" class="w-full py-2 rounded-md bg-brand-teal hover:bg-brand-teal-dark text-white text-sm font-medium">
            Ingresar
        </button>
    </form>

    <p class="text-sm text-center mt-4 text-gray-500">
        ¿No tienes cuenta? <a href="{{ route('portal.registro') }}" wire:navigate class="text-brand-teal hover:underline">Regístrate</a>
    </p>
</div>
