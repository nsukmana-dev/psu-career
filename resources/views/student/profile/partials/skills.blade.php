<x-card title="Tambah Skill">
    <form method="POST" action="{{ route('student.skills.store') }}" class="flex flex-wrap items-end gap-4">
        @csrf
        <div class="flex-1 min-w-[200px]">
            <x-input-label for="skill_name" value="Nama Skill" />
            <input id="skill_name" name="skill_name" list="skill-options" type="text" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2457C5] focus:ring-[#2457C5]">
            <datalist id="skill-options">
                @foreach (\App\Models\Skill::where('is_approved', true)->orderBy('name')->get() as $skill)
                    <option value="{{ $skill->name }}">
                @endforeach
            </datalist>
            <x-input-error :messages="$errors->get('skill_name')" class="mt-1" />
        </div>

        <div>
            <x-input-label for="level" value="Level" />
            <select id="level" name="level" required class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-[#2457C5] focus:ring-[#2457C5]">
                @foreach (\App\Enums\SkillLevel::cases() as $level)
                    <option value="{{ $level->value }}">{{ $level->label() }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('level')" class="mt-1" />
        </div>

        <x-primary-button>Tambah</x-primary-button>
    </form>
</x-card>

<x-card title="Skill Saya" class="mt-6">
    @if ($student->skills->isEmpty())
        <p class="text-sm text-slate-500">Belum ada skill ditambahkan.</p>
    @else
        <ul class="divide-y divide-slate-100">
            @foreach ($student->skills as $skill)
                <li class="flex items-center justify-between py-2">
                    <div>
                        <span class="text-sm font-medium text-slate-900">{{ $skill->name }}</span>
                        <span class="ml-2 text-xs text-slate-500">{{ \App\Enums\SkillLevel::from($skill->pivot->level)->label() }}</span>
                        @if (! $skill->is_approved)
                            <x-badge color="amber" class="ml-2">Menunggu persetujuan admin</x-badge>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('student.skills.destroy', $skill) }}" onsubmit="return confirm('Hapus skill ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-red-600 hover:underline">Hapus</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif
</x-card>
