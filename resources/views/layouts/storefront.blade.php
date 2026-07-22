<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <script>
        (function () {
            const stored = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (stored === 'dark' || (!stored && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>
<body class="font-sans text-gray-900 dark:text-gray-100 antialiased bg-white dark:bg-brand-black transition-colors">
<header class="border-b border-gray-100 dark:border-gray-700 px-4 py-3 flex items-center justify-between">
    <a href="{{ route('storefront.catalogo') }}" wire:navigate class="flex items-center gap-2">
        <img src="{{ asset('images/ayni.jpg') }}" alt="Ayni Mikhuna" class="h-8 w-auto">
    </a>

    <div class="flex items-center gap-4">
        <x-theme-toggle />

        <livewire:storefront.carrito-indicador />

        @auth('portal')
            <form method="POST" action="{{ route('portal.logout') }}">
                @csrf
                <button type="submit" class="text-sm text-gray-500 hover:text-brand-teal">Cerrar sesión</button>
            </form>
        @else
            <a href="{{ route('portal.login') }}" wire:navigate class="text-sm text-gray-500 hover:text-brand-teal">Iniciar sesión</a>
        @endauth
    </div>
</header>

{{ $slot }}

@livewireScripts
</body>
</html>
