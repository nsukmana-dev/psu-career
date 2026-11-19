<x-dashboard-layout role="company" title="Cari Kandidat">
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
        <div class="lg:col-span-1 space-y-6">
            <x-card title="Filter">
                <form method="GET" action="{{ route('company.candidates.index') }}" class="space-y-4 text-sm">
                    <div>
                        <x-input-label value="Program Studi" />
                        <div class="mt-1 max-h-36 space-y-1 overflow-y-auto rounded-lg border border-slate-300 p-2">
                            @foreach ($studyPrograms as $program)
                                <label class="flex items-center gap-2 rounded px-1 py-0.5 text-sm text-slate-700 hover:bg-slate-50">
                                    <input type="checkbox" name="study_program_ids[]" value="{{ $program->id }}" class="rounded border-slate-300 text-[#2457C5] focus:ring-[#2457C5]" @checked(in_array($program->id, (array) request('study_program_ids', [])))>
                                    {{ $program->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <x-input-label for="min_semester" value="Semester Minimal" />
                        <x-text-input id="min_semester" name="min_semester" type="number" min="1" max="14" class="mt-1 block w-full" :value="request('min_semester')" />
                    </div>

                    <div>
                        <x-input-label for="entry_year" value="Angkatan" />
                        <x-text-input id="entry_year" name="entry_year" type="number" class="mt-1 block w-full" :value="request('entry_year')" />
                    </div>

                    <div>
                        <x-input-label for="min_gpa" value="IPK Minimal" />
                        <x-text-input id="min_gpa" name="min_gpa" type="number" step="0.01" min="0" max="4" class="mt-1 block w-full" :value="request('min_gpa')" />
                    </div>

                    <div>
                        <x-input-label value="Skill" />
                        <div class="mt-1 max-h-40 space-y-1 overflow-y-auto rounded-lg border border-slate-300 p-2">
                            @foreach ($skills as $skill)
                                <label class="flex items-center gap-2 rounded px-1 py-0.5 text-sm text-slate-700 hover:bg-slate-50">
                                    <input type="checkbox" name="skill_ids[]" value="{{ $skill->id }}" class="rounded border-slate-300 text-[#2457C5] focus:ring-[#2457C5]" @checked(in_array($skill->id, (array) request('skill_ids', [])))>
                                    {{ $skill->name }}
                                </label>
                            @endforeach
                        </div>
                        <div class="mt-1 flex gap-3 text-xs text-slate-600">
                            <label><input type="radio" name="skill_mode" value="any" @checked(request('skill_mode', 'any') === 'any')> Salah satu</label>
                            <label><input type="radio" name="skill_mode" value="all" @checked(request('skill_mode') === 'all')> Semua</label>
                        </div>
                    </div>

                    <div>
                        <x-input-label for="city" value="Domisili (Kota)" />
                        <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="request('city')" />
                    </div>

                    <div>
                        <x-input-label for="job_interest" value="Minat" />
                        <select id="job_interest" name="job_interest" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-[#2457C5] focus:ring-[#2457C5]">
                            <option value="">Semua</option>
                            <option value="internship" @selected(request('job_interest') === 'internship')>Magang</option>
                            <option value="fulltime" @selected(request('job_interest') === 'fulltime')>Full-time</option>
                        </select>
                    </div>

                    <label class="flex items-center gap-2 text-xs text-slate-600">
                        <input type="checkbox" name="willing_to_relocate" value="1" @checked(request('willing_to_relocate'))>
                        Bersedia luar kota
                    </label>

                    <div>
                        <x-input-label for="keyword" value="Kata Kunci" />
                        <x-text-input id="keyword" name="keyword" type="text" class="mt-1 block w-full" :value="request('keyword')" placeholder="Nama, ringkasan, judul portfolio" />
                    </div>

                    <div>
                        <x-input-label for="sort" value="Urutkan" />
                        <select id="sort" name="sort" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-[#2457C5] focus:ring-[#2457C5]">
                            <option value="relevance" @selected(request('sort', 'relevance') === 'relevance')>Relevansi</option>
                            <option value="gpa_desc" @selected(request('sort') === 'gpa_desc')>IPK Tertinggi</option>
                            <option value="semester_desc" @selected(request('sort') === 'semester_desc')>Semester Tertinggi</option>
                            <option value="updated_desc" @selected(request('sort') === 'updated_desc')>Terbaru Diperbarui</option>
                        </select>
                    </div>

                    <x-primary-button class="w-full justify-center">Terapkan Filter</x-primary-button>
                </form>

                @if (request()->query() !== [])
                    <form method="POST" action="{{ route('company.saved-searches.store') }}" class="mt-4 border-t border-slate-100 pt-4">
                        @csrf
                        @foreach (request()->query() as $key => $value)
                            @foreach (Illuminate\Support\Arr::wrap($value) as $v)
                                <input type="hidden" name="filters[{{ $key }}]{{ is_array($value) ? '[]' : '' }}" value="{{ $v }}">
                            @endforeach
                        @endforeach
                        <x-input-label for="saved_search_name" value="Simpan filter ini" />
                        <div class="mt-1 flex gap-2">
                            <x-text-input id="saved_search_name" name="name" type="text" class="block w-full" placeholder="Nama filter" required />
                            <x-secondary-button type="submit">Simpan</x-secondary-button>
                        </div>
                    </form>
                @endif
            </x-card>

            @if ($savedSearches->isNotEmpty())
                <x-card title="Filter Tersimpan">
                    <ul class="space-y-2 text-sm">
                        @foreach ($savedSearches as $saved)
                            <li class="flex items-center justify-between">
                                <a href="{{ route('company.saved-searches.apply', $saved) }}" class="text-[#2457C5] hover:underline">{{ $saved->name }}</a>
                                <form method="POST" action="{{ route('company.saved-searches.destroy', $saved) }}" onsubmit="return confirm('Hapus filter ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:underline">Hapus</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                </x-card>
            @endif
        </div>

        <div class="lg:col-span-3">
            <x-card>
                <p class="mb-4 text-sm text-slate-500">{{ $candidates->total() }} kandidat ditemukan.</p>

                @if ($candidates->isEmpty())
                    <p class="text-sm text-slate-500">Tidak ada kandidat yang cocok dengan filter ini.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead>
                                <tr class="text-left text-xs font-medium uppercase text-slate-500">
                                    <th class="py-2 pr-4">Nama</th>
                                    <th class="py-2 pr-4">Program Studi</th>
                                    <th class="py-2 pr-4">Semester</th>
                                    <th class="py-2 pr-4">IPK</th>
                                    <th class="py-2 pr-4">Skill</th>
                                    <th class="py-2 pr-4">Kontak</th>
                                    <th class="py-2 pr-4"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($candidates as $candidate)
                                    @php $c = $candidate->resolve(); @endphp
                                    <tr class="hover:bg-slate-50">
                                        <td class="py-3 pr-4">
                                            <div class="flex items-center gap-3">
                                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#2457C5]/10 text-xs font-semibold text-[#2457C5]">{{ strtoupper(substr($c['short_name'], 0, 1)) }}</span>
                                                <span class="font-medium text-slate-900">{{ $c['short_name'] }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3 pr-4 text-slate-600">{{ $c['study_program'] }}</td>
                                        <td class="py-3 pr-4 text-slate-600">{{ $c['semester'] }}</td>
                                        <td class="py-3 pr-4 text-slate-600">{{ $c['gpa'] }}</td>
                                        <td class="py-3 pr-4">
                                            @foreach ($c['skills'] as $skill)
                                                <x-badge class="mb-1 mr-1">{{ $skill['name'] }}</x-badge>
                                            @endforeach
                                        </td>
                                        <td class="py-3 pr-4">
                                            @php
                                                [$statusColor, $statusLabel] = match ($c['contact_status']) {
                                                    'shared' => ['emerald', 'Sudah dibagikan'],
                                                    'pending' => ['amber', 'Menunggu'],
                                                    default => ['slate', 'Disembunyikan'],
                                                };
                                            @endphp
                                            <x-badge :color="$statusColor">{{ $statusLabel }}</x-badge>
                                        </td>
                                        <td class="py-3 pr-4 text-right">
                                            <a href="{{ route('company.candidates.show', $c['id']) }}" class="text-sm text-[#2457C5] hover:underline">Lihat Profil</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">{{ $candidates->links() }}</div>
                @endif
            </x-card>
        </div>
    </div>
</x-dashboard-layout>
