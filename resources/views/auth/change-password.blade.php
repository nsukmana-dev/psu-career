<x-guest-layout>
    <h1 class="text-xl font-bold text-slate-900">Buat Password Baru</h1>
    <p class="mt-1 text-sm text-slate-500">
        Ini adalah login pertama kamu. Silakan buat password baru sebelum melanjutkan ke dashboard.
    </p>

    <form method="POST" action="{{ route('password.change.update') }}" class="mt-6 space-y-4">
        @csrf

        <div>
            <x-input-label for="password" value="Password Baru" />
            <input id="password" type="password" name="password" required autocomplete="new-password" autofocus
                class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-[#2457C5] focus:ring-[#2457C5]">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Konfirmasi Password" />
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-[#2457C5] focus:ring-[#2457C5]">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit" class="w-full rounded-lg bg-[#2457C5] px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-[#2457C5] focus:ring-offset-2">
            Simpan Password Baru
        </button>
    </form>
</x-guest-layout>
