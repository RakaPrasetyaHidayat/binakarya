<x-guest-layout>
    <x-auth-session-status class="mb-8" :status="session('status')" />

    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-8 text-center">Masuk</h2>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div>
            <x-input-label for="email" value="Email" class="text-gray-700 dark:text-gray-300 text-sm font-medium mb-2" />
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   placeholder="nama@example.com"
                   class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="Kata Sandi" class="text-gray-700 dark:text-gray-300 text-sm font-medium mb-2" />
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   placeholder="••••••••"
                   class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between pt-2">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-primary-600 dark:bg-slate-700 dark:border-slate-600 focus:ring-primary-500" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">Ingat Saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-colors" href="{{ route('password.request') }}">
                    Lupa Kata Sandi?
                </a>
            @endif
        </div>

        <button type="submit" class="w-full py-2.5 px-4 bg-primary-600 hover:bg-primary-700 dark:bg-primary-600 dark:hover:bg-primary-700 text-white font-semibold rounded-lg transition-all duration-200 mt-8">
            Masuk
        </button>
    </form>
</x-guest-layout>
