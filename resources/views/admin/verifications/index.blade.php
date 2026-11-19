<x-admin-layout title="Verifikasi Profil Mahasiswa">
    <div class="mb-6 grid grid-cols-2 gap-3.5 lg:grid-cols-3">
        <x-admin.stat-card label="Menunggu Verifikasi" :value="$students->total()" trend="perlu ditinjau" trend-color="warn" />
        <x-admin.stat-card label="Disetujui Bulan Ini" :value="$verifiedThisMonth" />
    </div>

    <div x-data="{ open: false, active: null, rejecting: false, reason: '' }">
        <form method="GET" class="relative mb-5 max-w-sm">
            <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-soft" fill="none" viewBox="0 0 16 16" stroke="currentColor" stroke-width="1.6"><circle cx="7" cy="7" r="5"/><path d="M11 11l3.5 3.5" stroke-linecap="round"/></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIM…" class="w-full rounded-full border border-line bg-white py-2.5 pl-10 pr-4 text-sm focus:border-coral-500 focus:outline-none focus:ring-2 focus:ring-coral-100">
        </form>

        <x-admin.card class="overflow-hidden !p-0">
            @if ($students->isEmpty())
                <p class="px-6 py-12 text-center text-sm text-ink-soft">Tidak ada profil yang menunggu verifikasi.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[620px] border-collapse text-sm">
                        <thead>
                            <tr class="border-b border-line bg-surface">
                                <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-ink-soft">Nama</th>
                                <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-ink-soft">NIM</th>
                                <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-ink-soft">Program Studi</th>
                                <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-ink-soft">Diajukan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $student)
                                @php
                                    $detail = [
                                        'id' => $student->id,
                                        'name' => $student->user->name,
                                        'nim' => $student->nim,
                                        'program' => $student->studyProgram->name,
                                        'entry_year' => $student->entry_year,
                                        'semester' => $student->current_semester,
                                        'gpa' => (string) $student->gpa,
                                        'gender' => $student->gender?->label(),
                                        'city' => $student->city,
                                        'summary' => $student->summary,
                                        'skills' => $student->skills->pluck('name')->values(),
                                        'show_url' => route('admin.verifications.show', $student),
                                        'approve_url' => route('admin.verifications.approve', $student),
                                        'reject_url' => route('admin.verifications.reject', $student),
                                    ];
                                @endphp
                                <tr class="cursor-pointer border-b border-l-[3px] border-line border-l-warn last:border-b-0 transition hover:bg-surface" @click="active = {{ \Illuminate\Support\Js::from($detail) }}; open = true; rejecting = false">
                                    <td class="px-4 py-3.5 font-semibold">{{ $student->user->name }}</td>
                                    <td class="px-4 py-3.5 font-mono text-xs text-ink-soft">{{ $student->nim }}</td>
                                    <td class="px-4 py-3.5 text-ink-soft">{{ $student->studyProgram->name }}</td>
                                    <td class="px-4 py-3.5 text-ink-soft">{{ $student->updated_at->translatedFormat('d M Y') }}</td>
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

        <x-admin.modal show="open" title="Tinjau Profil Mahasiswa" max-width="max-w-xl">
            <template x-if="active">
                <div class="space-y-5">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div><p class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Nama</p><p class="font-semibold" x-text="active.name"></p></div>
                        <div><p class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">NIM</p><p class="font-semibold" x-text="active.nim"></p></div>
                        <div><p class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Program Studi</p><p class="font-semibold" x-text="active.program"></p></div>
                        <div><p class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Angkatan</p><p class="font-semibold" x-text="active.entry_year"></p></div>
                        <div><p class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Semester</p><p class="font-semibold" x-text="active.semester"></p></div>
                        <div><p class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">IPK</p><p class="font-semibold" x-text="active.gpa"></p></div>
                        <div><p class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Jenis Kelamin</p><p class="font-semibold" x-text="active.gender || '-'"></p></div>
                        <div><p class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Domisili</p><p class="font-semibold" x-text="active.city || '-'"></p></div>
                    </div>

                    <div x-show="active.summary">
                        <p class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Ringkasan</p>
                        <p class="text-sm text-ink-soft" x-text="active.summary"></p>
                    </div>

                    <div>
                        <p class="mb-1.5 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Skill</p>
                        <div class="flex flex-wrap gap-1.5">
                            <template x-for="skill in active.skills" :key="skill">
                                <span class="rounded-full bg-surface px-2.5 py-1 text-xs font-semibold text-ink-soft" x-text="skill"></span>
                            </template>
                            <span x-show="active.skills.length === 0" class="text-xs text-ink-soft">Belum ada skill.</span>
                        </div>
                    </div>

                    <a :href="active.show_url" class="inline-block text-xs font-bold text-coral-700 hover:underline">Lihat profil lengkap (pengalaman, pendidikan, sertifikat) &rarr;</a>
                </div>
            </template>

            <x-slot:footer>
                <template x-if="!rejecting">
                    <div class="flex gap-2">
                        <x-admin.btn type="button" variant="ghost" class="!text-bad" @click="rejecting = true">Tolak</x-admin.btn>
                        <x-admin.btn type="button" variant="primary" @click="postTo(active.approve_url)">Setujui Profil</x-admin.btn>
                    </div>
                </template>
                <template x-if="rejecting">
                    <div class="flex w-full items-center gap-2">
                        <input type="text" x-model="reason" placeholder="Alasan penolakan" class="flex-1 rounded-xl border border-line px-3 py-2 text-xs focus:border-bad focus:outline-none focus:ring-2 focus:ring-bad-soft">
                        <x-admin.btn type="button" variant="danger" @click="reason.trim() && postTo(active.reject_url, { reason: reason })">Kirim</x-admin.btn>
                    </div>
                </template>
            </x-slot:footer>
        </x-admin.modal>
    </div>
</x-admin-layout>
