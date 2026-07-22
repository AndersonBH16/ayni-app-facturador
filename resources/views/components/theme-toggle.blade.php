<div>
    <button
        type="button"
        x-data="{ dark: document.documentElement.classList.contains('dark') }"
        @click="
        dark = !dark;
        document.documentElement.classList.toggle('dark', dark);
        localStorage.setItem('theme', dark ? 'dark' : 'light');
    "
        class="inline-flex items-center justify-center w-9 h-9 rounded-full text-gray-500 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 transition"
        aria-label="Cambiar tema"
    >
        {{-- Ícono sol: visible en modo oscuro, para volver a claro --}}
        <svg x-show="dark" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <circle cx="12" cy="12" r="5"/>
            <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
        </svg>
        {{-- Ícono luna: visible en modo claro, para ir a oscuro --}}
        <svg x-show="!dark" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
        </svg>
    </button>
</div>
