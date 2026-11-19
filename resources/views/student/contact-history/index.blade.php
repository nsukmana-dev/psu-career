<x-dashboard-layout role="student" title="Riwayat Kontak">
    <x-card>
        @if ($items->isEmpty())
            <p class="text-sm text-slate-500">Belum ada perusahaan yang menerima kontakmu.</p>
        @else
            <ul class="divide-y divide-slate-100">
                @foreach ($items as $item)
                    <li class="py-3">
                        <p class="font-medium text-slate-900">{{ $item->contactRequest->company->name }}</p>
                        <p class="text-sm text-slate-600">{{ $item->contactRequest->position_title }} · {{ $item->contactRequest->employment_type->label() }}</p>
                        <p class="text-xs text-slate-400">Disetujui {{ $item->decided_at?->translatedFormat('d M Y') }}</p>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-card>
</x-dashboard-layout>
