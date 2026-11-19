<x-dashboard-layout role="company" title="Shortlist">
    <x-card title="Buat Shortlist Baru">
        <form method="POST" action="{{ route('company.shortlists.store') }}" class="flex flex-wrap items-end gap-4">
            @csrf
            <div class="flex-1 min-w-[200px]">
                <x-input-label for="name" value="Nama Shortlist" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required />
            </div>
            <div class="flex-1 min-w-[200px]">
                <x-input-label for="description" value="Deskripsi (opsional)" />
                <x-text-input id="description" name="description" type="text" class="mt-1 block w-full" />
            </div>
            <x-primary-button>Buat</x-primary-button>
        </form>
    </x-card>

    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($shortlists as $shortlist)
            <x-card>
                <p class="font-medium text-slate-900">{{ $shortlist->name }}</p>
                @if ($shortlist->description)
                    <p class="mt-1 text-sm text-slate-600">{{ $shortlist->description }}</p>
                @endif
                <p class="mt-2 text-xs text-slate-500">{{ $shortlist->items_count }} kandidat</p>
                <div class="mt-3 flex items-center justify-between">
                    <a href="{{ route('company.shortlists.show', $shortlist) }}" class="text-sm text-[#2457C5] hover:underline">Lihat Detail</a>
                    <form method="POST" action="{{ route('company.shortlists.destroy', $shortlist) }}" onsubmit="return confirm('Hapus shortlist ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-red-600 hover:underline">Hapus</button>
                    </form>
                </div>
            </x-card>
        @empty
            <p class="text-sm text-slate-500">Belum ada shortlist.</p>
        @endforelse
    </div>
</x-dashboard-layout>
