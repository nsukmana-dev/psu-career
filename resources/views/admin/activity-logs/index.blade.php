<x-admin-layout title="Log Aktivitas">
    <x-admin.card title="Filter" class="mb-5">
        <form method="GET" class="flex flex-wrap items-end gap-3">
            <div>
                <x-admin.label value="Aksi" />
                <select name="action" class="rounded-xl border border-line bg-cream px-3 py-2.5 text-sm focus:border-coral-500 focus:outline-none focus:ring-2 focus:ring-coral-100">
                    <option value="">Semua Aksi</option>
                    @foreach ($actions as $action)
                        <option value="{{ $action }}" @selected(request('action') === $action)>{{ $action }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-admin.label value="Dari Tanggal" />
                <x-admin.input name="from" type="date" :value="request('from')" />
            </div>
            <div>
                <x-admin.label value="Sampai Tanggal" />
                <x-admin.input name="until" type="date" :value="request('until')" />
            </div>
            <x-admin.btn type="submit" variant="primary">Filter</x-admin.btn>
            @if (request()->hasAny(['action', 'from', 'until']))
                <a href="{{ route('admin.activity-logs.index') }}" class="text-xs font-semibold text-ink-soft hover:text-ink">Reset</a>
            @endif
        </form>
    </x-admin.card>

    <x-admin.card class="overflow-hidden !p-0">
        @if ($logs->isEmpty())
            <p class="px-6 py-12 text-center text-sm text-ink-soft">Belum ada log aktivitas.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[640px] border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-line bg-surface">
                            <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-ink-soft">Waktu</th>
                            <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-ink-soft">Pengguna</th>
                            <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-ink-soft">Aksi</th>
                            <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-ink-soft">Subjek</th>
                            <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-ink-soft">IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($logs as $log)
                            <tr class="border-b border-line last:border-b-0">
                                <td class="whitespace-nowrap px-4 py-3.5 text-ink-soft">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-3.5 font-semibold">{{ $log->user?->name ?? 'Sistem' }}</td>
                                <td class="px-4 py-3.5"><x-admin.pill color="slate">{{ $log->action }}</x-admin.pill></td>
                                <td class="px-4 py-3.5 text-ink-soft">{{ $log->subject_type ? class_basename($log->subject_type).' #'.$log->subject_id : '-' }}</td>
                                <td class="px-4 py-3.5 font-mono text-xs text-ink-soft">{{ $log->ip_address ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="flex items-center justify-between border-t border-line px-5 py-3.5 text-xs text-ink-soft">
                <span>Menampilkan {{ $logs->firstItem() }}–{{ $logs->lastItem() }} dari {{ $logs->total() }}</span>
                {{ $logs->onEachSide(1)->links() }}
            </div>
        @endif
    </x-admin.card>
</x-admin-layout>
