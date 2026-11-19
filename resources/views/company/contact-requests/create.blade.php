<x-dashboard-layout role="company" title="Ajukan Request Kontak">
    <x-card>
        @if ($students->isEmpty())
            <p class="text-sm text-slate-500">
                Tidak ada kandidat yang dipilih. Pilih kandidat dari halaman
                <a href="{{ route('company.shortlists.index') }}" class="text-[#2457C5] hover:underline">shortlist</a> terlebih dahulu.
            </p>
        @else
            <form method="POST" action="{{ route('company.contact-requests.store') }}" class="space-y-4">
                @csrf
                @foreach ($students as $student)
                    <input type="hidden" name="student_ids[]" value="{{ $student->id }}">
                @endforeach

                <div>
                    <x-input-label value="Kandidat Dipilih ({{ $students->count() }})" />
                    <div class="mt-1 flex flex-wrap gap-2">
                        @foreach ($students as $student)
                            <x-badge>{{ (new \App\Http\Resources\CandidateResource($student))->resolve()['short_name'] }}</x-badge>
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <x-input-label for="position_title" value="Posisi yang Ditawarkan" />
                        <x-text-input id="position_title" name="position_title" type="text" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('position_title')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="employment_type" value="Tipe" />
                        <select id="employment_type" name="employment_type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2457C5] focus:ring-[#2457C5]">
                            @foreach (\App\Enums\EmploymentType::cases() as $type)
                                <option value="{{ $type->value }}">{{ $type->label() }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('employment_type')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="work_location" value="Lokasi Kerja" />
                        <x-text-input id="work_location" name="work_location" type="text" class="mt-1 block w-full" />
                    </div>
                </div>

                <div>
                    <x-input-label for="message" value="Pesan untuk Admin Kampus" />
                    <textarea id="message" name="message" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2457C5] focus:ring-[#2457C5]"></textarea>
                </div>

                <x-input-error :messages="$errors->get('student_ids')" class="mt-1" />

                <x-primary-button>Kirim Request Kontak</x-primary-button>
            </form>
        @endif
    </x-card>
</x-dashboard-layout>
