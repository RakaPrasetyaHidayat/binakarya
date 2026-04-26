<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>

<body
    class="font-sans text-gray-900 dark:text-gray-100 antialiased bg-white dark:bg-slate-800 transition-colors duration-300">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-50 dark:bg-slate-800">
        <div>
            <a href="/">
                @if($siteSettings->get('logo'))
                    <img src="{{ asset('storage/' . $siteSettings->get('logo')) }}" alt="Logo" class="h-16 w-auto">
                @else
                    <div class="flex flex-col items-center">
                        <span
                            class="text-3xl font-black text-blue-700 leading-none uppercase tracking-tight">Bina karya</span>
                        <span class="text-sm font-bold text-gray-400 leading-none tracking-widest uppercase">Cendekia</span>
                    </div>
                @endif
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-8">
            {{ $slot }}
        </div>
    </div>
</body>

</html>