<x-public-layout theme="student" title="Kampus & Mahasiswa — PSU Career">
    <x-slot name="navbar">
        <div class="flex h-20 items-center justify-between">
            <a href="{{ route('landing.home') }}" class="text-lg font-bold">PSU Career</a>
            <div class="hidden items-center gap-8 text-sm font-medium md:flex">
                <a href="#kampus" class="hover:text-[#0E7C74]">Kampus</a>
                <a href="#cara-kerja" class="hover:text-[#0E7C74]">Cara Kerja</a>
                <a href="#cv" class="hover:text-[#0E7C74]">Template CV</a>
                <a href="#faq" class="hover:text-[#0E7C74]">FAQ</a>
                <a href="{{ route('login') }}" class="hover:text-[#0E7C74]">Masuk</a>
                <a href="{{ route('register') }}" class="rounded-full bg-[#0E7C74] px-5 py-2 text-white hover:bg-teal-700">Daftar</a>
            </div>
        </div>
    </x-slot>

    {{-- Hero --}}
    <section class="bg-[#12325B] text-white">
        <div class="mx-auto grid max-w-7xl grid-cols-1 items-center gap-12 px-4 py-20 sm:px-6 lg:grid-cols-2 lg:px-8">
            <div>
                <h1 class="text-4xl font-extrabold leading-tight sm:text-5xl">
                    Bangun Kariermu Sejak Kuliah
                </h1>
                <p class="mt-6 text-lg text-slate-300">
                    Lengkapi profil, buat CV profesional, dan temukan peluang karier melalui perusahaan mitra kampus.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('register') }}" class="rounded-full bg-[#0E7C74] px-6 py-3 font-semibold hover:bg-teal-700">
                        Buat Profil Sekarang
                    </a>
                    <a href="#cv" class="rounded-full border border-white/30 px-6 py-3 font-semibold hover:bg-white/10">
                        Lihat Template CV
                    </a>
                </div>
            </div>

            {{-- Preview kartu profil statis --}}
            <div class="rounded-xl bg-white p-6 text-slate-800 shadow-xl">
                <div class="flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#0E7C74] font-bold text-white">RA</div>
                    <div>
                        <p class="font-semibold text-slate-900">Rina Amelia</p>
                        <p class="text-sm text-slate-500">Sistem Informasi · Semester 6</p>
                    </div>
                    <span class="ml-auto rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-700">Terverifikasi</span>
                </div>
                <div class="mt-4">
                    <div class="flex items-center justify-between text-xs text-slate-500">
                        <span>Kelengkapan profil</span>
                        <span>85%</span>
                    </div>
                    <div class="mt-1 h-2 rounded-full bg-slate-100">
                        <div class="h-2 w-[85%] rounded-full bg-[#0E7C74]"></div>
                    </div>
                </div>
                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs">Laravel</span>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs">UI/UX</span>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs">SQL</span>
                </div>
                <div class="mt-5 flex gap-3">
                    <span class="flex-1 rounded-full bg-slate-100 py-2 text-center text-xs font-medium text-slate-600">Update Semester</span>
                    <span class="flex-1 rounded-full bg-[#0E7C74] py-2 text-center text-xs font-medium text-white">Download CV</span>
                </div>
                <div class="mt-4 rounded-lg bg-[#EEF4FB] px-3 py-2 text-xs text-slate-600">
                    2 perusahaan melihat profilmu minggu ini
                </div>
            </div>
        </div>
    </section>

    {{-- Kampus --}}
    <section id="kampus" class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-[#12325B]">Kampus</h2>
            <p class="mt-4 max-w-2xl text-slate-600">{{ $settings['campus_description'] ?? '[ISI campus_description]' }}</p>

            <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="aspect-video rounded-xl bg-slate-100"></div>
                <div class="aspect-video rounded-xl bg-slate-100"></div>
                <div class="aspect-video rounded-xl bg-slate-100"></div>
            </div>

            <div class="mt-10 grid grid-cols-2 gap-6 rounded-xl bg-[#EEF4FB] p-8 sm:grid-cols-4">
                @foreach ([
                    ['label' => 'Mahasiswa Aktif', 'value' => $stats['students']],
                    ['label' => 'Program Studi', 'value' => $stats['studyPrograms']],
                    ['label' => 'Alumni', 'value' => $settings['alumni_count'] ?? '[ISI alumni_count]'],
                    ['label' => 'Akreditasi', 'value' => $settings['accreditation'] ?? '[ISI accreditation]'],
                ] as $stat)
                    <div class="text-center">
                        <p class="text-2xl font-extrabold text-[#12325B]">{{ $stat['value'] }}</p>
                        <p class="mt-1 text-sm text-slate-600">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 flex flex-wrap gap-2">
                @foreach ($studyPrograms as $program)
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-sm text-slate-700">{{ $program->name }}</span>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Cara Kerja --}}
    <section id="cara-kerja" class="bg-[#12325B] text-white">
        <div class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold">Cara Kerja</h2>
            <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    'Daftar menggunakan NIM',
                    'Lengkapi profil & portfolio',
                    'Diverifikasi admin kampus',
                    'Ditemukan perusahaan mitra',
                ] as $i => $step)
                    <div class="rounded-xl border border-white/10 p-6">
                        <p class="text-sm font-semibold text-[#0E7C74]">{{ $i + 1 }}</p>
                        <p class="mt-2 font-medium">{{ $step }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CV Builder --}}
    <section id="cv" class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-[#12325B]">Template CV</h2>
            <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($cvTemplates as $template)
                    <div class="rounded-xl border border-slate-100 p-4 text-center">
                        <div class="flex aspect-[3/4] items-center justify-center rounded-lg bg-slate-100 text-sm text-slate-400">
                            {{ $template->name }}
                        </div>
                        <p class="mt-3 text-sm font-medium text-slate-900">{{ $template->name }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section id="faq" class="bg-[#EEF4FB]">
        <div class="mx-auto max-w-3xl px-4 py-20 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-[#12325B]">Pertanyaan Seputar Privasi</h2>
            <div class="mt-8 space-y-4">
                @foreach ($faqs as $faq)
                    <div class="rounded-xl bg-white p-5">
                        <p class="font-medium text-slate-900">{{ $faq->question }}</p>
                        <p class="mt-2 text-sm text-slate-600">{{ $faq->answer }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA Daftar --}}
    <section class="bg-white">
        <div class="mx-auto max-w-xl px-4 py-20 text-center sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-[#12325B]">Mulai Sekarang</h2>
            <p class="mt-3 text-slate-600">Masukkan NIM dan email kampus untuk mulai mendaftar.</p>
            <form method="GET" action="{{ route('register') }}" class="mt-6 flex flex-col gap-3 sm:flex-row">
                <input type="text" name="nim" placeholder="NIM" class="flex-1 rounded-full border-slate-300 shadow-sm focus:border-[#0E7C74] focus:ring-[#0E7C74]">
                <input type="email" name="email" placeholder="Email Kampus" class="flex-1 rounded-full border-slate-300 shadow-sm focus:border-[#0E7C74] focus:ring-[#0E7C74]">
                <button type="submit" class="rounded-full bg-[#0E7C74] px-6 py-2 font-semibold text-white hover:bg-teal-700">Daftar</button>
            </form>
        </div>
    </section>

    <footer class="bg-slate-900 py-10 text-slate-400">
        <div class="mx-auto max-w-7xl px-4 text-sm sm:px-6 lg:px-8">
            <p>{{ $settings['campus_name'] ?? '[ISI campus_name]' }}</p>
            <p class="mt-2">Mewakili perusahaan? Kunjungi <a href="{{ route('landing.companies') }}" class="text-[#0E7C74] hover:underline">halaman Perusahaan</a>.</p>
        </div>
    </footer>
</x-public-layout>
