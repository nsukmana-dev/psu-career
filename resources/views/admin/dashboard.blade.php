@php $maxCount = max(1, $registrations->max('count')); @endphp

<x-admin-layout title="Dashboard Admin">
    <div class="mb-6 grid grid-cols-2 gap-3.5 lg:grid-cols-5">
        @foreach (\App\Enums\VerificationStatus::cases() as $status)
            <x-admin.stat-card :label="'Mahasiswa '.$status->label()" :value="$statusCounts[$status->value] ?? 0" />
        @endforeach
        <x-admin.stat-card label="Perusahaan Aktif" :value="$companiesActive" />
    </div>

    <div class="mb-6 grid grid-cols-1 gap-3.5 lg:grid-cols-3">
        <x-admin.stat-card label="Kontak Dibagikan Bulan Ini" :value="$contactsThisMonth" trend="mahasiswa ↔ perusahaan" />
        <x-admin.card class="lg:col-span-2">
            <p class="mb-1 text-xs font-bold text-ink-soft">Ekspor Data</p>
            <div class="flex flex-wrap gap-2.5">
                <x-admin.btn as="a" href="{{ route('admin.export.students') }}" variant="ghost">Unduh CSV Mahasiswa Terverifikasi</x-admin.btn>
                <x-admin.btn as="a" href="{{ route('admin.export.contact-requests') }}" variant="ghost">Unduh CSV Rekap Request Kontak</x-admin.btn>
            </div>
        </x-admin.card>
    </div>

    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        <x-admin.card title="Antrian Tugas">
            <ul class="divide-y divide-line">
                @foreach ($queue as $task)
                    <li class="flex items-center justify-between py-2.5">
                        <a href="{{ route($task['route']) }}" class="text-sm font-semibold text-ink hover:text-coral-700">{{ $task['label'] }}</a>
                        <x-admin.pill :color="$task['count'] > 0 ? 'warn' : 'slate'">{{ $task['count'] }}</x-admin.pill>
                    </li>
                @endforeach
            </ul>
        </x-admin.card>

        <x-admin.card title="Pendaftaran Mahasiswa per Bulan">
            <div class="flex h-40 items-end gap-3">
                @foreach ($registrations as $month)
                    <div class="flex flex-1 flex-col items-center gap-1.5">
                        <span class="text-xs font-bold text-ink-soft">{{ $month['count'] }}</span>
                        <div class="w-full rounded-t-lg bg-gradient-to-t from-coral-700 to-coral-400" style="height: {{ max(4, $month['count'] / $maxCount * 100) }}px"></div>
                        <span class="text-xs text-ink-soft">{{ $month['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </x-admin.card>
    </div>
</x-admin-layout>
