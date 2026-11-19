<x-dashboard-layout role="company" :title="$shortlist->name">
    <x-card>
        @if ($shortlist->items->isEmpty())
            <p class="text-sm text-slate-500">Belum ada kandidat di shortlist ini. Tambahkan dari halaman pencarian kandidat.</p>
        @else
            <form method="GET" action="{{ route('company.contact-requests.create') }}" id="select-candidates-form"></form>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead>
                        <tr class="text-left text-xs font-medium uppercase text-slate-500">
                            <th class="py-2 pr-4"></th>
                            <th class="py-2 pr-4">Kandidat</th>
                            <th class="py-2 pr-4">Program Studi</th>
                            <th class="py-2 pr-4">Catatan</th>
                            <th class="py-2 pr-4"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($shortlist->items as $item)
                            <tr>
                                <td class="py-3 pr-4">
                                    <input form="select-candidates-form" type="checkbox" name="student_ids[]" value="{{ $item->student_id }}" class="rounded border-gray-300 text-[#2457C5]">
                                </td>
                                <td class="py-3 pr-4 font-medium text-slate-900">{{ (new \App\Http\Resources\CandidateResource($item->student))->resolve()['short_name'] }}</td>
                                <td class="py-3 pr-4 text-slate-600">{{ $item->student->studyProgram->name }}</td>
                                <td class="py-3 pr-4 text-slate-600">{{ $item->note ?? '-' }}</td>
                                <td class="py-3 pr-4 text-right space-x-3">
                                    <a href="{{ route('company.candidates.show', $item->student) }}" class="text-sm text-[#2457C5] hover:underline">Lihat Profil</a>
                                    <form method="POST" action="{{ route('company.shortlists.items.destroy', [$shortlist, $item]) }}" class="inline" onsubmit="return confirm('Hapus dari shortlist?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <button type="submit" form="select-candidates-form" class="mt-4 inline-flex items-center rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">
                Ajukan Request Kontak untuk yang Dipilih
            </button>
        @endif
    </x-card>
</x-dashboard-layout>
