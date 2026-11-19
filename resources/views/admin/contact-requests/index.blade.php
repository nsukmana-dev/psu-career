<x-admin-layout title="Request Kontak">
    <div class="mb-6 grid grid-cols-2 gap-3.5 lg:grid-cols-4">
        <x-admin.stat-card label="Request Bulan Ini" :value="$stats['total_month']" />
        <x-admin.stat-card label="Menunggu Keputusan" :value="$stats['pending']" trend="perlu diproses" trend-color="warn" />
        <x-admin.stat-card label="Selesai Bulan Ini" :value="$stats['completed_month']" trend="request selesai diputuskan" />
        <x-admin.stat-card label="Kontak Dibagikan" :value="$stats['shared_month']" trend="bulan ini" />
    </div>

    <div x-data="{ detailOpen: false, active: null, rejectingId: null, reason: '' }">
        <div class="mb-5 flex flex-wrap items-center gap-2.5">
            <form method="GET" class="relative min-w-[220px] flex-1">
                <input type="hidden" name="status" value="{{ request('status', 'all') }}">
                <input type="hidden" name="type" value="{{ request('type', 'all') }}">
                <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-soft" fill="none" viewBox="0 0 16 16" stroke="currentColor" stroke-width="1.6"><circle cx="7" cy="7" r="5"/><path d="M11 11l3.5 3.5" stroke-linecap="round"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari perusahaan, posisi, atau nomor request…" class="w-full rounded-full border border-line bg-white py-2.5 pl-10 pr-4 text-sm focus:border-coral-500 focus:outline-none focus:ring-2 focus:ring-coral-100">
            </form>

            <div class="inline-flex rounded-full border border-line bg-surface p-1">
                @foreach (['all' => 'Semua', 'pending' => 'Menunggu', 'completed' => 'Selesai'] as $val => $label)
                    <a href="{{ request()->fullUrlWithQuery(['status' => $val, 'page' => null]) }}" class="rounded-full px-3.5 py-1.5 text-xs font-bold transition {{ request('status', 'all') === $val ? 'bg-white text-ink shadow-sm' : 'text-ink-soft hover:text-ink' }}">{{ $label }}</a>
                @endforeach
            </div>

            <div class="inline-flex rounded-full border border-line bg-surface p-1">
                @foreach (['all' => 'Semua Tipe', 'internship' => 'Magang', 'fulltime' => 'Full-time'] as $val => $label)
                    <a href="{{ request()->fullUrlWithQuery(['type' => $val, 'page' => null]) }}" class="rounded-full px-3.5 py-1.5 text-xs font-bold transition {{ request('type', 'all') === $val ? 'bg-white text-ink shadow-sm' : 'text-ink-soft hover:text-ink' }}">{{ $label }}</a>
                @endforeach
            </div>
        </div>

        <x-admin.card class="overflow-hidden !p-0">
            @if ($requests->isEmpty())
                <p class="px-6 py-12 text-center text-sm text-ink-soft">Tidak ada request kontak yang cocok dengan filter ini.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[760px] border-collapse text-sm">
                        <thead>
                            <tr class="border-b border-line bg-surface">
                                <x-admin.sortable-th field="id" label="No. Request" />
                                <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-ink-soft">Perusahaan</th>
                                <x-admin.sortable-th field="position" label="Posisi" />
                                <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-ink-soft">Kandidat</th>
                                <x-admin.sortable-th field="date" label="Diajukan" />
                                <x-admin.sortable-th field="status" label="Status" />
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requests as $r)
                                @php
                                    $detail = [
                                        'number' => $r->request_number,
                                        'company' => $r->company->name,
                                        'position' => $r->position_title,
                                        'type' => $r->employment_type->label(),
                                        'location' => $r->work_location,
                                        'message' => $r->message,
                                        'status' => $r->status->value,
                                        'status_label' => $r->status->label(),
                                        'date' => $r->created_at->translatedFormat('d M Y, H:i'),
                                        'approve_all_url' => route('admin.contact-requests.approve-all', $r),
                                        'items' => $r->items->map(fn ($i) => [
                                            'id' => $i->id,
                                            'name' => $i->student->user->name,
                                            'nim' => $i->student->nim,
                                            'program' => $i->student->studyProgram->name,
                                            'status' => $i->status->value,
                                            'status_label' => $i->status->label(),
                                            'reason' => $i->rejection_reason,
                                            'approve_url' => route('admin.contact-request-items.approve', $i),
                                            'reject_url' => route('admin.contact-request-items.reject', $i),
                                        ])->values(),
                                    ];
                                @endphp
                                <tr
                                    class="cursor-pointer border-b border-l-[3px] border-line last:border-b-0 transition hover:bg-surface {{ $r->status->value === 'pending' ? 'border-l-warn' : 'border-l-good' }}"
                                    @click="active = {{ \Illuminate\Support\Js::from($detail) }}; detailOpen = true"
                                >
                                    <td class="px-4 py-3.5 font-mono text-xs text-ink-soft">{{ $r->request_number }}</td>
                                    <td class="px-4 py-3.5 font-semibold">{{ $r->company->name }}</td>
                                    <td class="px-4 py-3.5">{{ $r->position_title }}<span class="block text-xs font-normal text-ink-soft">{{ $r->employment_type->label() }}</span></td>
                                    <td class="px-4 py-3.5 text-ink-soft">
                                        {{ $r->items->count() }} kandidat
                                        @if ($r->pending_count)
                                            <span class="font-semibold text-warn">· {{ $r->pending_count }} menunggu</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 text-ink-soft">{{ $r->created_at->translatedFormat('d M Y') }}</td>
                                    <td class="px-4 py-3.5">
                                        <x-admin.pill :color="$r->status->value === 'pending' ? 'warn' : 'good'" :pulse="$r->status->value === 'pending'">
                                            {{ $r->status->label() }}
                                        </x-admin.pill>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="flex flex-wrap items-center justify-between gap-3 border-t border-line px-5 py-3.5 text-xs text-ink-soft">
                    <span>Menampilkan {{ $requests->firstItem() }}–{{ $requests->lastItem() }} dari {{ $requests->total() }} request</span>
                    {{ $requests->onEachSide(1)->links() }}
                </div>
            @endif
        </x-admin.card>

        <x-admin.modal show="detailOpen" title="Detail Request" max-width="max-w-2xl">
            <template x-if="active">
                <div class="space-y-5">
                    <div class="flex items-center justify-between">
                        <span class="font-mono text-xs text-ink-soft" x-text="active.number"></span>
                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-bold" :class="active.status === 'pending' ? 'bg-warn-soft text-warn' : 'bg-good-soft text-good'">
                            <span class="h-1.5 w-1.5 rounded-full" :class="active.status === 'pending' ? 'bg-warn' : 'bg-good'"></span>
                            <span x-text="active.status_label"></span>
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div><p class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Perusahaan</p><p class="font-semibold" x-text="active.company"></p></div>
                        <div><p class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Posisi</p><p class="font-semibold" x-text="active.position"></p></div>
                        <div><p class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Tipe</p><p class="font-semibold" x-text="active.type"></p></div>
                        <div><p class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Lokasi</p><p class="font-semibold" x-text="active.location || '-'"></p></div>
                        <div class="col-span-2"><p class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Pesan</p><p class="text-ink-soft" x-text="active.message || 'Tidak ada pesan tambahan.'"></p></div>
                    </div>

                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <p class="text-sm font-bold">Kandidat</p>
                            <button type="button" x-show="active.status === 'pending'" @click="postTo(active.approve_all_url)" class="text-xs font-bold text-coral-700 hover:underline">Setujui Semua</button>
                        </div>
                        <ul class="divide-y divide-line rounded-2xl border border-line">
                            <template x-for="item in active.items" :key="item.id">
                                <li class="p-3.5">
                                    <div class="flex items-center justify-between gap-3">
                                        <div>
                                            <p class="text-sm font-semibold" x-text="item.name + ' (' + item.nim + ')'"></p>
                                            <p class="text-xs text-ink-soft" x-text="item.program"></p>
                                            <p class="mt-1 text-xs text-bad" x-show="item.reason" x-text="'Alasan: ' + item.reason"></p>
                                        </div>
                                        <div class="flex flex-shrink-0 items-center gap-2">
                                            <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-bold" :class="{ 'bg-warn-soft text-warn': item.status === 'pending', 'bg-good-soft text-good': item.status === 'approved', 'bg-bad-soft text-bad': item.status === 'rejected' }">
                                                <span x-text="item.status_label"></span>
                                            </span>
                                            <template x-if="item.status === 'pending'">
                                                <div class="flex items-center gap-1.5">
                                                    <button type="button" @click="postTo(item.approve_url)" class="rounded-full bg-good px-2.5 py-1 text-xs font-bold text-white hover:shadow">Setujui</button>
                                                    <button type="button" @click="rejectingId === item.id ? (rejectingId = null) : (rejectingId = item.id, reason = '')" class="rounded-full border border-bad px-2.5 py-1 text-xs font-bold text-bad hover:bg-bad-soft">Tolak</button>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                    <div x-show="rejectingId === item.id" x-cloak class="mt-2.5 flex items-center gap-2">
                                        <input type="text" x-model="reason" placeholder="Alasan penolakan" class="flex-1 rounded-xl border border-line px-3 py-2 text-xs focus:border-bad focus:outline-none focus:ring-2 focus:ring-bad-soft">
                                        <button type="button" @click="reason.trim() && postTo(item.reject_url, { rejection_reason: reason })" class="rounded-full bg-bad px-3 py-2 text-xs font-bold text-white">Kirim</button>
                                    </div>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>
            </template>
        </x-admin.modal>
    </div>
</x-admin-layout>
