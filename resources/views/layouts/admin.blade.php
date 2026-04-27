<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script>
        if (localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="font-sans antialiased transition-colors duration-300 bg-slate-50 dark:bg-slate-800 text-gray-900 dark:text-slate-100" 
      x-data="{ sidebarOpen: false, darkMode: localStorage.getItem('darkMode') === 'true' }"
      x-init="$watch('darkMode', val => { localStorage.setItem('darkMode', val); document.documentElement.classList.toggle('dark', val); })">

<div class="flex h-screen overflow-hidden">
    {{-- Sidebar --}}
    <div x-show="sidebarOpen" class="fixed inset-0 z-40 bg-gray-900/50 lg:hidden" @click="sidebarOpen = false"></div>
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 flex flex-col flex-shrink-0 transition-transform duration-300 lg:static lg:translate-x-0"
           :class="darkMode ? 'bg-slate-900 border-slate-800' : 'bg-white border-gray-200 shadow-xl'" style="border-right: 1px solid;">
        <div class="h-20 flex items-center px-6 border-b transition-colors" :class="darkMode ? 'border-slate-800' : 'border-gray-100'">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                <div class="flex flex-col">
                    <span class="font-bold text-lg leading-tight" :class="darkMode ? 'text-white' : 'text-black'">Bina Karya</span>
                    <span class="text-primary-600 font-bold text-sm leading-tight">Cendekia</span>
                </div>
            </a>
        </div>
        <nav class="flex-1 overflow-y-auto py-8 px-4 space-y-1.5 text-sm">
            @php
                $allNavItems = [
                    ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'grid-outline', 'roles' => ['admin', 'contributor']],
                    ['route' => 'admin.books.index', 'label' => 'Katalog Buku', 'icon' => 'book-outline', 'roles' => ['admin']],
                    ['route' => 'admin.posts.index', 'label' => 'Artikel Blog', 'icon' => 'newspaper-outline', 'roles' => ['admin', 'contributor']],
                    ['route' => 'admin.services.index', 'label' => 'Layanan', 'icon' => 'briefcase-outline', 'roles' => ['admin']],
                    ['route' => 'admin.pages.index', 'label' => 'Halaman Statis', 'icon' => 'document-text-outline', 'roles' => ['admin']],
                    ['route' => 'admin.team-members.index', 'label' => 'Struktur Anggota', 'icon' => 'people-outline', 'roles' => ['admin']],
                    ['route' => 'admin.categories.index', 'label' => 'Kategori', 'icon' => 'pricetags-outline', 'roles' => ['admin']],
                    ['route' => 'admin.menus.index', 'label' => 'Menu Navigasi', 'icon' => 'menu-outline', 'roles' => ['admin']],
                    ['route' => 'admin.menu-builder.index', 'label' => 'Menu Builder', 'icon' => 'construct-outline', 'roles' => ['admin']],
                    ['route' => 'admin.contacts.index', 'label' => 'Pesan Masuk', 'icon' => 'mail-outline', 'roles' => ['admin']],
                ];

                $userRole = auth()->user()->role;
                $navItems = array_filter($allNavItems, function($item) use ($userRole) {
                    return in_array($userRole, $item['roles']);
                });
            @endphp
            @foreach($navItems as $item)
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition font-medium {{ request()->routeIs($item['route']) ? 'bg-primary-600 text-white shadow-lg shadow-primary-900/50' : '' }}"
                   :class="!{{ json_encode(request()->routeIs($item['route'])) }} && (darkMode ? 'text-gray-300 hover:text-white hover:bg-slate-800' : 'text-gray-800 hover:text-primary-600 hover:bg-gray-50')">
                    <ion-icon name="{{ $item['icon'] }}" class="w-5 h-5"></ion-icon>
                    {{ $item['label'] }}
                </a>
            @endforeach

            @if(auth()->user()->isAdmin())
                <div class="mt-6 pt-6 border-t" :class="darkMode ? 'border-gray-700/50' : 'border-gray-100'">
                    <p class="px-3 text-xs font-semibold uppercase tracking-wider mb-3 transition-colors" :class="darkMode ? 'text-gray-400' : 'text-gray-500'">Pengaturan</p>
                    
                    <a href="{{ route('admin.settings.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition font-medium {{ request()->routeIs('admin.settings.*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-900/50' : '' }}"
                       :class="!{{ json_encode(request()->routeIs('admin.settings.*')) }} && (darkMode ? 'text-gray-300 hover:bg-slate-800' : 'text-gray-800 hover:bg-gray-50')">
                        <ion-icon name="settings-outline" class="w-5 h-5"></ion-icon>
                        Pengaturan Global
                    </a>
                    
                    <a href="{{ route('admin.mail-settings.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition font-medium {{ request()->routeIs('admin.mail-settings.*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-900/50' : '' }}"
                       :class="!{{ json_encode(request()->routeIs('admin.mail-settings.*')) }} && (darkMode ? 'text-gray-300 hover:bg-slate-800' : 'text-gray-800 hover:bg-gray-50')">
                        <ion-icon name="mail-outline" class="w-5 h-5"></ion-icon>
                        Pengaturan Email
                    </a>
                    
                    <a href="{{ route('admin.scholar-settings.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition font-medium {{ request()->routeIs('admin.scholar-settings.*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-900/50' : '' }}"
                       :class="!{{ json_encode(request()->routeIs('admin.scholar-settings.*')) }} && (darkMode ? 'text-gray-300 hover:bg-slate-800' : 'text-gray-800 hover:bg-gray-50')">
                        <ion-icon name="school-outline" class="w-5 h-5"></ion-icon>
                        Google Scholar
                    </a>
                    
                    <a href="{{ route('admin.tinymce-settings.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition font-medium {{ request()->routeIs('admin.tinymce-settings.*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-900/50' : '' }}"
                       :class="!{{ json_encode(request()->routeIs('admin.tinymce-settings.*')) }} && (darkMode ? 'text-gray-300 hover:bg-slate-800' : 'text-gray-800 hover:bg-gray-50')">
                        <ion-icon name="code-slash-outline" class="w-5 h-5"></ion-icon>
                        Pengaturan TinyMCE
                    </a>

                    <a href="{{ route('admin.users.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition font-medium {{ request()->routeIs('admin.users.*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-900/50' : '' }}"
                       :class="!{{ json_encode(request()->routeIs('admin.users.*')) }} && (darkMode ? 'text-gray-300 hover:bg-slate-800' : 'text-gray-800 hover:bg-gray-50')">
                        <ion-icon name="people-circle-outline" class="w-5 h-5"></ion-icon>
                        Pengguna
                    </a>
                    
                    <a href="{{ route('admin.audit-logs.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition font-medium {{ request()->routeIs('admin.audit-logs.*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-900/50' : '' }}"
                       :class="!{{ json_encode(request()->routeIs('admin.audit-logs.*')) }} && (darkMode ? 'text-gray-300 hover:bg-slate-800' : 'text-gray-800 hover:bg-gray-50')">
                        <ion-icon name="shield-checkmark-outline" class="w-5 h-5"></ion-icon>
                        Logs Aktivitas
                    </a>
                </div>
            @endif
        </nav>
        <div class="p-4 border-t transition-colors" :class="darkMode ? 'border-slate-800' : 'border-gray-100'">
            <p class="font-medium text-sm transition-colors" :class="darkMode ? 'text-gray-200' : 'text-gray-900'">{{ auth()->user()->name }}</p>
            <p class="capitalize text-xs mt-0.5 transition-colors" :class="darkMode ? 'text-gray-400' : 'text-gray-500'">{{ auth()->user()->role }}</p>
            <form method="POST" action="{{ route('logout') }}" class="mt-3" @submit.prevent="if(confirm('Apakah Anda yakin ingin keluar dari panel admin?')) $el.submit()">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 rounded-lg transition-all font-medium flex items-center gap-2"
                        :class="darkMode ? 'text-red-400 hover:bg-red-500/10 hover:text-red-300' : 'text-red-600 hover:bg-red-50 hover:text-red-700'">
                    <ion-icon name="log-out-outline" class="text-lg"></ion-icon>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- Main content --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        <header :class="darkMode ? 'bg-slate-800 border-slate-700' : 'bg-white border-gray-100'" class="h-16 shadow-soft flex items-center justify-between px-4 lg:px-6 border-b gap-4 transition-colors duration-300">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden focus:outline-none flex items-center" :class="darkMode ? 'text-gray-400 hover:text-gray-200' : 'text-gray-600 hover:text-primary-600'">
                    <ion-icon name="menu-outline" class="text-2xl"></ion-icon>
                </button>
                <h1 :class="darkMode ? 'text-gray-100' : 'text-black'" class="text-xl font-bold">@yield('title', 'Dashboard')</h1>
            </div>
            <div class="flex items-center gap-3">
                {{-- Dark Mode Toggle --}}
                <button @click="darkMode = !darkMode" 
                        class="relative w-9 h-9 sm:w-8 sm:h-8 rounded-lg transition-all duration-300 border flex items-center justify-center shadow-sm hover:scale-105 active:scale-95"
                        :class="darkMode ? 'bg-slate-700 border-slate-600 text-yellow-300' : 'bg-gray-100 border-gray-200 text-amber-500'"
                        title="Alihkan tema">
                    {{-- Sun icon (light mode) --}}
                    <svg x-show="!darkMode" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.707.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                    </svg>
                    {{-- Moon icon (dark mode) --}}
                    <svg x-show="darkMode" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                    </svg>
                </button>
            </div>
        </header>
        <main :class="darkMode ? 'bg-slate-800' : 'bg-gray-50'" class="flex-1 overflow-y-auto p-6 transition-colors duration-300">
            @if(session('success'))
                <div :class="darkMode ? 'bg-green-900/20 border-green-800 text-green-400' : 'bg-green-50 border-green-200 text-green-700'" class="mb-4 border px-4 py-3 rounded-lg text-sm font-medium flex items-center gap-3 transition-colors">
                    <ion-icon name="checkmark-circle" class="text-lg"></ion-icon>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div :class="darkMode ? 'bg-red-900/20 border-red-800 text-red-400' : 'bg-red-50 border-red-200 text-red-700'" class="mb-4 border px-4 py-3 rounded-lg text-sm font-medium flex items-center gap-3 transition-colors">
                    <ion-icon name="alert-circle" class="text-lg"></ion-icon>
                    {{ session('error') }}
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>

@yield('scripts')
</body>
</html>
