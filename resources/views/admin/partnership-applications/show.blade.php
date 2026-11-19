<x-admin-layout title="Tinjau Pengajuan Kemitraan">
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <x-admin.card title="Detail Pengajuan">
                <dl class="grid grid-cols-1 gap-3 text-sm sm:grid-cols-2">
                    <div><dt class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Nama Perusahaan</dt><dd class="font-semibold">{{ $application->company_name }}</dd></div>
                    <div><dt class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Industri</dt><dd class="font-semibold">{{ $application->industry?->name ?? '-' }}</dd></div>
                    <div><dt class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Nama PIC</dt><dd class="font-semibold">{{ $application->pic_name }}</dd></div>
                    <div><dt class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Jabatan PIC</dt><dd class="font-semibold">{{ $application->pic_position }}</dd></div>
                    <div><dt class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Email</dt><dd class="font-semibold">{{ $application->email }}</dd></div>
                    <div><dt class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Telepon</dt><dd class="font-semibold">{{ $application->phone }}</dd></div>
                    <div><dt class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Kebutuhan</dt><dd class="font-semibold">
                        @if ($application->needs_internship) Magang @endif
                        @if ($application->needs_internship && $application->needs_fulltime) · @endif
                        @if ($application->needs_fulltime) Full-time @endif
                    </dd></div>
                </dl>
                @if ($application->message)
                    <p class="mt-4 text-sm text-ink-soft">{{ $application->message }}</p>
                @endif
            </x-admin.card>
        </div>

        <div>
            <x-admin.card title="Keputusan">
                <x-admin.pill :color="$application->status->value === 'approved' ? 'good' : ($application->status->value === 'rejected' ? 'bad' : 'warn')" class="mb-4">{{ $application->status->label() }}</x-admin.pill>

                <form method="POST" action="{{ route('admin.partnership-applications.approve', $application) }}" class="mb-3">
                    @csrf
                    <x-admin.btn type="submit" variant="primary" class="w-full">Setujui &amp; Buat Akun</x-admin.btn>
                </form>

                <form method="POST" action="{{ route('admin.partnership-applications.reject', $application) }}">
                    @csrf
                    <x-admin.label for="rejection_reason" value="Alasan Penolakan" />
                    <textarea id="rejection_reason" name="rejection_reason" rows="3" required class="mb-2.5 w-full rounded-xl border border-line bg-cream px-3 py-2.5 text-sm focus:border-bad focus:outline-none focus:ring-2 focus:ring-bad-soft"></textarea>
                    <x-input-error :messages="$errors->get('rejection_reason')" class="mb-2.5" />
                    <x-admin.btn type="submit" variant="danger" class="w-full">Tolak Pengajuan</x-admin.btn>
                </form>
            </x-admin.card>
        </div>
    </div>
</x-admin-layout>
