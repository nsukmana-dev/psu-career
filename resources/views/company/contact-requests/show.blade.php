@php
    $statusColors = ['pending' => 'amber', 'approved' => 'emerald', 'rejected' => 'red'];
@endphp

<x-dashboard-layout role="company" :title="$contactRequest->request_number">
    <x-card title="{{ $contactRequest->position_title }}">
        <dl class="grid grid-cols-1 gap-3 sm:grid-cols-3 text-sm">
            <div><dt class="text-slate-500">Tipe</dt><dd class="font-medium text-slate-900">{{ $contactRequest->employment_type->label() }}</dd></div>
            <div><dt class="text-slate-500">Lokasi</dt><dd class="font-medium text-slate-900">{{ $contactRequest->work_location ?? '-' }}</dd></div>
            <div><dt class="text-slate-500">Status</dt><dd><x-badge :color="$contactRequest->status->value === 'completed' ? 'emerald' : 'amber'">{{ $contactRequest->status->label() }}</x-badge></dd></div>
        </dl>
        @if ($contactRequest->message)
            <p class="mt-3 text-sm text-slate-600">{{ $contactRequest->message }}</p>
        @endif
    </x-card>

    <x-card title="Kandidat" class="mt-6">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead>
                <tr class="text-left text-xs font-medium uppercase text-slate-500">
                    <th class="py-2 pr-4">Kandidat</th>
                    <th class="py-2 pr-4">Program Studi</th>
                    <th class="py-2 pr-4">Status</th>
                    <th class="py-2 pr-4">Alasan Penolakan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($contactRequest->items as $item)
                    <tr>
                        <td class="py-3 pr-4 font-medium text-slate-900">{{ (new \App\Http\Resources\CandidateResource($item->student))->resolve()['short_name'] }}</td>
                        <td class="py-3 pr-4 text-slate-600">{{ $item->student->studyProgram->name }}</td>
                        <td class="py-3 pr-4"><x-badge :color="$statusColors[$item->status->value]">{{ $item->status->label() }}</x-badge></td>
                        <td class="py-3 pr-4 text-slate-600">{{ $item->rejection_reason ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-card>
</x-dashboard-layout>
