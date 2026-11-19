<x-dashboard-layout role="admin" title="Tambah Mahasiswa">
    <x-card>
        <form method="POST" action="{{ route('admin.students.store') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label for="name" value="Nama Lengkap" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="email" value="Email" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    <p class="mt-1 text-xs text-slate-500">Email untuk login & terima tautan atur password.</p>
                </div>

                <div>
                    <x-input-label for="nim" value="NIM" />
                    <x-text-input id="nim" name="nim" type="text" class="mt-1 block w-full" :value="old('nim')" required />
                    <x-input-error :messages="$errors->get('nim')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="study_program_id" value="Program Studi" />
                    <select id="study_program_id" name="study_program_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2457C5] focus:ring-[#2457C5]" required>
                        <option value="">Pilih program studi</option>
                        @foreach ($studyPrograms as $program)
                            <option value="{{ $program->id }}" @selected((int) old('study_program_id') === $program->id)>{{ $program->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('study_program_id')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="entry_year" value="Angkatan" />
                    <x-text-input id="entry_year" name="entry_year" type="number" class="mt-1 block w-full" :value="old('entry_year', now()->year)" />
                    <x-input-error :messages="$errors->get('entry_year')" class="mt-1" />
                </div>
            </div>

            <x-primary-button>Buat Akun &amp; Kirim Undangan</x-primary-button>
        </form>
    </x-card>
</x-dashboard-layout>
