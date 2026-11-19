<x-admin-layout title="Program Studi">
    <div x-data="{
        open: false,
        mode: 'create',
        active: { id: null, code: '', name: '', level: 'S1', action_url: '{{ route('admin.study-programs.store') }}' },
        openCreate() { this.mode = 'create'; this.active = { id: null, code: '', name: '', level: 'S1', action_url: '{{ route('admin.study-programs.store') }}' }; this.open = true; },
        openEdit(data) { this.mode = 'edit'; this.active = data; this.open = true; }
    }">
        <div class="mb-5 flex items-center justify-between">
            <p class="text-sm text-ink-soft">Kelola daftar program studi yang tersedia untuk pendaftaran mahasiswa.</p>
            <x-admin.btn type="button" variant="primary" @click="openCreate()">+ Tambah Program Studi</x-admin.btn>
        </div>

        <x-admin.card class="overflow-hidden !p-0">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[560px] border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-line bg-surface">
                            <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-ink-soft">Kode</th>
                            <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-ink-soft">Nama</th>
                            <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-ink-soft">Jenjang</th>
                            <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-ink-soft">Mahasiswa</th>
                            <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-ink-soft">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($studyPrograms as $program)
                            @php
                                $data = [
                                    'id' => $program->id, 'code' => $program->code, 'name' => $program->name, 'level' => $program->level->value,
                                    'is_active' => $program->is_active,
                                    'action_url' => route('admin.study-programs.update', $program),
                                    'toggle_url' => route('admin.study-programs.toggle-active', $program),
                                ];
                            @endphp
                            <tr class="cursor-pointer border-b border-line last:border-b-0 transition hover:bg-surface" @click="openEdit({{ \Illuminate\Support\Js::from($data) }})">
                                <td class="px-4 py-3.5 font-mono text-xs text-ink-soft">{{ $program->code }}</td>
                                <td class="px-4 py-3.5 font-semibold">{{ $program->name }}</td>
                                <td class="px-4 py-3.5 text-ink-soft">{{ $program->level->value }}</td>
                                <td class="px-4 py-3.5 text-ink-soft">{{ $program->students_count }}</td>
                                <td class="px-4 py-3.5"><x-admin.pill :color="$program->is_active ? 'good' : 'slate'">{{ $program->is_active ? 'Aktif' : 'Nonaktif' }}</x-admin.pill></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-admin.card>

        <x-admin.modal show="open" title="Program Studi" max-width="max-w-md">
            <h2 class="-mt-1 mb-4 text-base font-bold" x-text="mode === 'create' ? 'Tambah Program Studi' : 'Ubah Program Studi'"></h2>
            <form method="POST" :action="active.action_url" id="programForm" class="space-y-4">
                @csrf
                <template x-if="mode === 'edit'"><input type="hidden" name="_method" value="PUT"></template>
                <div>
                    <x-admin.label value="Kode" />
                    <x-admin.input type="text" name="code" x-model="active.code" required />
                </div>
                <div>
                    <x-admin.label value="Nama Program Studi" />
                    <x-admin.input type="text" name="name" x-model="active.name" required />
                </div>
                <div>
                    <x-admin.label value="Jenjang" />
                    <select name="level" x-model="active.level" class="w-full rounded-xl border border-line bg-cream px-3 py-2.5 text-sm focus:border-coral-500 focus:outline-none focus:ring-2 focus:ring-coral-100">
                        @foreach (\App\Enums\StudyProgramLevel::cases() as $level)
                            <option value="{{ $level->value }}">{{ $level->value }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
            <x-slot:footer>
                <template x-if="mode === 'edit'">
                    <x-admin.btn type="button" variant="ghost" class="mr-auto" x-bind:class="active.is_active ? '!text-bad' : '!text-good'" @click="postTo(active.toggle_url)" x-text="active.is_active ? 'Nonaktifkan' : 'Aktifkan'"></x-admin.btn>
                </template>
                <x-admin.btn type="submit" form="programForm" variant="primary">Simpan</x-admin.btn>
            </x-slot:footer>
        </x-admin.modal>
    </div>
</x-admin-layout>
