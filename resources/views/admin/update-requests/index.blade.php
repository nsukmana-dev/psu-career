<x-admin-layout title="Update Request">
    <div class="mb-6 grid grid-cols-2 gap-3.5 lg:grid-cols-3">
        <x-admin.stat-card label="Menunggu Keputusan" :value="$requests->total()" trend="perlu ditinjau" trend-color="warn" />
        <x-admin.stat-card label="Diputuskan Bulan Ini" :value="$decidedThisMonth" />
    </div>

    <div x-data="{ open: false, active: null, mode: 'review', note: '' }">
        <x-admin.card class="overflow-hidden !p-0">
            @if ($requests->isEmpty())
                <p class="px-6 py-12 text-center text-sm text-ink-soft">Tidak ada pengajuan perubahan data yang menunggu.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[620px] border-collapse text-sm">
                        <thead>
                            <tr class="border-b border-line bg-surface">
                                <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-ink-soft">Mahasiswa</th>
                                <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-ink-soft">NIM</th>
                                <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-ink-soft">Field Diubah</th>
                                <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-ink-soft">Diajukan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requests as $req)
                                @php
                                    $resolvedChanges = collect($req->changes)->mapWithKeys(function ($change, $field) use ($programNames) {
                                        $resolve = fn ($v) => $field === 'study_program_id' ? ($programNames[$v] ?? '-') : (string) $v;

                                        return [$field => ['old' => $resolve($change['old']), 'new' => $resolve($change['new'])]];
                                    });
                                    $detail = [
                                        'name' => $req->student->user->name,
                                        'nim' => $req->student->nim,
                                        'changes' => $resolvedChanges,
                                        'approve_url' => route('admin.update-requests.approve', $req),
                                        'reject_url' => route('admin.update-requests.reject', $req),
                                    ];
                                @endphp
                                <tr class="cursor-pointer border-b border-l-[3px] border-line border-l-warn last:border-b-0 transition hover:bg-surface" @click="active = {{ \Illuminate\Support\Js::from($detail) }}; open = true; mode = 'review'; note = ''">
                                    <td class="px-4 py-3.5 font-semibold">{{ $req->student->user->name }}</td>
                                    <td class="px-4 py-3.5 font-mono text-xs text-ink-soft">{{ $req->student->nim }}</td>
                                    <td class="px-4 py-3.5 text-ink-soft">{{ implode(', ', array_map(fn ($f) => $fieldLabels[$f] ?? $f, array_keys($req->changes))) }}</td>
                                    <td class="px-4 py-3.5 text-ink-soft">{{ $req->created_at->translatedFormat('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="flex flex-wrap items-center justify-between gap-3 border-t border-line px-5 py-3.5 text-xs text-ink-soft">
                    <span>Menampilkan {{ $requests->firstItem() }}–{{ $requests->lastItem() }} dari {{ $requests->total() }}</span>
                    {{ $requests->onEachSide(1)->links() }}
                </div>
            @endif
        </x-admin.card>

        <x-admin.modal show="open" title="Tinjau Perubahan Data Akademik" max-width="max-w-lg">
            <template x-if="active">
                <div class="space-y-4">
                    <p class="text-sm text-ink-soft"><span class="font-semibold text-ink" x-text="active.name"></span> · <span x-text="active.nim"></span></p>

                    <div class="overflow-hidden rounded-2xl border border-line">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-line bg-surface text-left text-[11px] font-bold uppercase tracking-wider text-ink-soft">
                                    <th class="px-3 py-2.5">Field</th>
                                    <th class="px-3 py-2.5">Sebelum</th>
                                    <th class="px-3 py-2.5">Sesudah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(change, field) in active.changes" :key="field">
                                    <tr class="border-b border-line last:border-0">
                                        <td class="px-3 py-2.5 font-semibold" x-text="({{ \Illuminate\Support\Js::from($fieldLabels) }})[field] ?? field"></td>
                                        <td class="px-3 py-2.5 text-bad line-through" x-text="change.old"></td>
                                        <td class="px-3 py-2.5 text-good" x-text="change.new"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <div x-show="mode === 'reject'" x-cloak>
                        <x-admin.label value="Alasan Penolakan" />
                        <textarea x-model="note" rows="2" class="w-full rounded-xl border border-line bg-cream px-3 py-2.5 text-sm focus:border-bad focus:outline-none focus:ring-2 focus:ring-bad-soft"></textarea>
                    </div>
                </div>
            </template>

            <x-slot:footer>
                <template x-if="mode === 'review'">
                    <div class="flex gap-2">
                        <x-admin.btn type="button" variant="ghost" class="!text-bad" @click="mode = 'reject'">Tolak</x-admin.btn>
                        <x-admin.btn type="button" variant="primary" @click="postTo(active.approve_url, { note: note })">Setujui Perubahan</x-admin.btn>
                    </div>
                </template>
                <template x-if="mode === 'reject'">
                    <x-admin.btn type="button" variant="danger" @click="note.trim() && postTo(active.reject_url, { note: note })">Kirim Penolakan</x-admin.btn>
                </template>
            </x-slot:footer>
        </x-admin.modal>
    </div>
</x-admin-layout>
