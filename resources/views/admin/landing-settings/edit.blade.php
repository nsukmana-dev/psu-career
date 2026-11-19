<x-admin-layout title="Landing Page">
    <x-admin.card title="Pengaturan Landing Page">
        <form method="POST" action="{{ route('admin.landing-settings.update') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                @foreach ($settings as $key => $setting)
                    @continue($setting->type === \App\Enums\LandingSettingType::Image)
                    <div class="{{ $setting->type === \App\Enums\LandingSettingType::Textarea ? 'sm:col-span-2' : '' }}">
                        <x-admin.label :for="$key" :value="str($key)->replace('_', ' ')->title()" />
                        @if ($setting->type === \App\Enums\LandingSettingType::Textarea)
                            <textarea id="{{ $key }}" name="{{ $key }}" rows="3" class="w-full rounded-xl border border-line bg-cream px-3 py-2.5 text-sm focus:border-coral-500 focus:outline-none focus:ring-2 focus:ring-coral-100">{{ old($key, $setting->value) }}</textarea>
                        @else
                            <x-admin.input :id="$key" :name="$key" :type="$setting->type === \App\Enums\LandingSettingType::Number ? 'number' : 'text'" :value="old($key, $setting->value)" />
                        @endif
                        <x-input-error :messages="$errors->get($key)" class="mt-1" />
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                @foreach ($settings as $key => $setting)
                    @continue($setting->type !== \App\Enums\LandingSettingType::Image)
                    <div>
                        <x-admin.label :for="$key" :value="str($key)->replace('_', ' ')->title()" />
                        @if (\Illuminate\Support\Facades\Storage::disk('public')->exists($setting->value))
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($setting->value) }}" class="mb-2 h-20 w-20 rounded-xl border border-line object-cover">
                        @endif
                        <input id="{{ $key }}" name="{{ $key }}" type="file" accept="image/*" class="block text-sm">
                        <x-input-error :messages="$errors->get($key)" class="mt-1" />
                    </div>
                @endforeach
            </div>

            <x-admin.btn type="submit" variant="primary">Simpan Perubahan</x-admin.btn>
        </form>
    </x-admin.card>
</x-admin-layout>
