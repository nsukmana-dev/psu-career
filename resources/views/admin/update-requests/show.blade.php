@php
    $fieldLabels = [
        'current_semester' => 'Semester',
        'gpa' => 'IPK',
        'study_program_id' => 'Program Studi',
        'entry_year' => 'Angkatan',
    ];

    $resolve = function (string $field, $value) {
        if ($field === 'study_program_id') {
            return \App\Models\StudyProgram::find($value)?->name ?? '-';
        }

        return $value;
    };
@endphp

<x-admin-layout title="Tinjau Perubahan Data Akademik">
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <x-admin.card title="Perbandingan Data">
                <p class="mb-4 text-sm text-ink-soft">
                    {{ $updateRequest->student->user->name }} ({{ $updateRequest->student->nim }})
                </p>

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
                            @foreach ($updateRequest->changes as $field => $change)
                                <tr class="border-b border-line last:border-0">
                                    <td class="px-3 py-2.5 font-semibold">{{ $fieldLabels[$field] ?? $field }}</td>
                                    <td class="px-3 py-2.5 text-bad line-through">{{ $resolve($field, $change['old']) }}</td>
                                    <td class="px-3 py-2.5 text-good">{{ $resolve($field, $change['new']) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-admin.card>
        </div>

        <div>
            <x-admin.card title="Keputusan">
                <x-admin.pill :color="$updateRequest->status->value === 'approved' ? 'good' : ($updateRequest->status->value === 'rejected' ? 'bad' : 'warn')" class="mb-4">{{ $updateRequest->status->label() }}</x-admin.pill>

                <form method="POST" action="{{ route('admin.update-requests.approve', $updateRequest) }}" class="mb-3">
                    @csrf
                    <x-admin.label for="approve_note" value="Catatan (opsional)" />
                    <textarea id="approve_note" name="note" rows="2" class="mb-2.5 w-full rounded-xl border border-line bg-cream px-3 py-2.5 text-sm focus:border-coral-500 focus:outline-none focus:ring-2 focus:ring-coral-100"></textarea>
                    <x-admin.btn type="submit" variant="primary" class="w-full">Setujui Perubahan</x-admin.btn>
                </form>

                <form method="POST" action="{{ route('admin.update-requests.reject', $updateRequest) }}">
                    @csrf
                    <x-admin.label for="reject_note" value="Alasan Penolakan" />
                    <textarea id="reject_note" name="note" rows="2" required class="mb-2.5 w-full rounded-xl border border-line bg-cream px-3 py-2.5 text-sm focus:border-bad focus:outline-none focus:ring-2 focus:ring-bad-soft"></textarea>
                    <x-input-error :messages="$errors->get('note')" class="mb-2.5" />
                    <x-admin.btn type="submit" variant="danger" class="w-full">Tolak Perubahan</x-admin.btn>
                </form>
            </x-admin.card>
        </div>
    </div>
</x-admin-layout>
