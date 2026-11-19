<x-dashboard-layout role="company" title="Request Kontak">
    <x-card>
        @if ($requests->isEmpty())
            <p class="text-sm text-slate-500">Belum ada request kontak. Pilih kandidat dari shortlist untuk mengajukan.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead>
                        <tr class="text-left text-xs font-medium uppercase text-slate-500">
                            <th class="py-2 pr-4">No. Request</th>
                            <th class="py-2 pr-4">Posisi</th>
                            <th class="py-2 pr-4">Kandidat</th>
                            <th class="py-2 pr-4">Status</th>
                            <th class="py-2 pr-4">Diajukan</th>
                            <th class="py-2 pr-4"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($requests as $request)
                            <tr>
                                <td class="py-3 pr-4 font-medium text-slate-900">{{ $request->request_number }}</td>
                                <td class="py-3 pr-4 text-slate-600">{{ $request->position_title }}</td>
                                <td class="py-3 pr-4 text-slate-600">{{ $request->items_count }}</td>
                                <td class="py-3 pr-4"><x-badge :color="$request->status->value === 'completed' ? 'emerald' : 'amber'">{{ $request->status->label() }}</x-badge></td>
                                <td class="py-3 pr-4 text-slate-600">{{ $request->created_at->translatedFormat('d M Y') }}</td>
                                <td class="py-3 pr-4 text-right">
                                    <a href="{{ route('company.contact-requests.show', $request) }}" class="text-sm text-[#2457C5] hover:underline">Lihat Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $requests->links() }}</div>
        @endif
    </x-card>
</x-dashboard-layout>
