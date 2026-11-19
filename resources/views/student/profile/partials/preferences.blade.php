<x-card title="Preferensi Kerja">
    <form method="POST" action="{{ route('student.preferences.update') }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <x-input-label for="job_interest" value="Minat" />
            <select id="job_interest" name="job_interest" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2457C5] focus:ring-[#2457C5]">
                @foreach (\App\Enums\JobInterest::cases() as $interest)
                    <option value="{{ $interest->value }}" @selected($student->job_interest === $interest)>{{ $interest->label() }}</option>
                @endforeach
            </select>
        </div>

        <label class="flex items-center gap-2 text-sm text-slate-600">
            <input type="checkbox" name="willing_to_relocate" value="1" class="rounded border-gray-300 text-[#2457C5]" @checked($student->willing_to_relocate)>
            Bersedia ditempatkan di luar kota
        </label>

        <label class="flex items-center gap-2 text-sm text-slate-600">
            <input type="checkbox" name="open_to_contact" value="1" class="rounded border-gray-300 text-[#2457C5]" @checked($student->open_to_contact)>
            Bersedia dihubungi perusahaan
        </label>
        <p class="text-xs text-slate-500">
            Wajib dicentang supaya profil kamu muncul di pencarian kandidat perusahaan (hanya setelah profil terverifikasi).
        </p>

        <x-primary-button>Simpan Preferensi</x-primary-button>
    </form>
</x-card>
