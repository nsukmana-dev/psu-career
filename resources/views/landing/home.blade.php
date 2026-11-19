<x-public-layout theme="home" title="PSU Career — Universitas Panca Sakti Bekasi">
    <x-slot name="navbar">
        <div class="flex h-20 items-center justify-between">
            <a href="{{ route('landing.home') }}" class="text-lg font-bold">PSU Career</a>
            <div class="hidden items-center gap-8 text-sm font-medium md:flex">
                <a href="#tentang" class="hover:text-[#F2A93B]">Tentang Kampus</a>
                <a href="{{ route('landing.students') }}" class="hover:text-[#F2A93B]">Mahasiswa</a>
                <a href="{{ route('landing.companies') }}" class="hover:text-[#F2A93B]">Perusahaan</a>
                <a href="{{ route('login') }}" class="rounded-md bg-[#F2A93B] px-4 py-2 text-[#0F2A4A] hover:bg-amber-400">Masuk</a>
            </div>
        </div>
    </x-slot>

    {{-- Hero --}}
    <section class="bg-[#0F2A4A] text-white">
        <div class="mx-auto grid max-w-7xl grid-cols-1 items-center gap-12 px-4 py-20 sm:px-6 lg:grid-cols-2 lg:px-8">
            <div>
                <h1 class="text-4xl font-extrabold leading-tight sm:text-5xl">
                    Menghubungkan Mahasiswa PSU Bekasi dengan Perusahaan Mitra
                </h1>
                <p class="mt-6 text-lg text-slate-300">
                    Career Center kampus memverifikasi profil mahasiswa dan memfasilitasi kontak dengan perusahaan — aman dan terpercaya.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('register') }}" class="rounded-md bg-[#F2A93B] px-6 py-3 font-semibold text-[#0F2A4A] hover:bg-amber-400">
                        Daftar sebagai Mahasiswa
                    </a>
                    <a href="{{ route('landing.companies') }}#mitra" class="rounded-md border border-white/30 px-6 py-3 font-semibold hover:bg-white/10">
                        Ajukan Akun Perusahaan
                    </a>
                </div>
            </div>

            {{-- Preview statis kartu Cari Kandidat --}}
            <div class="rounded-xl bg-white p-5 text-slate-800 shadow-xl">
                <p class="text-sm font-semibold text-slate-900">Cari Kandidat</p>
                <div class="mt-3 flex flex-wrap gap-2">
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs">Sistem Informasi</span>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs">IPK ≥ 3.0</span>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs">Laravel</span>
                </div>
                <div class="mt-4 space-y-2">
                    @foreach (['Rina A. — Sistem Informasi', 'Budi S. — Teknik Informatika', 'Dewi P. — Manajemen'] as $row)
                        <div class="flex items-center justify-between rounded-lg border border-slate-100 px-3 py-2 text-sm">
                            <span>{{ $row }}</span>
                            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs text-slate-500">Kontak via kampus</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Statistik --}}
    <section class="border-b border-slate-100 bg-white">
        <div class="mx-auto grid max-w-7xl grid-cols-2 gap-8 px-4 py-12 sm:px-6 lg:grid-cols-4 lg:px-8">
            @foreach ([
                ['label' => 'Mahasiswa Terdaftar', 'value' => $stats['students']],
                ['label' => 'Perusahaan Mitra', 'value' => $stats['companies']],
                ['label' => 'Program Studi', 'value' => $stats['studyPrograms']],
                ['label' => 'Mahasiswa Terhubung', 'value' => $stats['connected']],
            ] as $stat)
                <div class="text-center">
                    <p class="text-3xl font-extrabold text-[#0F2A4A]">{{ $stat['value'] }}</p>
                    <p class="mt-1 text-sm text-slate-500">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Tentang Kampus --}}
    <section id="tentang" class="bg-white">
        <div class="mx-auto grid max-w-7xl grid-cols-1 gap-12 px-4 py-20 sm:px-6 lg:grid-cols-2 lg:px-8">
            <div class="aspect-video rounded-xl bg-slate-100"></div>
            <div>
                <h2 class="text-2xl font-bold text-[#0F2A4A]">Tentang Kampus</h2>
                <p class="mt-4 text-slate-600">{{ $settings['campus_description'] ?? '[ISI campus_description]' }}</p>
                <div class="mt-6 flex flex-wrap gap-2">
                    @foreach ($studyPrograms as $program)
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-sm text-slate-700">{{ $program->name }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Untuk Mahasiswa --}}
    <section class="bg-slate-50">
        <div class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-[#0F2A4A]">Untuk Mahasiswa</h2>
            <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    'Bangun profil karier lengkap',
                    'Buat CV profesional otomatis',
                    'Terverifikasi oleh kampus',
                    'Ditemukan perusahaan mitra',
                ] as $feature)
                    <div class="rounded-xl bg-white p-6 shadow-sm">
                        <div class="mb-3 h-10 w-10 rounded-lg bg-[#F2A93B]/20"></div>
                        <p class="font-medium text-slate-900">{{ $feature }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Untuk Perusahaan --}}
    <section class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-[#0F2A4A]">Untuk Perusahaan</h2>
            <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    'Ajukan kemitraan',
                    'Cari & filter kandidat',
                    'Ajukan request kontak',
                    'Kampus memfasilitasi kontak',
                ] as $i => $step)
                    <div class="rounded-xl border border-slate-100 p-6">
                        <p class="text-sm font-semibold text-[#F2A93B]">Langkah {{ $i + 1 }}</p>
                        <p class="mt-2 font-medium text-slate-900">{{ $step }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA Kemitraan --}}
    <section class="bg-[#0F2A4A] text-white">
        <div class="mx-auto flex max-w-7xl flex-col items-center gap-6 px-4 py-16 text-center sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold">Tertarik Bermitra dengan PSU Career?</h2>
            <a href="{{ route('landing.companies') }}#mitra" class="rounded-md bg-[#F2A93B] px-6 py-3 font-semibold text-[#0F2A4A] hover:bg-amber-400">
                Ajukan Kemitraan
            </a>
        </div>
    </section>

    <footer class="bg-slate-900 py-10 text-slate-400">
        <div class="mx-auto max-w-7xl px-4 text-sm sm:px-6 lg:px-8">
            <p>{{ $settings['campus_name'] ?? '[ISI campus_name]' }} — {{ $settings['address'] ?? '[ISI address]' }}</p>
            <p class="mt-1">{{ $settings['career_center_email'] ?? '[ISI career_center_email]' }} · {{ $settings['career_center_phone'] ?? '[ISI career_center_phone]' }}</p>
            <p class="mt-4"><a href="{{ route('privacy-policy') }}" class="hover:text-white">Kebijakan Privasi</a></p>
        </div>
    </footer>
</x-public-layout>
