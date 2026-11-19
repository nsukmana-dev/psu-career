<x-guest-layout>
    <h1 class="text-xl font-bold text-slate-900">Masuk</h1>
    <p class="mt-1 text-sm text-slate-500">Masuk ke akun mahasiswa, perusahaan, atau admin kamu.</p>

    <x-auth-session-status class="mt-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-[#2457C5] focus:ring-[#2457C5]">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="Password" />
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-[#2457C5] focus:ring-[#2457C5]">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center gap-2">
                <input id="remember_me" type="checkbox" name="remember" class="rounded border-slate-300 text-[#2457C5] focus:ring-[#2457C5]">
                <span class="text-sm text-slate-600">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-slate-500 hover:text-[#2457C5]" href="{{ route('password.request') }}">
                    Lupa password?
                </a>
            @endif
        </div>

        <button type="submit" class="w-full rounded-lg bg-[#2457C5] px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-[#2457C5] focus:ring-offset-2">
            Masuk
        </button>

        @if (Route::has('register'))
            <p class="text-center text-sm text-slate-500">
                Belum punya akun mahasiswa? <a href="{{ route('register') }}" class="font-medium text-[#2457C5] hover:underline">Daftar sekarang</a>
            </p>
        @endif
    </form>
</x-guest-layout>
