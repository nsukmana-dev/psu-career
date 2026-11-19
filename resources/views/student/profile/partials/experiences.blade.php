<x-card title="Tambah Pengalaman">
    <form method="POST" action="{{ route('student.experiences.store') }}" class="space-y-4">
        @csrf
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <x-input-label for="exp_type" value="Tipe" />
                <select id="exp_type" name="type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2457C5] focus:ring-[#2457C5]">
                    @foreach (\App\Enums\ExperienceType::cases() as $type)
                        <option value="{{ $type->value }}">{{ $type->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label for="organization" value="Nama Tempat/Organisasi" />
                <x-text-input id="organization" name="organization" type="text" class="mt-1 block w-full" required />
            </div>
            <div>
                <x-input-label for="position" value="Posisi" />
                <x-text-input id="position" name="position" type="text" class="mt-1 block w-full" required />
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <x-input-label for="exp_start_date" value="Mulai" />
                    <x-text-input id="exp_start_date" name="start_date" type="date" class="mt-1 block w-full" required />
                </div>
                <div>
                    <x-input-label for="exp_end_date" value="Selesai" />
                    <x-text-input id="exp_end_date" name="end_date" type="date" class="mt-1 block w-full" />
                </div>
            </div>
        </div>

        <label class="inline-flex items-center gap-2 text-sm text-slate-600">
            <input type="checkbox" name="is_current" value="1" class="rounded border-gray-300 text-[#2457C5]">
            Masih berjalan sampai sekarang
        </label>

        <div>
            <x-input-label for="exp_description" value="Deskripsi" />
            <textarea id="exp_description" name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2457C5] focus:ring-[#2457C5]"></textarea>
        </div>

        <x-primary-button>Tambah Pengalaman</x-primary-button>
    </form>
</x-card>

<x-card title="Riwayat Pengalaman" class="mt-6">
    @if ($student->experiences->isEmpty())
        <p class="text-sm text-slate-500">Belum ada pengalaman ditambahkan.</p>
    @else
        <ul class="divide-y divide-slate-100">
            @foreach ($student->experiences->sortByDesc('start_date') as $experience)
                <li class="flex items-start justify-between gap-4 py-3">
                    <div>
                        <p class="text-sm font-medium text-slate-900">{{ $experience->position }} · {{ $experience->organization }}</p>
                        <p class="text-xs text-slate-500">
                            {{ $experience->type->label() }} ·
                            {{ $experience->start_date->translatedFormat('M Y') }} –
                            {{ $experience->is_current ? 'Sekarang' : $experience->end_date?->translatedFormat('M Y') }}
                        </p>
                        @if ($experience->description)
                            <p class="mt-1 text-sm text-slate-600">{{ $experience->description }}</p>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('student.experiences.destroy', $experience) }}" onsubmit="return confirm('Hapus pengalaman ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-red-600 hover:underline">Hapus</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif
</x-card>
