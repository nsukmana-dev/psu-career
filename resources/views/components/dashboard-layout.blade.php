@props(['role', 'title' => 'Dashboard'])

@php
    $palette = match ($role) {
        'admin' => ['accent' => '#0F2A4A', 'accentText' => 'text-[#0F2A4A]', 'accentBg' => 'bg-[#0F2A4A]', 'ring' => 'focus:ring-[#0F2A4A]'],
        'student' => ['accent' => '#0E7C74', 'accentText' => 'text-[#0E7C74]', 'accentBg' => 'bg-[#0E7C74]', 'ring' => 'focus:ring-[#0E7C74]'],
        'company' => ['accent' => '#2457C5', 'accentText' => 'text-[#2457C5]', 'accentBg' => 'bg-[#2457C5]', 'ring' => 'focus:ring-[#2457C5]'],
    };

    $roleLabel = match ($role) {
        'admin' => 'Admin Career Center',
        'student' => 'Mahasiswa',
        'company' => 'Perusahaan',
    };

    // Tambahkan item navigasi di sini saat rute fase berikutnya tersedia.
    $navItems = match ($role) {
        'admin' => [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
            ['heading' => 'Approval'],
            ['label' => 'Verifikasi Profil', 'route' => 'admin.verifications.index', 'active' => 'admin.verifications.*'],
            ['label' => 'Update Request', 'route' => 'admin.update-requests.index', 'active' => 'admin.update-requests.*'],
            ['label' => 'Pengajuan Kemitraan', 'route' => 'admin.partnership-applications.index', 'active' => 'admin.partnership-applications.*'],
            ['label' => 'Request Kontak', 'route' => 'admin.contact-requests.index', 'active' => 'admin.contact-requests.*'],
            ['heading' => 'Kelola User'],
            ['label' => 'Mahasiswa', 'route' => 'admin.students.index', 'active' => 'admin.students.*'],
            ['label' => 'Perusahaan', 'route' => 'admin.companies.index', 'active' => 'admin.companies.*'],
            ['heading' => 'Master Data'],
            ['label' => 'Program Studi', 'route' => 'admin.study-programs.index'],
            ['label' => 'Skill', 'route' => 'admin.skills.index'],
            ['label' => 'Industri', 'route' => 'admin.industries.index'],
            ['label' => 'Template CV', 'route' => 'admin.cv-templates.index'],
            ['heading' => 'Lainnya'],
            ['label' => 'Landing Page', 'route' => 'admin.landing-settings.edit'],
            ['label' => 'FAQ', 'route' => 'admin.faqs.index'],
            ['label' => 'Log Aktivitas', 'route' => 'admin.activity-logs.index'],
        ],
        'student' => [
            ['label' => 'Dashboard', 'route' => 'student.dashboard'],
            ['label' => 'Profil Saya', 'route' => 'student.profile.edit', 'active' => 'student.profile.*'],
            ['label' => 'CV Saya', 'route' => 'student.cv.index', 'active' => 'student.cv.*'],
            ['label' => 'Riwayat Kontak', 'route' => 'student.contact-history.index'],
        ],
        'company' => [
            ['label' => 'Dashboard', 'route' => 'company.dashboard'],
            ['label' => 'Cari Kandidat', 'route' => 'company.candidates.index', 'active' => 'company.candidates.*'],
            ['label' => 'Shortlist', 'route' => 'company.shortlists.index', 'active' => 'company.shortlists.*'],
            ['label' => 'Request Kontak', 'route' => 'company.contact-requests.index', 'active' => 'company.contact-requests.*'],
            ['label' => 'Kontak Saya', 'route' => 'company.contacts.index'],
        ],
    };
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} — PSU Career</title>
    <link rel="icon" type="image/png" href="{{ asset('images/branding/logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">
    <div class="flex min-h-screen" x-data="{ sidebarOpen: false }">
        <aside
            class="fixed inset-y-0 left-0 z-40 w-64 -translate-x-full bg-white border-r border-slate-200 transition-transform lg:static lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="flex h-16 items-center gap-2 px-6 border-b border-slate-200">
                <span class="text-lg font-bold {{ $palette['accentText'] }}">PSU Career</span>
            </div>
            <nav class="px-3 py-4 space-y-1" aria-label="Navigasi utama">
                @foreach ($navItems as $item)
                    @if (isset($item['heading']))
                        <p class="px-3 pt-3 pb-1 text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $item['heading'] }}</p>
                    @else
                        <a
                            href="{{ route($item['route']) }}"
                            class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs($item['active'] ?? $item['route']) ? $palette['accentBg'].' text-white' : 'text-slate-600 hover:bg-slate-100' }}"
                        >
                            {{ $item['label'] }}
                        </a>
                    @endif
                @endforeach
            </nav>
        </aside>

        <div class="flex-1 lg:pl-0">
            <header class="flex h-16 items-center justify-between border-b border-slate-200 bg-white px-4 lg:px-8">
                <div class="flex items-center gap-3">
                    <button type="button" class="lg:hidden rounded p-2 text-slate-500 hover:bg-slate-100" @click="sidebarOpen = !sidebarOpen" aria-label="Buka menu">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
                        </svg>
                    </button>
                    <h1 class="text-lg font-semibold text-slate-900">{{ $title }}</h1>
                </div>

                <div class="flex items-center gap-4">
                    @php $unread = auth()->user()->unreadNotifications; @endphp
                    <div class="relative" x-data="{ open: false }">
                        <button type="button" class="relative rounded-full p-2 text-slate-500 hover:bg-slate-100" aria-label="Notifikasi" @click="open = !open">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                            </svg>
                            @if ($unread->count() > 0)
                                <span class="absolute right-0 top-0 inline-flex h-4 w-4 items-center justify-center rounded-full bg-red-600 text-[10px] font-medium text-white">
                                    {{ $unread->count() > 9 ? '9+' : $unread->count() }}
                                </span>
                            @endif
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 top-12 w-80 rounded-lg border border-slate-200 bg-white py-2 shadow-lg">
                            <div class="flex items-center justify-between px-4 pb-2">
                                <span class="text-sm font-semibold text-slate-900">Notifikasi</span>
                                @if ($unread->count() > 0)
                                    <form method="POST" action="{{ route('notifications.mark-read') }}">
                                        @csrf
                                        <button type="submit" class="text-xs text-[#2457C5] hover:underline">Tandai semua dibaca</button>
                                    </form>
                                @endif
                            </div>
                            <div class="max-h-80 overflow-y-auto">
                                @forelse (auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                                    <div class="border-t border-slate-100 px-4 py-2 text-sm {{ $notification->read_at ? 'text-slate-500' : 'text-slate-900 font-medium' }}">
                                        {{ $notification->data['message'] ?? 'Notifikasi baru' }}
                                        <div class="text-xs font-normal text-slate-400">{{ $notification->created_at->diffForHumans() }}</div>
                                    </div>
                                @empty
                                    <p class="px-4 py-3 text-sm text-slate-500">Belum ada notifikasi.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3" x-data="{ open: false }">
                        <button type="button" class="flex items-center gap-2 rounded-full text-sm" @click="open = !open">
                            <span class="hidden sm:block text-right">
                                <span class="block font-medium text-slate-900">{{ auth()->user()->name }}</span>
                                <span class="block text-xs text-slate-500">{{ $roleLabel }}</span>
                            </span>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak class="absolute right-4 top-14 w-48 rounded-lg border border-slate-200 bg-white py-1 shadow-lg">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Profil Akun</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-slate-700 hover:bg-slate-100">Keluar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="p-4 lg:p-8">
                @if (session('status'))
                    <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
