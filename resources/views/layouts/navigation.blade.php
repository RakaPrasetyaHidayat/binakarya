<nav x-data="{ 
        open: false, 
        darkMode: localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
        toggleDarkMode() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('darkMode', this.darkMode);
            document.documentElement.classList.toggle('dark', this.darkMode);
            window.dispatchEvent(new CustomEvent('darkModeChanged', { detail: { darkMode: this.darkMode } }));
        }
    }" 
     @darkmodechanged.window="darkMode = $event.detail.darkMode"
     class="transition-colors duration-300"
     :class="darkMode ? 'bg-slate-800 border-b border-slate-700' : 'bg-white border-b border-gray-100'">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('admin.dashboard') }}">
                        <x-application-logo :class="darkMode ? 'text-gray-200' : 'text-gray-800'" class="block h-9 w-auto fill-current transition-colors duration-300" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">
                <!-- Dark Mode Toggle -->
                <button @click="toggleDarkMode()"
                        class="relative inline-flex items-center gap-2 px-3 py-2 rounded-lg transition-all duration-300 border shadow-sm"
                        :class="darkMode ? 'bg-slate-700 border-slate-600 text-yellow-400 hover:bg-slate-600' : 'bg-gray-100 border-gray-200 text-gray-600 hover:bg-gray-200'">
                    <svg :class="!darkMode ? 'block' : 'hidden'" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.707.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zm5.657-9.193a1 1 0 00-1.414 1.414l.707.707a1 1 0 001.414-1.414l-.707-.707zm0 18.28a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM4 11a1 1 0 100-2H3a1 1 0 100 2h1z" clip-rule="evenodd"></path>
                    </svg>
                    <svg :class="darkMode ? 'block' : 'hidden'" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                </button>

                <x-dropdown align="right" width="48" :darkMode="$darkMode ?? false">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md transition ease-in-out duration-150"
                                :class="darkMode ? 'text-gray-300 bg-slate-700/50 hover:text-gray-100 hover:bg-slate-700 focus:text-gray-100 focus:bg-slate-700' : 'text-gray-700 bg-white hover:text-gray-900 focus:text-gray-900 focus:bg-gray-50'"
                                aria-label="User menu">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md transition duration-150 ease-in-out"
                        :class="darkMode ? 'text-gray-400 hover:text-gray-200 hover:bg-slate-700 focus:outline-none focus:bg-slate-700 focus:text-gray-200' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-900'"
                        aria-label="Toggle navigation">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden transition-colors duration-300"
         :class="darkMode ? 'bg-slate-700 border-t border-slate-600' : 'bg-white border-t border-gray-200'">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t transition-colors duration-300" :class="darkMode ? 'border-slate-600' : 'border-gray-200'">
            <div class="px-4">
                <div class="font-medium text-base transition-colors duration-300" :class="darkMode ? 'text-gray-200' : 'text-gray-800'">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm transition-colors duration-300" :class="darkMode ? 'text-gray-400' : 'text-gray-500'">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
