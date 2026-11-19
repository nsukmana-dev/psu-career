<x-card>
    <form method="POST" action="{{ route('student.profile.update') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PATCH')
        <input type="hidden" name="_tab" value="data-diri">

        <div class="flex items-center gap-4">
            <img
                src="{{ $student->photo_path ? \Illuminate\Support\Facades\Storage::url($student->photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($student->user->name) }}"
                alt="Foto profil"
                class="h-16 w-16 rounded-full object-cover border border-slate-200"
            >
            <div>
                <x-input-label for="photo" value="Foto Profil" />
                <input id="photo" name="photo" type="file" accept="image/*" class="mt-1 block text-sm">
                <x-input-error :messages="$errors->get('photo')" class="mt-1" />
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <x-input-label for="name" value="Nama Lengkap" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $student->user->name)" required />
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="nim" value="NIM" />
                <x-text-input id="nim" type="text" class="mt-1 block w-full bg-slate-100" value="{{ $student->nim }}" disabled />
            </div>

            <div>
                <x-input-label for="study_program_id" value="Program Studi" />
                <select id="study_program_id" name="study_program_id" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2457C5] focus:ring-[#2457C5]">
                    @foreach ($studyPrograms as $program)
                        <option value="{{ $program->id }}" @selected(old('study_program_id', $student->study_program_id) == $program->id)>
                            {{ $program->name }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('study_program_id')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="entry_year" value="Angkatan" />
                <x-text-input id="entry_year" name="entry_year" type="number" class="mt-1 block w-full" :value="old('entry_year', $student->entry_year)" required />
                <x-input-error :messages="$errors->get('entry_year')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="current_semester" value="Semester Saat Ini" />
                <x-text-input id="current_semester" name="current_semester" type="number" min="1" max="14" class="mt-1 block w-full" :value="old('current_semester', $student->current_semester)" required />
                <x-input-error :messages="$errors->get('current_semester')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="gpa" value="IPK" />
                <x-text-input id="gpa" name="gpa" type="number" step="0.01" min="0" max="4" class="mt-1 block w-full" :value="old('gpa', $student->gpa)" required />
                <x-input-error :messages="$errors->get('gpa')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="gender" value="Jenis Kelamin" />
                <select id="gender" name="gender" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2457C5] focus:ring-[#2457C5]">
                    @foreach (\App\Enums\Gender::cases() as $gender)
                        <option value="{{ $gender->value }}" @selected(old('gender', $student->gender?->value) === $gender->value)>
                            {{ $gender->label() }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('gender')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="birth_date" value="Tanggal Lahir" />
                <x-text-input id="birth_date" name="birth_date" type="date" class="mt-1 block w-full" :value="old('birth_date', $student->birth_date?->format('Y-m-d'))" />
                <x-input-error :messages="$errors->get('birth_date')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="city" value="Domisili (Kota)" />
                <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city', $student->city)" />
                <x-input-error :messages="$errors->get('city')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="phone" value="No. HP" />
                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $student->phone)" />
                <x-input-error :messages="$errors->get('phone')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="linkedin_url" value="LinkedIn" />
                <x-text-input id="linkedin_url" name="linkedin_url" type="url" class="mt-1 block w-full" :value="old('linkedin_url', $student->linkedin_url)" />
                <x-input-error :messages="$errors->get('linkedin_url')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="portfolio_url" value="GitHub / Portfolio URL" />
                <x-text-input id="portfolio_url" name="portfolio_url" type="url" class="mt-1 block w-full" :value="old('portfolio_url', $student->portfolio_url)" />
                <x-input-error :messages="$errors->get('portfolio_url')" class="mt-1" />
            </div>
        </div>

        <div>
            <x-input-label for="summary" value="Ringkasan Diri" />
            <textarea id="summary" name="summary" rows="4" maxlength="600"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2457C5] focus:ring-[#2457C5]">{{ old('summary', $student->summary) }}</textarea>
            <x-input-error :messages="$errors->get('summary')" class="mt-1" />
        </div>

        @if ($student->verification_status === \App\Enums\VerificationStatus::Verified)
            <p class="text-xs text-slate-500">
                Profil sudah terverifikasi: perubahan program studi, angkatan, semester, dan IPK akan menunggu persetujuan admin.
            </p>
        @endif

        <x-primary-button>Simpan Data Diri</x-primary-button>
    </form>
</x-card>
