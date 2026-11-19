<x-guest-layout>
    <h1 class="text-xl font-bold text-slate-900">Lupa Password</h1>
    <p class="mt-1 text-sm text-slate-500">
        Masukkan email kamu, kami kirimkan tautan untuk membuat password baru.
    </p>

    <x-auth-session-status class="mt-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-4">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-[#2457C5] focus:ring-[#2457C5]">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <button type="submit" class="w-full rounded-lg bg-[#2457C5] px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-[#2457C5] focus:ring-offset-2">
            Kirim Tautan Reset Password
        </button>
    </form>
</x-guest-layout>
