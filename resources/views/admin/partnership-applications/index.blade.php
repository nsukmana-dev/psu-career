<x-admin-layout title="Pengajuan Kemitraan">
    <div class="mb-6 grid grid-cols-2 gap-3.5 lg:grid-cols-3">
        <x-admin.stat-card label="Menunggu Keputusan" :value="$applications->total()" trend="perlu ditinjau" trend-color="warn" />
        <x-admin.stat-card label="Diputuskan Bulan Ini" :value="$decidedThisMonth" />
    </div>

    <div x-data="{ open: false, active: null, mode: 'review', reason: '' }">
        <x-admin.card class="overflow-hidden !p-0">
            @if ($applications->isEmpty())
                <p class="px-6 py-12 text-center text-sm text-ink-soft">Tidak ada pengajuan kemitraan yang menunggu.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[640px] border-collapse text-sm">
                        <thead>
                            <tr class="border-b border-line bg-surface">
                                <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-ink-soft">Perusahaan</th>
                                <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-ink-soft">PIC</th>
                                <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-ink-soft">Industri</th>
                                <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-ink-soft">Diajukan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($applications as $application)
                                @php
                                    $needs = collect([
                                        $application->needs_internship ? 'Magang' : null,
                                        $application->needs_fulltime ? 'Full-time' : null,
                                    ])->filter()->implode(' · ');
                                    $detail = [
                                        'company_name' => $application->company_name,
                                        'industry' => $application->industry?->name ?? '-',
                                        'pic_name' => $application->pic_name,
                                        'pic_position' => $application->pic_position,
                                        'email' => $application->email,
                                        'phone' => $application->phone,
                                        'needs' => $needs,
                                        'message' => $application->message,
                                        'approve_url' => route('admin.partnership-applications.approve', $application),
                                        'reject_url' => route('admin.partnership-applications.reject', $application),
                                    ];
                                @endphp
                                <tr class="cursor-pointer border-b border-l-[3px] border-line border-l-warn last:border-b-0 transition hover:bg-surface" @click="active = {{ \Illuminate\Support\Js::from($detail) }}; open = true; mode = 'review'; reason = ''">
                                    <td class="px-4 py-3.5 font-semibold">{{ $application->company_name }}</td>
                                    <td class="px-4 py-3.5 text-ink-soft">{{ $application->pic_name }}</td>
                                    <td class="px-4 py-3.5 text-ink-soft">{{ $application->industry?->name ?? '-' }}</td>
                                    <td class="px-4 py-3.5 text-ink-soft">{{ $application->created_at->translatedFormat('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="flex flex-wrap items-center justify-between gap-3 border-t border-line px-5 py-3.5 text-xs text-ink-soft">
                    <span>Menampilkan {{ $applications->firstItem() }}–{{ $applications->lastItem() }} dari {{ $applications->total() }}</span>
                    {{ $applications->onEachSide(1)->links() }}
                </div>
            @endif
        </x-admin.card>

        <x-admin.modal show="open" title="Tinjau Pengajuan Kemitraan" max-width="max-w-lg">
            <template x-if="active">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div><p class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Nama Perusahaan</p><p class="font-semibold" x-text="active.company_name"></p></div>
                        <div><p class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Industri</p><p class="font-semibold" x-text="active.industry"></p></div>
                        <div><p class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Nama PIC</p><p class="font-semibold" x-text="active.pic_name"></p></div>
                        <div><p class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Jabatan PIC</p><p class="font-semibold" x-text="active.pic_position"></p></div>
                        <div><p class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Email</p><p class="font-semibold" x-text="active.email"></p></div>
                        <div><p class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Telepon</p><p class="font-semibold" x-text="active.phone"></p></div>
                        <div class="col-span-2"><p class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Kebutuhan</p><p class="font-semibold" x-text="active.needs"></p></div>
                    </div>
                    <div x-show="active.message">
                        <p class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Pesan</p>
                        <p class="text-sm text-ink-soft" x-text="active.message"></p>
                    </div>

                    <div x-show="mode === 'reject'" x-cloak>
                        <x-admin.label value="Alasan Penolakan" />
                        <textarea x-model="reason" rows="2" class="w-full rounded-xl border border-line bg-cream px-3 py-2.5 text-sm focus:border-bad focus:outline-none focus:ring-2 focus:ring-bad-soft"></textarea>
                    </div>
                </div>
            </template>

            <x-slot:footer>
                <template x-if="mode === 'review'">
                    <div class="flex gap-2">
                        <x-admin.btn type="button" variant="ghost" class="!text-bad" @click="mode = 'reject'">Tolak</x-admin.btn>
                        <x-admin.btn type="button" variant="primary" @click="postTo(active.approve_url)">Setujui &amp; Buat Akun</x-admin.btn>
                    </div>
                </template>
                <template x-if="mode === 'reject'">
                    <x-admin.btn type="button" variant="danger" @click="reason.trim() && postTo(active.reject_url, { rejection_reason: reason })">Kirim Penolakan</x-admin.btn>
                </template>
            </x-slot:footer>
        </x-admin.modal>
    </div>
</x-admin-layout>
