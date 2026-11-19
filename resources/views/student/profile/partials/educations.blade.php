<x-card title="Tambah Riwayat Pendidikan">
    <form method="POST" action="{{ route('student.educations.store') }}" class="space-y-4">
        @csrf
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <x-input-label for="institution" value="Institusi" />
                <x-text-input id="institution" name="institution" type="text" class="mt-1 block w-full" required />
            </div>
            <div>
                <x-input-label for="major" value="Jurusan" />
                <x-text-input id="major" name="major" type="text" class="mt-1 block w-full" required />
            </div>
            <div>
                <x-input-label for="edu_level" value="Jenjang" />
                <x-text-input id="edu_level" name="level" type="text" class="mt-1 block w-full" placeholder="SMA/SMK, D3, dll" required />
            </div>
            <div>
                <x-input-label for="score" value="Nilai (opsional)" />
                <x-text-input id="score" name="score" type="text" class="mt-1 block w-full" />
            </div>
            <div>
                <x-input-label for="start_year" value="Tahun Masuk" />
                <x-text-input id="start_year" name="start_year" type="number" class="mt-1 block w-full" required />
            </div>
            <div>
                <x-input-label for="end_year" value="Tahun Lulus" />
                <x-text-input id="end_year" name="end_year" type="number" class="mt-1 block w-full" />
            </div>
        </div>

        <x-primary-button>Tambah Riwayat Pendidikan</x-primary-button>
    </form>
</x-card>

<x-card title="Riwayat Pendidikan" class="mt-6">
    @if ($student->educations->isEmpty())
        <p class="text-sm text-slate-500">Belum ada riwayat pendidikan ditambahkan.</p>
    @else
        <ul class="divide-y divide-slate-100">
            @foreach ($student->educations->sortByDesc('start_year') as $education)
                <li class="flex items-start justify-between gap-4 py-3">
                    <div>
                        <p class="text-sm font-medium text-slate-900">{{ $education->institution }}</p>
                        <p class="text-xs text-slate-500">
                            {{ $education->major }} · {{ $education->level }} ·
                            {{ $education->start_year }}–{{ $education->end_year ?? 'sekarang' }}
                            @if ($education->score) · Nilai: {{ $education->score }} @endif
                        </p>
                    </div>
                    <form method="POST" action="{{ route('student.educations.destroy', $education) }}" onsubmit="return confirm('Hapus riwayat pendidikan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-red-600 hover:underline">Hapus</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif
</x-card>
