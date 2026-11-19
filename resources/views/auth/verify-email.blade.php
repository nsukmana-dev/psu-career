<x-guest-layout>
    <h1 class="text-xl font-bold text-slate-900">Verifikasi Email</h1>
    <p class="mt-1 text-sm text-slate-500">
        Terima kasih telah mendaftar! Sebelum memulai, silakan verifikasi alamat email kamu dengan mengklik
        tautan yang baru saja kami kirimkan. Kalau belum menerima emailnya, kami bisa kirimkan yang baru.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="mt-4 text-sm font-medium text-emerald-600">
            Tautan verifikasi baru telah dikirim ke alamat email yang kamu gunakan saat mendaftar.
        </div>
    @endif

    <div class="mt-6 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="rounded-lg bg-[#2457C5] px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-[#2457C5] focus:ring-offset-2">
                Kirim Ulang Email Verifikasi
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-slate-500 underline hover:text-slate-800">
                Keluar
            </button>
        </form>
    </div>
</x-guest-layout>
