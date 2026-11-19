<x-guest-layout>
    <h1 class="text-xl font-bold text-slate-900">Daftar Mahasiswa</h1>
    <p class="mt-1 text-sm text-slate-500">Buat profil dan temukan peluang karier lewat kampus.</p>

    <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
        @csrf

        <div>
            <x-input-label for="name" value="Nama Lengkap" />
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-[#2457C5] focus:ring-[#2457C5]">
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="nim" value="NIM" />
            <input id="nim" type="text" name="nim" value="{{ old('nim', request('nim')) }}" required autocomplete="off"
                class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-[#2457C5] focus:ring-[#2457C5]">
            <x-input-error :messages="$errors->get('nim')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="study_program_id" value="Program Studi" />
            <select id="study_program_id" name="study_program_id" required
                class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-[#2457C5] focus:ring-[#2457C5]">
                <option value="">Pilih program studi</option>
                @foreach ($studyPrograms as $program)
                    <option value="{{ $program->id }}" @selected((int) old('study_program_id') === $program->id)>
                        {{ $program->name }} ({{ $program->level->value }})
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('study_program_id')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" value="Email Kampus" />
            <input id="email" type="email" name="email" value="{{ old('email', request('email')) }}" required autocomplete="username"
                class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-[#2457C5] focus:ring-[#2457C5]">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="Password" />
            <input id="password" type="password" name="password" required autocomplete="new-password"
                class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-[#2457C5] focus:ring-[#2457C5]">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Konfirmasi Password" />
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-[#2457C5] focus:ring-[#2457C5]">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div>
            <label for="consent" class="inline-flex items-start gap-2">
                <input id="consent" type="checkbox" name="consent" value="1" required
                    class="mt-1 rounded border-slate-300 text-[#2457C5] focus:ring-[#2457C5]">
                <span class="text-sm text-slate-600">
                    Saya telah membaca dan menyetujui
                    <a href="{{ route('privacy-policy') }}" target="_blank" class="font-medium text-[#2457C5] hover:underline">Kebijakan Privasi</a>.
                </span>
            </label>
            <x-input-error :messages="$errors->get('consent')" class="mt-2" />
        </div>

        <button type="submit" class="w-full rounded-lg bg-[#2457C5] px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-[#2457C5] focus:ring-offset-2">
            Daftar
        </button>

        <p class="text-center text-sm text-slate-500">
            Sudah punya akun? <a href="{{ route('login') }}" class="font-medium text-[#2457C5] hover:underline">Masuk</a>
        </p>
    </form>
</x-guest-layout>
