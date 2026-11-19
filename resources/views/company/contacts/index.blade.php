<x-dashboard-layout role="company" title="Kontak Saya">
    <x-card>
        @if ($contacts->isEmpty())
            <p class="text-sm text-slate-500">Belum ada kontak yang dibagikan. Kontak muncul di sini setelah admin kampus menyetujui request kontak Anda.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead>
                        <tr class="text-left text-xs font-medium uppercase text-slate-500">
                            <th class="py-2 pr-4">Nama</th>
                            <th class="py-2 pr-4">Program Studi</th>
                            <th class="py-2 pr-4">Posisi</th>
                            <th class="py-2 pr-4">Email</th>
                            <th class="py-2 pr-4">No. HP</th>
                            <th class="py-2 pr-4">LinkedIn</th>
                            <th class="py-2 pr-4">Disetujui</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($contacts as $item)
                            <tr>
                                <td class="py-3 pr-4 font-medium text-slate-900">{{ $item->student->user->name }}</td>
                                <td class="py-3 pr-4 text-slate-600">{{ $item->student->studyProgram->name }}</td>
                                <td class="py-3 pr-4 text-slate-600">{{ $item->contactRequest->position_title }}</td>
                                <td class="py-3 pr-4 text-slate-600">{{ $item->student->user->email }}</td>
                                <td class="py-3 pr-4 text-slate-600">{{ $item->student->phone ?? '-' }}</td>
                                <td class="py-3 pr-4 text-slate-600">
                                    @if ($item->student->linkedin_url)
                                        <a href="{{ $item->student->linkedin_url }}" target="_blank" class="text-[#2457C5] hover:underline">Profil</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="py-3 pr-4 text-slate-600">{{ $item->decided_at?->translatedFormat('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $contacts->links() }}</div>
        @endif
    </x-card>
</x-dashboard-layout>
