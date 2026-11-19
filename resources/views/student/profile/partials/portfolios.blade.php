<x-card title="Tambah Portfolio">
    <form method="POST" action="{{ route('student.portfolios.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <x-input-label for="title" value="Judul" />
                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" required />
            </div>
            <div>
                <x-input-label for="url" value="URL (opsional)" />
                <x-text-input id="url" name="url" type="url" class="mt-1 block w-full" />
            </div>
        </div>

        <div>
            <x-input-label for="portfolio_description" value="Deskripsi" />
            <textarea id="portfolio_description" name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2457C5] focus:ring-[#2457C5]"></textarea>
        </div>

        <div>
            <x-input-label for="thumbnail" value="Thumbnail (opsional)" />
            <input id="thumbnail" name="thumbnail" type="file" accept="image/*" class="mt-1 block text-sm">
            <x-input-error :messages="$errors->get('thumbnail')" class="mt-1" />
        </div>

        <x-primary-button>Tambah Portfolio</x-primary-button>
    </form>
</x-card>

<div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
    @forelse ($student->portfolios as $portfolio)
        <x-card>
            @if ($portfolio->thumbnail_path)
                <img src="{{ \Illuminate\Support\Facades\Storage::url($portfolio->thumbnail_path) }}" alt="{{ $portfolio->title }}" class="mb-3 h-32 w-full rounded-lg object-cover">
            @endif
            <p class="text-sm font-medium text-slate-900">{{ $portfolio->title }}</p>
            @if ($portfolio->description)
                <p class="mt-1 text-sm text-slate-600">{{ $portfolio->description }}</p>
            @endif
            <div class="mt-3 flex items-center justify-between">
                @if ($portfolio->url)
                    <a href="{{ $portfolio->url }}" target="_blank" class="text-xs text-[#2457C5] hover:underline">Lihat proyek</a>
                @else
                    <span></span>
                @endif
                <form method="POST" action="{{ route('student.portfolios.destroy', $portfolio) }}" onsubmit="return confirm('Hapus portfolio ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs text-red-600 hover:underline">Hapus</button>
                </form>
            </div>
        </x-card>
    @empty
        <p class="text-sm text-slate-500">Belum ada portfolio ditambahkan.</p>
    @endforelse
</div>
