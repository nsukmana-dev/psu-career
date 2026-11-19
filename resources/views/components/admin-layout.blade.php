@props(['title' => 'Dashboard'])

@php
    $navSections = [
        null => [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ],
        'Approval' => [
            ['label' => 'Verifikasi Profil', 'route' => 'admin.verifications.index', 'active' => 'admin.verifications.*', 'icon' => 'check'],
            ['label' => 'Update Request', 'route' => 'admin.update-requests.index', 'active' => 'admin.update-requests.*', 'icon' => 'refresh'],
            ['label' => 'Pengajuan Kemitraan', 'route' => 'admin.partnership-applications.index', 'active' => 'admin.partnership-applications.*', 'icon' => 'clipboard'],
            ['label' => 'Request Kontak', 'route' => 'admin.contact-requests.index', 'active' => 'admin.contact-requests.*', 'icon' => 'inbox'],
        ],
        'Kelola User' => [
            ['label' => 'Mahasiswa', 'route' => 'admin.students.index', 'active' => 'admin.students.*', 'icon' => 'users'],
            ['label' => 'Perusahaan', 'route' => 'admin.companies.index', 'active' => 'admin.companies.*', 'icon' => 'building'],
        ],
        'Master Data' => [
            ['label' => 'Program Studi', 'route' => 'admin.study-programs.index', 'icon' => 'book'],
            ['label' => 'Skill', 'route' => 'admin.skills.index', 'icon' => 'tag'],
            ['label' => 'Industri', 'route' => 'admin.industries.index', 'icon' => 'briefcase'],
            ['label' => 'Template CV', 'route' => 'admin.cv-templates.index', 'icon' => 'document'],
        ],
        'Lainnya' => [
            ['label' => 'Landing Page', 'route' => 'admin.landing-settings.edit', 'icon' => 'globe'],
            ['label' => 'FAQ', 'route' => 'admin.faqs.index', 'icon' => 'help'],
            ['label' => 'Log Aktivitas', 'route' => 'admin.activity-logs.index', 'icon' => 'clock'],
        ],
    ];

    $hour = now()->hour;
    $greetWord = match (true) {
        $hour < 11 => 'pagi',
        $hour < 15 => 'siang',
        $hour < 18 => 'sore',
        default => 'malam',
    };

    $initials = collect(explode(' ', auth()->user()->name))->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode('');
    $unread = auth()->user()->unreadNotifications;
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} — PSU Career</title>
    <link rel="icon" type="image/png" href="{{ asset('images/branding/logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-cream text-ink antialiased">
    <div class="flex min-h-screen" x-data="{ sidebarOpen: false }">
        <aside
            class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col bg-dock transition-transform lg:static lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="flex h-16 flex-shrink-0 items-center gap-2.5 px-6">
                <span class="h-8 w-8 flex-shrink-0 rounded-lg bg-gradient-to-br from-coral-500 to-coral-700"></span>
                <span class="text-base font-bold text-cream">PSU Career</span>
            </div>
            <nav class="flex-1 overflow-y-auto px-3 pb-6" aria-label="Navigasi utama">
                @foreach ($navSections as $heading => $items)
                    @if ($heading)
                        <p class="px-3 pb-1 pt-4 text-[11px] font-bold uppercase tracking-wider text-cream/35">{{ $heading }}</p>
                    @endif
                    @foreach ($items as $item)
                        @php $isActive = request()->routeIs($item['active'] ?? $item['route']); @endphp
                        <a
                            href="{{ route($item['route']) }}"
                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition {{ $isActive ? 'bg-coral-500 text-[#2A140C]' : 'text-cream/65 hover:bg-white/5 hover:text-cream' }}"
                        >
                            <x-admin.nav-icon :name="$item['icon']" class="h-4 w-4 flex-shrink-0" />
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                @endforeach
            </nav>
        </aside>

        <div class="min-w-0 flex-1">
            <div class="px-4 py-5 lg:px-9 lg:py-7">
                <header class="mb-6 flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <button type="button" class="mb-2 rounded-lg border border-line bg-white p-2 text-ink-soft lg:hidden" @click="sidebarOpen = !sidebarOpen" aria-label="Buka menu">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" /></svg>
                        </button>
                        <p class="text-xs font-semibold text-ink-soft">{{ now()->translatedFormat('l, j F Y') }}</p>
                        <h1 class="text-2xl font-bold tracking-tight">Selamat {{ $greetWord }}, {{ auth()->user()->name }}</h1>
                    </div>

                    <div class="flex items-center gap-2.5">
                        <div class="relative" x-data="{ open: false }">
                            <button type="button" class="relative rounded-full border border-line bg-white p-2.5 text-ink-soft shadow-sm hover:text-ink" aria-label="Notifikasi" @click="open = !open">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" /></svg>
                                @if ($unread->count() > 0)
                                    <span class="absolute -right-0.5 -top-0.5 inline-flex h-4 w-4 items-center justify-center rounded-full bg-bad text-[10px] font-bold text-white">{{ $unread->count() > 9 ? '9+' : $unread->count() }}</span>
                                @endif
                            </button>
                            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 top-12 z-30 w-80 rounded-2xl border border-line bg-white py-2 shadow-lg">
                                <div class="flex items-center justify-between px-4 pb-2">
                                    <span class="text-sm font-bold">Notifikasi</span>
                                    @if ($unread->count() > 0)
                                        <form method="POST" action="{{ route('notifications.mark-read') }}">
                                            @csrf
                                            <button type="submit" class="text-xs font-semibold text-coral-700 hover:underline">Tandai semua dibaca</button>
                                        </form>
                                    @endif
                                </div>
                                <div class="max-h-80 overflow-y-auto">
                                    @forelse (auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                                        <div class="border-t border-line px-4 py-2.5 text-sm {{ $notification->read_at ? 'text-ink-soft' : 'font-semibold text-ink' }}">
                                            {{ $notification->data['message'] ?? 'Notifikasi baru' }}
                                            <div class="text-xs font-normal text-ink-soft">{{ $notification->created_at->diffForHumans() }}</div>
                                        </div>
                                    @empty
                                        <p class="px-4 py-3 text-sm text-ink-soft">Belum ada notifikasi.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="relative" x-data="{ open: false }">
                            <button type="button" class="flex h-10 w-10 items-center justify-center rounded-full border border-line bg-surface text-sm font-bold text-coral-700" @click="open = !open">
                                {{ $initials }}
                            </button>
                            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 top-12 z-30 w-48 rounded-2xl border border-line bg-white py-1 shadow-lg">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-ink hover:bg-surface">Profil Akun</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-ink hover:bg-surface">Keluar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                @if (session('status'))
                    <div class="mb-5 rounded-2xl border border-good/30 bg-good-soft px-4 py-3 text-sm font-medium text-good">{{ session('status') }}</div>
                @endif
                @if (session('error'))
                    <div class="mb-5 rounded-2xl border border-bad/30 bg-bad-soft px-4 py-3 text-sm font-medium text-bad">{{ session('error') }}</div>
                @endif

                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
