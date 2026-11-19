@php $c = $candidate->resolve(); @endphp

<x-dashboard-layout role="company" title="Profil Kandidat">
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <x-card title="{{ $c['short_name'] }}">
                <dl class="grid grid-cols-1 gap-3 sm:grid-cols-2 text-sm">
                    <div><dt class="text-slate-500">Program Studi</dt><dd class="font-medium text-slate-900">{{ $c['study_program'] }}</dd></div>
                    <div><dt class="text-slate-500">Semester</dt><dd class="font-medium text-slate-900">{{ $c['semester'] }}</dd></div>
                    <div><dt class="text-slate-500">IPK</dt><dd class="font-medium text-slate-900">{{ $c['gpa'] }}</dd></div>
                    <div><dt class="text-slate-500">Domisili</dt><dd class="font-medium text-slate-900">{{ $c['city'] ?? '-' }}</dd></div>
                </dl>

                @if (count($c['skills']))
                    <div class="mt-4">
                        @foreach ($c['skills'] as $skill)
                            <x-badge class="mb-1 mr-1">{{ $skill['name'] }} · {{ $skill['level'] }}</x-badge>
                        @endforeach
                    </div>
                @endif

                <p class="mt-4 text-xs text-slate-400">
                    @switch ($c['contact_status'])
                        @case('shared')
                            Kontak kandidat sudah dibagikan. Lihat di <a href="{{ route('company.contacts.index') }}" class="text-[#2457C5] hover:underline">Kontak Saya</a>.
                            @break
                        @case('pending')
                            Request kontak untuk kandidat ini sedang menunggu persetujuan admin kampus.
                            @break
                        @default
                            Kontak kandidat disembunyikan sampai request kontak disetujui admin kampus.
                    @endswitch
                </p>
            </x-card>

            <x-card title="Preview CV" class="mt-6">
                <iframe src="{{ route('company.candidates.cv-preview', $student) }}" class="h-[800px] w-full rounded-lg border border-slate-200"></iframe>
            </x-card>
        </div>

        <div>
            <x-card title="Tambahkan ke Shortlist">
                @if ($shortlists->isEmpty())
                    <p class="text-sm text-slate-500">Anda belum punya shortlist. <a href="{{ route('company.shortlists.index') }}" class="text-[#2457C5] hover:underline">Buat shortlist</a> terlebih dahulu.</p>
                @else
                    <form method="POST" action="{{ route('company.shortlists.items.quick-add') }}">
                        @csrf
                        <input type="hidden" name="student_id" value="{{ $student->id }}">

                        <x-input-label for="shortlist_id" value="Pilih Shortlist" />
                        <select id="shortlist_id" name="shortlist_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2457C5] focus:ring-[#2457C5]">
                            @foreach ($shortlists as $shortlist)
                                <option value="{{ $shortlist->id }}">{{ $shortlist->name }}</option>
                            @endforeach
                        </select>

                        <x-input-label for="note" value="Catatan (opsional)" class="mt-3" />
                        <textarea id="note" name="note" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2457C5] focus:ring-[#2457C5]"></textarea>

                        <x-primary-button class="mt-3 w-full justify-center">Tambahkan</x-primary-button>
                    </form>
                @endif
            </x-card>
        </div>
    </div>
</x-dashboard-layout>
