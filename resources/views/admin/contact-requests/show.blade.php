@php
    $statusColors = ['pending' => 'warn', 'approved' => 'good', 'rejected' => 'bad'];
@endphp

<x-admin-layout :title="$contactRequest->request_number">
    <x-admin.card :title="$contactRequest->position_title.' — '.$contactRequest->company->name">
        <dl class="grid grid-cols-1 gap-4 text-sm sm:grid-cols-3">
            <div><dt class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Tipe</dt><dd class="font-semibold">{{ $contactRequest->employment_type->label() }}</dd></div>
            <div><dt class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Lokasi</dt><dd class="font-semibold">{{ $contactRequest->work_location ?? '-' }}</dd></div>
            <div><dt class="mb-1 text-[11px] font-bold uppercase tracking-wide text-ink-soft">Status</dt><dd><x-admin.pill :color="$contactRequest->status->value === 'completed' ? 'good' : 'warn'">{{ $contactRequest->status->label() }}</x-admin.pill></dd></div>
        </dl>
        @if ($contactRequest->message)
            <p class="mt-4 text-sm text-ink-soft">{{ $contactRequest->message }}</p>
        @endif

        @if ($contactRequest->items->contains(fn ($item) => $item->status->value === 'pending'))
            <form method="POST" action="{{ route('admin.contact-requests.approve-all', $contactRequest) }}" class="mt-5" onsubmit="return confirm('Setujui semua item yang masih menunggu?')">
                @csrf
                <x-admin.btn type="submit" variant="primary">Setujui Semua</x-admin.btn>
            </form>
        @endif
    </x-admin.card>

    <div class="mt-5 space-y-3.5">
        @foreach ($contactRequest->items as $item)
            <x-admin.card>
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="font-semibold">{{ $item->student->user->name }} ({{ $item->student->nim }})</p>
                        <p class="text-sm text-ink-soft">{{ $item->student->studyProgram->name }}</p>
                        @if ($item->rejection_reason)
                            <p class="mt-1 text-xs text-bad">Alasan: {{ $item->rejection_reason }}</p>
                        @endif
                    </div>

                    <div class="flex items-center gap-2.5" x-data="{ open: false }">
                        <x-admin.pill :color="$statusColors[$item->status->value]">{{ $item->status->label() }}</x-admin.pill>

                        @if ($item->status->value === 'pending')
                            <form method="POST" action="{{ route('admin.contact-request-items.approve', $item) }}">
                                @csrf
                                <x-admin.btn type="submit" variant="primary" class="!px-3.5 !py-1.5 !text-xs">Setujui</x-admin.btn>
                            </form>

                            <x-admin.btn type="button" variant="ghost" class="!px-3.5 !py-1.5 !text-xs text-bad" x-show="!open" @click="open = true">Tolak</x-admin.btn>
                            <form x-show="open" x-cloak method="POST" action="{{ route('admin.contact-request-items.reject', $item) }}" class="flex items-center gap-2">
                                @csrf
                                <input type="text" name="rejection_reason" placeholder="Alasan penolakan" required class="rounded-xl border border-line px-3 py-2 text-xs focus:border-bad focus:outline-none focus:ring-2 focus:ring-bad-soft">
                                <x-admin.btn type="submit" variant="danger" class="!px-3.5 !py-1.5 !text-xs">Kirim</x-admin.btn>
                            </form>
                        @endif
                    </div>
                </div>
            </x-admin.card>
        @endforeach
    </div>
</x-admin-layout>
