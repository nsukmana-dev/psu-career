<x-public-layout theme="company" title="Talent Partner — PSU Career">
    <x-slot name="navbar">
        <div class="flex h-20 items-center justify-between">
            <a href="{{ route('landing.home') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/branding/logo.png') }}" alt="Logo PSU Bekasi" class="h-10 w-10 object-contain">
                <span class="leading-tight">
                    <span class="block text-lg font-bold">PSU Career</span>
                    <span class="block text-xs text-slate-400">Universitas Panca Sakti Bekasi</span>
                </span>
            </a>
            <div class="hidden items-center gap-8 text-sm font-medium md:flex">
                <a href="#talent-pool" class="hover:text-cyan-400">Talent Pool</a>
                <a href="#cara-bermitra" class="hover:text-cyan-400">Cara Bermitra</a>
                <a href="#faq" class="hover:text-cyan-400">FAQ</a>
                <a href="{{ route('login') }}" class="hover:text-cyan-400">Masuk Perusahaan</a>
                <a href="#mitra" class="inline-flex items-center gap-1.5 rounded-full bg-[#2457C5] px-5 py-2.5 font-semibold text-white hover:bg-blue-600">
                    Ajukan Kemitraan
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>
        </div>
    </x-slot>

    {{-- Hero --}}
    <section class="relative overflow-hidden bg-gradient-to-b from-[#071536] via-[#0A1F52] to-[#0C2A6B] pb-14">
        <div class="pointer-events-none absolute -left-32 -top-32 h-96 w-96 rounded-full bg-[#2457C5] opacity-20 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-20 top-20 h-96 w-96 rounded-full bg-blue-500 opacity-20 blur-3xl"></div>

        <div class="relative mx-auto grid max-w-7xl grid-cols-1 items-center gap-12 px-4 py-20 sm:px-6 lg:grid-cols-2 lg:px-8">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-1.5 text-xs font-medium text-slate-300">
                    <span class="h-1.5 w-1.5 rounded-full bg-cyan-400"></span>
                    Portal Talenta Universitas Panca Sakti Bekasi
                </span>

                <h1 class="mt-5 text-4xl font-extrabold leading-tight text-white sm:text-5xl">
                    Rekrut Talenta Terverifikasi dari <span class="text-sky-400">PSU Bekasi</span>
                </h1>
                <p class="mt-6 text-lg text-slate-400">
                    Cari, filter, dan ajukan kontak kandidat — kampus memfasilitasi pemenuhan kebutuhan talenta siap kerja untuk perusahaan Anda.
                </p>

                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="#mitra" class="inline-flex items-center gap-2 rounded-full bg-[#2457C5] px-6 py-3 font-semibold text-white hover:bg-blue-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Ajukan Kemitraan
                    </a>
                    <a href="#talent-pool" class="inline-flex items-center gap-2 rounded-full border border-white/20 px-6 py-3 font-semibold text-white hover:bg-white/10">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M19 11a8 8 0 11-16 0 8 8 0 0116 0z"/></svg>
                        Lihat Talent Pool
                    </a>
                </div>

                <div class="mt-10 grid grid-cols-3 gap-4 border-t border-white/10 pt-6">
                    <div class="flex items-center gap-2.5">
                        <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-[#2457C5]/30 text-sky-300">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                        </span>
                        <div>
                            <p class="text-lg font-extrabold text-white">{{ $stats['students'] }}</p>
                            <p class="text-xs text-slate-400">Talenta Terdaftar</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-[#2457C5]/30 text-sky-300">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 017.231-4.41 60.46 60.46 0 00-.491-6.347m-14.98 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-14.98 0a50.717 50.717 0 0114.98 0"/></svg>
                        </span>
                        <div>
                            <p class="text-lg font-extrabold text-white">{{ $stats['studyPrograms'] }}</p>
                            <p class="text-xs text-slate-400">Program Studi</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-[#2457C5]/30 text-sky-300">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.653v-2.994c0-1.09-.788-2.03-1.872-2.175a48.57 48.57 0 00-.831-.102m2.95 6.924l-3.722-1.86m-15.5 1.86a2.18 2.18 0 01-.75-1.653v-2.994c0-1.09.787-2.03 1.872-2.175a48.57 48.57 0 01.831-.102m-2.953 6.924l3.722-1.86m16.5 0v-5.2c0-1.1-.9-2-2-2H5.75c-1.1 0-2 .9-2 2v5.2m16.5 0l-3.722-1.86M5.75 14.15l3.722-1.86m0 0a48.473 48.473 0 015.056 0M9.472 12.29v-.95c0-.78.634-1.414 1.414-1.414h2.228c.78 0 1.414.633 1.414 1.414v.95"/></svg>
                        </span>
                        <div>
                            <p class="text-lg font-extrabold text-white">{{ $stats['companies'] }}</p>
                            <p class="text-xs text-slate-400">Perusahaan Mitra</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative">
                <div class="absolute -bottom-5 -left-5 h-32 w-32 rounded-full bg-amber-400"></div>
                <div class="absolute -right-4 -top-4 h-full w-full rounded-[3rem] rounded-tl-[5rem] bg-[#2457C5]/40"></div>
                <div class="absolute -left-4 -top-4 z-10 flex items-center gap-2 rounded-xl bg-[#0F1C33] px-4 py-3 shadow-xl ring-1 ring-white/10">
                    <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-emerald-500/20 text-emerald-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    </span>
                    <span class="text-xs font-semibold leading-tight text-white">Talenta<br>Terverifikasi oleh Kampus</span>
                </div>
                <img src="{{ asset('images/branding/hero-students.jpg') }}" alt="Mahasiswa Duta PSU Bekasi" class="relative aspect-[4/3] w-full rounded-[2.5rem] rounded-tl-[5rem] rounded-br-[4rem] object-cover shadow-2xl">
            </div>
        </div>
    </section>

    {{-- Feature strip — floats over the hero/content boundary --}}
    <div class="relative z-10 mx-auto -mt-12 max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-6 divide-y divide-slate-100 rounded-3xl bg-white p-6 shadow-2xl shadow-blue-900/20 sm:grid-cols-2 sm:divide-x sm:divide-y-0 lg:grid-cols-4">
            @foreach ([
                ['label' => 'Data Kandidat Terverifikasi Kampus', 'icon' => 'M9 12.75L11.25 15 15 9.75M21 12c0 4.556-3.04 8.312-7.19 9.606a1.5 1.5 0 01-1.62 0C7.04 20.312 4 16.556 4 12V6.75a1.5 1.5 0 01.9-1.374l6.75-3a1.5 1.5 0 011.3 0l6.75 3a1.5 1.5 0 01.9 1.374V12z'],
                ['label' => 'Filter Pencarian Sesuai Kebutuhan', 'icon' => 'M21 21l-4.35-4.35M19 11a8 8 0 11-16 0 8 8 0 0116 0z'],
                ['label' => 'CV dalam Format Seragam', 'icon' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z'],
                ['label' => 'Kontak Difasilitasi oleh Career Center', 'icon' => 'M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z'],
            ] as $item)
                <div class="flex items-center gap-3 pt-6 first:pt-0 sm:pt-0 sm:pl-6 sm:first:pl-0">
                    <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center text-[#2457C5]">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/></svg>
                    </span>
                    <p class="text-sm font-semibold text-slate-800">{{ $item['label'] }}</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Talent Pool --}}
    <section id="talent-pool" class="bg-white">
        <div class="mx-auto max-w-7xl px-4 pb-20 pt-16 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-end justify-between gap-2">
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-900">Talent Pool per Program Studi</h2>
                    <p class="mt-2 max-w-2xl text-slate-500">Jelajahi talenta unggulan berdasarkan program studi di Universitas Panca Sakti Bekasi.</p>
                </div>
                <a href="#mitra" class="inline-flex items-center gap-1 text-sm font-semibold text-[#2457C5] hover:underline">Lihat Semua Program Studi &rarr;</a>
            </div>

            <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($talentPool as $row)
                    <div class="group flex items-center gap-4 rounded-xl bg-white p-5 shadow-md ring-1 ring-slate-100 transition hover:bg-blue-50 hover:ring-[#2457C5]">
                        <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center text-[#2457C5]">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 017.231-4.41 60.46 60.46 0 00-.491-6.347M12 14.25L1.5 8.25 12 2.25l10.5 6-10.5 6z"/></svg>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate font-semibold text-slate-900">{{ $row['program']->name }}</p>
                            <p class="text-sm text-slate-500">
                                @if ($row['activeCount'] > 0)
                                    {{ $row['activeCount'] }} talenta aktif
                                @else
                                    Belum ada talenta aktif
                                @endif
                            </p>
                        </div>
                        <a href="#mitra" class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 text-[#2457C5] group-hover:bg-[#2457C5] group-hover:text-white">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Cara Bermitra --}}
    <section id="cara-bermitra" class="relative overflow-hidden bg-gradient-to-b from-[#0A1F52] to-[#0C2A6B]">
        <div class="pointer-events-none absolute right-0 top-0 h-96 w-96 rounded-full bg-[#2457C5] opacity-30 blur-3xl"></div>

        <div class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-white">Cara Bermitra</h2>
            <p class="mt-2 max-w-2xl text-slate-400">Proses mudah bagi perusahaan untuk mendapatkan talenta terbaik dari PSU Bekasi.</p>

            <div class="mt-10 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-5">
                @foreach ([
                    ['label' => 'Ajukan permohonan kemitraan', 'icon' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z'],
                    ['label' => 'Admin memverifikasi perusahaan', 'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['label' => 'Cari & filter kandidat', 'icon' => 'M21 21l-4.35-4.35M19 11a8 8 0 11-16 0 8 8 0 0116 0z'],
                    ['label' => 'Ajukan request kontak', 'icon' => 'M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5'],
                    ['label' => 'Admin memfasilitasi kontak', 'icon' => 'M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z'],
                ] as $i => $step)
                    <div class="relative flex flex-col items-center text-center">
                        <span class="flex h-14 w-14 items-center justify-center rounded-full bg-[#2457C5] text-white ring-4 ring-white/10">
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $step['icon'] }}"/></svg>
                        </span>
                        <p class="mt-3 text-sm font-semibold text-white">{{ $i + 1 }}. {{ $step['label'] }}</p>
                        @if (! $loop->last)
                            <span class="absolute -right-5 top-3 hidden text-2xl text-slate-400 lg:block">&rsaquo;</span>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- ponytail: no real partner companies onboarded yet — generic unbranded placeholders only, swap for real logos/testimonials once partnerships exist --}}
            <div class="mt-16 grid grid-cols-1 gap-10 lg:grid-cols-2">
                <div>
                    <h3 class="text-xl font-extrabold text-white">Perusahaan yang Telah Bermitra</h3>
                    <p class="mt-1 text-sm text-slate-300">Logo perusahaan mitra akan tampil di sini setelah pengajuan kemitraan disetujui admin kampus.</p>
                    <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-5">
                        @for ($i = 1; $i <= 5; $i++)
                            <div class="flex h-14 items-center justify-center rounded-lg bg-white text-xs font-bold text-slate-400 shadow">Logo Mitra {{ $i }}</div>
                        @endfor
                    </div>
                </div>
                <div class="flex gap-4 lg:border-l lg:border-white/20 lg:pl-10">
                    <span class="text-5xl font-extrabold leading-none text-sky-400">&ldquo;</span>
                    <div>
                        <p class="text-sm text-slate-100">"Contoh testimoni — akan diganti kutipan asli dari perusahaan mitra."</p>
                        <div class="mt-4 flex items-center gap-3">
                            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-white/20 text-sm font-bold text-white">HR</span>
                            <div>
                                <p class="text-sm font-semibold text-white">Nama Perwakilan HR</p>
                                <p class="text-xs text-slate-300">Contoh Perusahaan Mitra</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section id="faq" class="bg-white">
        <div class="mx-auto grid max-w-6xl grid-cols-1 gap-10 px-4 py-16 sm:px-6 lg:grid-cols-3 lg:px-8">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-900">Pertanyaan Umum</h2>
                <p class="mt-2 text-slate-500">Beberapa pertanyaan yang sering diajukan oleh perusahaan mitra.</p>
                <svg class="mt-6 h-20 w-20 text-[#2457C5]/30" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.5 2 2 5.8 2 10.5c0 2.4 1.2 4.6 3.1 6.1L4 21l4.6-2.2c1.1.3 2.2.4 3.4.4 5.5 0 10-3.8 10-8.5S17.5 2 12 2z"/></svg>
            </div>
            <div class="space-y-3 lg:col-span-2" x-data="{ open: 0 }">
                @foreach ($faqs as $i => $faq)
                    <div class="rounded-xl shadow-md ring-1 ring-slate-100 transition" x-bind:class="open === {{ $i }} ? 'bg-blue-100' : 'bg-white'">
                        <button type="button" class="flex w-full items-center justify-between gap-4 px-5 py-4 text-left" @click="open = open === {{ $i }} ? null : {{ $i }}">
                            <span class="font-semibold text-slate-900">{{ $faq->question }}</span>
                            <svg class="h-5 w-5 flex-shrink-0 text-slate-600 transition" x-bind:class="open === {{ $i }} ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                        </button>
                        <div x-show="open === {{ $i }}" x-transition>
                            <p class="px-5 pb-4 text-sm text-slate-600">{{ $faq->answer }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Form Kemitraan --}}
    <section id="mitra" class="bg-white px-4 pb-10 sm:px-6">
        <div class="mx-auto max-w-6xl rounded-[2rem] bg-gradient-to-br from-[#0A1F52] to-[#12388A] p-8 shadow-2xl sm:p-12">
            <div class="grid grid-cols-1 gap-10 lg:grid-cols-5">
                <div class="lg:col-span-2">
                    <span class="inline-block rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-slate-300">Ajukan Kemitraan</span>
                    <h2 class="mt-4 text-3xl font-extrabold text-white">Bermitra dengan Career Center PSU Bekasi</h2>
                    <p class="mt-3 text-slate-400">Isi formulir di samping, dan tim kami akan menghubungi Anda secepatnya.</p>
                </div>

                <div class="lg:col-span-3">
                    @if (session('status'))
                        <div class="mb-6 rounded-lg border border-emerald-800 bg-emerald-900/30 px-4 py-3 text-sm text-emerald-400">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('partnership.store') }}" class="space-y-4">
                        @csrf

                        {{-- Honeypot — kosong untuk pengguna asli, bot pengisi semua field akan tertangkap. --}}
                        <div class="absolute -left-[9999px]" aria-hidden="true">
                            <label for="website">Website</label>
                            <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-slate-300">Nama Perusahaan</label>
                                <input type="text" name="company_name" value="{{ old('company_name') }}" required placeholder="Contoh: PT Sukses Mandiri" class="mt-1 block w-full rounded-lg border border-white/20 bg-[#0B1F4B]/70 px-3 py-2.5 text-sm text-white shadow-sm placeholder:text-slate-400 focus:ring-2 focus:ring-[#2457C5]">
                                @error('company_name') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-300">Bidang Industri</label>
                                <select name="industry_id" class="mt-1 block w-full rounded-lg border-0 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm focus:ring-2 focus:ring-[#2457C5]">
                                    <option value="">Pilih industri</option>
                                    @foreach ($industries as $industry)
                                        <option value="{{ $industry->id }}" @selected((int) old('industry_id') === $industry->id)>{{ $industry->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-300">Nama PIC</label>
                                <input type="text" name="pic_name" value="{{ old('pic_name') }}" required placeholder="Nama lengkap PIC" class="mt-1 block w-full rounded-lg border border-white/20 bg-[#0B1F4B]/70 px-3 py-2.5 text-sm text-white shadow-sm placeholder:text-slate-400 focus:ring-2 focus:ring-[#2457C5]">
                                @error('pic_name') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-300">Jabatan PIC</label>
                                <input type="text" name="pic_position" value="{{ old('pic_position') }}" required placeholder="Contoh: HR Manager" class="mt-1 block w-full rounded-lg border border-white/20 bg-[#0B1F4B]/70 px-3 py-2.5 text-sm text-white shadow-sm placeholder:text-slate-400 focus:ring-2 focus:ring-[#2457C5]">
                                @error('pic_position') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-300">Email Kantor</label>
                                <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@perusahaan.com" class="mt-1 block w-full rounded-lg border border-white/20 bg-[#0B1F4B]/70 px-3 py-2.5 text-sm text-white shadow-sm placeholder:text-slate-400 focus:ring-2 focus:ring-[#2457C5]">
                                @error('email') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-300">Telepon</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="08xxxxxxxxxx" class="mt-1 block w-full rounded-lg border border-white/20 bg-[#0B1F4B]/70 px-3 py-2.5 text-sm text-white shadow-sm placeholder:text-slate-400 focus:ring-2 focus:ring-[#2457C5]">
                                @error('phone') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300">Keperluan</label>
                            <div class="mt-2 flex gap-6 text-sm text-slate-300">
                                <label class="flex items-center gap-2"><input type="checkbox" name="needs_internship" value="1" class="rounded border-slate-300 text-[#2457C5] focus:ring-[#2457C5]"> Magang</label>
                                <label class="flex items-center gap-2"><input type="checkbox" name="needs_fulltime" value="1" class="rounded border-slate-300 text-[#2457C5] focus:ring-[#2457C5]"> Full-time</label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300">Pesan (opsional)</label>
                            <textarea name="message" rows="3" placeholder="Tuliskan kebutuhan atau informasi tambahan..." class="mt-1 block w-full rounded-lg border border-white/20 bg-[#0B1F4B]/70 px-3 py-2.5 text-sm text-white shadow-sm placeholder:text-slate-400 focus:ring-2 focus:ring-[#2457C5]">{{ old('message') }}</textarea>
                        </div>

                        <div class="text-right"><button type="submit" class="inline-flex items-center gap-2 rounded-full bg-[#2457C5] px-6 py-3 font-semibold text-white hover:bg-blue-600">
                            Kirim Pengajuan
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                        </button></div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-[#071536] py-8 text-slate-400">
        <div class="mx-auto flex max-w-7xl flex-col gap-6 px-4 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/branding/logo.png') }}" alt="Logo PSU Bekasi" class="h-8 w-8 object-contain opacity-80">
                <span class="leading-tight">
                    <span class="block text-sm font-bold text-slate-300">PSU Career</span>
                    <span class="block text-xs">Universitas Panca Sakti Bekasi</span>
                </span>
            </div>
            <div class="flex flex-wrap gap-6 text-sm">
                <a href="#talent-pool" class="hover:text-slate-300">Talent Pool</a>
                <a href="#cara-bermitra" class="hover:text-slate-300">Cara Bermitra</a>
                <a href="#faq" class="hover:text-slate-300">FAQ</a>
                <a href="{{ route('login') }}" class="hover:text-slate-300">Masuk Perusahaan</a>
            </div>
            <p class="text-xs">{{ $settings['campus_name'] ?? '[ISI campus_name]' }}<br>&copy; {{ now()->year }}. All rights reserved.</p>
        </div>
    </footer>
</x-public-layout>
