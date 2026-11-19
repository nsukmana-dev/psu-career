<x-dashboard-layout role="company" title="Dashboard Perusahaan">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <x-card>
            <p class="text-2xl font-bold text-[#2457C5]">{{ $shortlistsCount }}</p>
            <p class="mt-1 text-xs text-slate-500">Shortlist</p>
        </x-card>
        <x-card>
            <p class="text-2xl font-bold text-[#2457C5]">{{ $requestsPending }}</p>
            <p class="mt-1 text-xs text-slate-500">Request Kontak Menunggu</p>
        </x-card>
        <x-card>
            <p class="text-2xl font-bold text-[#2457C5]">{{ $requestsCompleted }}</p>
            <p class="mt-1 text-xs text-slate-500">Request Kontak Selesai</p>
        </x-card>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <x-card title="Kontak Terbaru">
            @if ($recentContacts->isEmpty())
                <p class="text-sm text-slate-500">Belum ada kontak yang dibagikan.</p>
            @else
                <ul class="divide-y divide-slate-100">
                    @foreach ($recentContacts as $item)
                        <li class="py-2">
                            <a href="{{ route('company.candidates.show', $item->student) }}" class="text-sm font-medium text-slate-900 hover:underline">
                                {{ $item->student->user->name }}
                            </a>
                            <p class="text-xs text-slate-500">{{ $item->student->studyProgram->name }} · {{ $item->decided_at->format('d/m/Y') }}</p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-card>

        <x-card title="Saved Search">
            @if ($savedSearches->isEmpty())
                <p class="text-sm text-slate-500">Belum ada pencarian tersimpan.</p>
            @else
                <ul class="divide-y divide-slate-100">
                    @foreach ($savedSearches as $search)
                        <li class="flex items-center justify-between py-2">
                            <span class="text-sm text-slate-900">{{ $search->name }}</span>
                            <a href="{{ route('company.saved-searches.apply', $search) }}" class="text-sm text-[#2457C5] hover:underline">Terapkan</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-card>
    </div>

    <div class="mt-6 flex gap-3">
        <a href="{{ route('company.candidates.index') }}">
            <x-primary-button>Cari Kandidat</x-primary-button>
        </a>
        <a href="{{ route('company.contact-requests.index') }}">
            <x-secondary-button>Request Kontak Saya</x-secondary-button>
        </a>
    </div>
</x-dashboard-layout>
