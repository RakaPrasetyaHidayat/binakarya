@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-primary-500 text-start text-base font-medium text-primary-700 dark:text-primary-400 bg-primary-50 dark:bg-slate-700/50 focus:outline-none focus:text-primary-800 dark:focus:text-primary-300 focus:bg-primary-100 dark:focus:bg-slate-700 focus:border-primary-700 dark:focus:border-primary-400 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-slate-700/50 hover:border-gray-300 dark:hover:border-slate-600 focus:outline-none focus:text-gray-800 dark:focus:text-gray-200 focus:bg-gray-50 dark:focus:bg-slate-700 focus:border-gray-300 dark:focus:border-slate-600 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
