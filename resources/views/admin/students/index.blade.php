<x-admin-layout title="Mahasiswa">
    <div class="mb-6 grid grid-cols-2 gap-3.5 lg:grid-cols-3">
        <x-admin.stat-card label="Mahasiswa Terverifikasi" :value="$verifiedCount" />
        <x-admin.stat-card label="Akun Nonaktif" :value="$inactiveCount" :trend-color="$inactiveCount > 0 ? 'warn' : 'good'" />
    </div>

    <div x-data="{
        open: false,
        mode: 'create',
        active: { name: '', email: '', nim: '', study_program_id: '', entry_year: {{ now()->year }} },
        openCreate() { this.mode = 'create'; this.active = { name: '', email: '', nim: '', study_program_id: '', entry_year: {{ now()->year }} }; this.open = true; },
        openDetail(data) { this.mode = 'detail'; this.active = data; this.open = true; }
    }">
        <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
            <form method="GET" class="flex flex-wrap items-center gap-2.5">
                <div class="relative min-w-[200px]">
                    <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-soft" fill="none" viewBox="0 0 16 16" stroke="currentColor" stroke-width="1.6"><circle cx="7" cy="7" r="5"/><path d="M11 11l3.5 3.5" stroke-linecap="round"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIM…" class="w-full rounded-full border border-line bg-white py-2.5 pl-10 pr-4 text-sm focus:border-coral-500 focus:outline-none focus:ring-2 focus:ring-coral-100">
                </div>
                <select name="status" onchange="this.form.submit()" class="rounded-full border border-line bg-white px-3.5 py-2.5 text-sm focus:border-coral-500 focus:outline-none">
                    <option value="">Semua Status</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->label() }}</option>
                    @endforeach
                </select>
                <select name="study_program_id" onchange="this.form.submit()" class="rounded-full border border-line bg-white px-3.5 py-2.5 text-sm focus:border-coral-500 focus:outline-none">
                    <option value="">Semua Prodi</option>
                    @foreach ($studyPrograms as $program)
                        <option value="{{ $program->id }}" @selected((string) request('study_program_id') === (string) $program->id)>{{ $program->name }}</option>
                    @endforeach
                </select>
                @if (request()->hasAny(['status', 'study_program_id', 'search']))
                    <a href="{{ route('admin.students.index') }}" class="text-xs font-semibold text-ink-soft hover:text-ink">Reset</a>
                @endif
            </form>
            <x-admin.btn type="button" variant="primary" @click="openCreate()">+ Tambah Mahasiswa</x-admin.btn>
        </div>

        <x-admin.card class="overflow-hidden !p-0">
            @if ($students->isEmpty())
                <p class="px-6 py-12 text-center text-sm text-ink-soft">Tidak ada mahasiswa yang cocok.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[680px] border-collapse text-sm">
                        <thead>
                            <tr class="border-b border-line bg-surface">
                                <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-ink-soft">NIM</th>
                                <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-ink-soft">Nama</th>
                                <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-ink-soft">Prodi</th>
                                <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-ink-soft">Angkatan</th>
                                <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-ink-soft">Status</th>
                                <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-ink-soft">Akun</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $student)
                                @php
                                    $data = [
                                        'name' => $student->user->name,
                                        'email' => $student->user->email,
                                        'nim' => $student->nim,
                                        'program' => $student->studyProgram->name,
                                        'entry_year' => $student->entry_year,
                                        'status_label' => $student->verification_status->label(),
                                        'is_active' => $student->user->is_active,
                                        'toggle_url' => route('admin.students.toggle-active', $student),
                                        'reset_url' => route('admin.students.reset-password', $student),
                                    ];
                                @endphp
                                <tr class="cursor-pointer border-b border-line last:border-b-0 transition hover:bg-surface" @click="openDetail({{ \Illuminate\Support\Js::from($data) }})">
                                    <td class="px-4 py-3.5 font-mono text-xs text-ink-soft">{{ $student->nim }}</td>
                                    <td class="px-4 py-3.5 font-semibold">{{ $student->user->name }}</td>
                                    <td class="px-4 py-3.5 text-ink-soft">{{ $student->studyProgram->name }}</td>
                                    <td class="px-4 py-3.5 text-ink-soft">{{ $student->entry_year ?? '-' }}</td>
                                    <td class="px-4 py-3.5"><x-admin.pill :color="$student->verification_status->value === 'verified' ? 'good' : ($student->verification_status->value === 'rejected' ? 'bad' : 'warn')">{{ $student->verification_status->label() }}</x-admin.pill></td>
                                    <td class="px-4 py-3.5"><x-admin.pill :color="$student->user->is_active ? 'good' : 'bad'">{{ $student->user->is_active ? 'Aktif' : 'Nonaktif' }}</x-admin.pill></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="flex flex-wrap items-center justify-between gap-3 border-t border-line px-5 py-3.5 text-xs text-ink-soft">
                    <span>Menampilkan {{ $students->firstItem() }}–{{ $students->lastItem() }} dari {{ $students->total() }}</span>
                    {{ $students->onEachSide(1)->links() }}
                </div>
            @endif
        </x-admin.card>

        <x-admin.modal show="open" title="Mahasiswa" max-width="max-w-lg">
            <template x-if="mode === 'create'">
                <form method="POST" action="{{ route('admin.students.store') }}" id="studentForm" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <x-admin.label value="Nama Lengkap" />
                            <x-admin.input type="text" name="name" x-model="active.name" required />
                        </div>
                        <div>
                            <x-admin.label value="Email" />
                            <x-admin.input type="email" name="email" x-model="active.email" required />
                        </div>
                        <div>
                            <x-admin.label value="NIM" />
                            <x-admin.input type="text" name="nim" x-model="active.nim" required />
                        </div>
                        <div>
                            <x-admin.label value="Program Studi" />
                            <select name="study_program_id" x-model="active.study_program_id" required class="w-full rounded-xl border border-line bg-cream px-3 py-2.5 text-sm focus:border-coral-500 focus:outline-none focus:ring-2 focus:ring-coral-100">
                                <option value="">Pilih program studi</option>
                                @foreach ($studyPrograms as $program)
                                    <option value="{{ $program->id }}">{{ $program->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-admin.label value="Angkatan" />
                            <x-admin.input type="number" name="entry_year" x-model="active.entry_year" />
                        </div>
                    </div>
                    <p class="text-xs text-ink-soft">Email atur password dikirim otomatis setelah akun dibuat.</p>
                </form>
            </template>

            <template x-if="mode === 'detail'">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div><p class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Nama</p><p class="font-semibold" x-text="active.name"></p></div>
                        <div><p class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">NIM</p><p class="font-semibold" x-text="active.nim"></p></div>
                        <div><p class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Email</p><p class="font-semibold" x-text="active.email"></p></div>
                        <div><p class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Program Studi</p><p class="font-semibold" x-text="active.program"></p></div>
                        <div><p class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Angkatan</p><p class="font-semibold" x-text="active.entry_year || '-'"></p></div>
                        <div><p class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Status Verifikasi</p><p class="font-semibold" x-text="active.status_label"></p></div>
                    </div>
                </div>
            </template>

            <x-slot:footer>
                <template x-if="mode === 'create'">
                    <x-admin.btn type="submit" form="studentForm" variant="primary">Buat Akun &amp; Kirim Undangan</x-admin.btn>
                </template>
                <template x-if="mode === 'detail'">
                    <div class="flex gap-2">
                        <x-admin.btn type="button" variant="ghost" @click="postTo(active.reset_url)">Reset Password</x-admin.btn>
                        <x-admin.btn type="button" variant="ghost" x-bind:class="active.is_active ? '!text-bad' : '!text-good'" @click="confirm(active.is_active ? 'Nonaktifkan akun ini?' : 'Aktifkan akun ini?') && postTo(active.toggle_url)" x-text="active.is_active ? 'Nonaktifkan' : 'Aktifkan'"></x-admin.btn>
                    </div>
                </template>
            </x-slot:footer>
        </x-admin.modal>
    </div>
</x-admin-layout>
