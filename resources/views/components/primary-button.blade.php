<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-2.5 bg-primary-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800 transition ease-in-out duration-150 shadow-lg shadow-primary-500/20 dark:shadow-primary-900/20']) }}>
    {{ $slot }}
</button>
