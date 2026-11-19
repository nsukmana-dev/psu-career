<x-admin-layout title="Tinjau Profil Mahasiswa">
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-5 lg:col-span-2">
            <x-admin.card title="Data Diri">
                <dl class="grid grid-cols-1 gap-3 text-sm sm:grid-cols-2">
                    <div><dt class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Nama</dt><dd class="font-semibold">{{ $student->user->name }}</dd></div>
                    <div><dt class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">NIM</dt><dd class="font-semibold">{{ $student->nim }}</dd></div>
                    <div><dt class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Program Studi</dt><dd class="font-semibold">{{ $student->studyProgram->name }}</dd></div>
                    <div><dt class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Angkatan</dt><dd class="font-semibold">{{ $student->entry_year }}</dd></div>
                    <div><dt class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Semester</dt><dd class="font-semibold">{{ $student->current_semester }}</dd></div>
                    <div><dt class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">IPK</dt><dd class="font-semibold">{{ $student->gpa }}</dd></div>
                    <div><dt class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Jenis Kelamin</dt><dd class="font-semibold">{{ $student->gender?->label() }}</dd></div>
                    <div><dt class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Domisili</dt><dd class="font-semibold">{{ $student->city ?? '-' }}</dd></div>
                </dl>
                @if ($student->summary)
                    <p class="mt-4 text-sm text-ink-soft">{{ $student->summary }}</p>
                @endif
            </x-admin.card>

            <x-admin.card title="Skill">
                @forelse ($student->skills as $skill)
                    <x-admin.pill class="mb-2 mr-2">{{ $skill->name }} · {{ \App\Enums\SkillLevel::from($skill->pivot->level)->label() }}</x-admin.pill>
                @empty
                    <p class="text-sm text-ink-soft">Belum ada skill.</p>
                @endforelse
            </x-admin.card>

            <x-admin.card title="Pengalaman">
                @forelse ($student->experiences as $experience)
                    <div class="mb-3 text-sm">
                        <p class="font-semibold">{{ $experience->position }} · {{ $experience->organization }}</p>
                        <p class="text-ink-soft">{{ $experience->type->label() }}</p>
                    </div>
                @empty
                    <p class="text-sm text-ink-soft">Belum ada pengalaman.</p>
                @endforelse
            </x-admin.card>

            <x-admin.card title="Pendidikan">
                @forelse ($student->educations as $education)
                    <div class="mb-3 text-sm">
                        <p class="font-semibold">{{ $education->institution }}</p>
                        <p class="text-ink-soft">{{ $education->major }} · {{ $education->level }}</p>
                    </div>
                @empty
                    <p class="text-sm text-ink-soft">Belum ada riwayat pendidikan.</p>
                @endforelse
            </x-admin.card>

            <x-admin.card title="Sertifikat">
                @forelse ($student->certificates as $certificate)
                    <div class="mb-2 text-sm">
                        <span class="font-semibold">{{ $certificate->name }}</span>
                        <span class="text-ink-soft">· {{ $certificate->issuer }}</span>
                        @if ($certificate->file_path)
                            <a href="{{ route('certificates.download', $certificate) }}" class="ml-2 font-semibold text-coral-700 hover:underline">Unduh</a>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-ink-soft">Belum ada sertifikat.</p>
                @endforelse
            </x-admin.card>
        </div>

        <div>
            <x-admin.card title="Keputusan Verifikasi">
                <x-admin.pill :color="$student->verification_status->value === 'verified' ? 'good' : 'warn'" class="mb-4">{{ $student->verification_status->label() }}</x-admin.pill>

                <form method="POST" action="{{ route('admin.verifications.approve', $student) }}" class="mb-3">
                    @csrf
                    <x-admin.btn type="submit" variant="primary" class="w-full">Setujui Profil</x-admin.btn>
                </form>

                <form method="POST" action="{{ route('admin.verifications.reject', $student) }}" x-data="{ open: false }">
                    @csrf
                    <template x-if="!open">
                        <x-admin.btn type="button" variant="ghost" class="w-full !text-bad" @click="open = true">Tolak Profil</x-admin.btn>
                    </template>
                    <div x-show="open" x-cloak>
                        <x-admin.label for="reason" value="Alasan Penolakan" />
                        <textarea id="reason" name="reason" rows="3" required class="w-full rounded-xl border border-line bg-cream px-3 py-2.5 text-sm focus:border-bad focus:outline-none focus:ring-2 focus:ring-bad-soft"></textarea>
                        <x-input-error :messages="$errors->get('reason')" class="mt-1" />
                        <x-admin.btn type="submit" variant="danger" class="mt-2 w-full">Kirim Penolakan</x-admin.btn>
                    </div>
                </form>
            </x-admin.card>
        </div>
    </div>
</x-admin-layout>
