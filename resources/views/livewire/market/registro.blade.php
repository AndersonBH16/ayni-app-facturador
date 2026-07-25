<div class="max-w-sm mx-auto mt-16 p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md border-t-4 border-brand-teal">
    <h1 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Crea tu cuenta de comprador</h1>

    <form wire:submit="registrar" class="space-y-4">
        <div>
            <label class="text-sm text-gray-600 dark:text-gray-300">Nombre completo</label>
            <input type="text" wire:model="name" class="w-full mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
            @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-sm text-gray-600 dark:text-gray-300">Correo electrónico</label>
            <input type="email" wire:model="email" class="w-full mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
            @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-sm text-gray-600 dark:text-gray-300">Contraseña</label>
            <input type="password" wire:model="password" class="w-full mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
            @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-sm text-gray-600 dark:text-gray-300">Confirmar contraseña</label>
            <input type="password" wire:model="password_confirmation" class="w-full mt-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
        </div>

        <button type="submit" class="w-full py-2 rounded-md bg-brand-teal hover:bg-brand-teal-dark text-white text-sm font-medium">
            Crear cuenta
        </button>
    </form>

    <p class="text-sm text-center mt-4 text-gray-500">
        ¿Ya tienes cuenta? <a href="{{ route('portal.login') }}" wire:navigate class="text-brand-teal hover:underline">Inicia sesión</a>
    </p>
</div>
