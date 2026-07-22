<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-brand-teal border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-brand-teal-dark focus:bg-brand-teal-dark active:bg-brand-teal-dark focus:outline-none focus:ring-2 focus:ring-brand-teal focus:ring-offset-2 dark:focus:ring-offset-brand-black disabled:opacity-50 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
