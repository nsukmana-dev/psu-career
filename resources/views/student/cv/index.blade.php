<x-dashboard-layout role="student" title="CV Builder">
    <x-card title="Pengaturan CV">
        <form method="POST" action="{{ route('student.cv.settings') }}" class="flex flex-wrap items-end gap-4">
            @csrf
            @method('PUT')

            <div>
                <x-input-label for="default_cv_template_id" value="Template Default" />
                <select id="default_cv_template_id" name="default_cv_template_id" required
                    class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-[#2457C5] focus:ring-[#2457C5]">
                    @foreach ($templates as $template)
                        <option value="{{ $template->id }}" @selected($student->default_cv_template_id === $template->id)>{{ $template->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <x-input-label for="cv_accent_color" value="Warna Aksen" />
                <input id="cv_accent_color" name="cv_accent_color" type="color" value="{{ $student->cv_accent_color ?? '#0F2A4A' }}"
                    class="mt-1 block h-10 w-16 rounded-md border-gray-300">
            </div>

            <x-primary-button>Simpan Pengaturan</x-primary-button>
        </form>
        <x-input-error :messages="$errors->get('cv_accent_color')" class="mt-2" />
        <x-input-error :messages="$errors->get('default_cv_template_id')" class="mt-2" />
    </x-card>

    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($templates as $template)
            <x-card>
                <div class="mb-3 flex h-32 items-center justify-center rounded-lg bg-slate-100 text-sm text-slate-400">
                    {{ $template->name }}
                </div>
                <p class="text-sm font-medium text-slate-900">{{ $template->name }}</p>
                @if ($student->default_cv_template_id === $template->id)
                    <x-badge color="emerald" class="mt-1">Default</x-badge>
                @endif
                <div class="mt-3 flex gap-3 text-xs">
                    <a href="{{ route('student.cv.preview', $template->slug) }}" target="_blank" class="text-[#2457C5] hover:underline">Pratinjau</a>
                    <a href="{{ route('student.cv.download', $template->slug) }}" class="text-[#2457C5] hover:underline">Unduh PDF</a>
                </div>
            </x-card>
        @endforeach
    </div>
</x-dashboard-layout>
