<x-dashboard-layout role="admin" title="Edit Perusahaan">
    <x-card>
        <form method="POST" action="{{ route('admin.companies.update', $company) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label for="name" value="Nama Perusahaan" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $company->name)" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="industry_id" value="Industri" />
                    <select id="industry_id" name="industry_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2457C5] focus:ring-[#2457C5]">
                        <option value="">Pilih industri</option>
                        @foreach ($industries as $industry)
                            <option value="{{ $industry->id }}" @selected((int) old('industry_id', $company->industry_id) === $industry->id)>{{ $industry->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label for="city" value="Kota" />
                    <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city', $company->city)" />
                </div>

                <div>
                    <x-input-label for="website" value="Website" />
                    <x-text-input id="website" name="website" type="url" class="mt-1 block w-full" :value="old('website', $company->website)" />
                </div>

                <div class="sm:col-span-2">
                    <x-input-label for="address" value="Alamat" />
                    <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $company->address)" />
                </div>

                <div>
                    <x-input-label for="pic_name" value="Nama PIC" />
                    <x-text-input id="pic_name" name="pic_name" type="text" class="mt-1 block w-full" :value="old('pic_name', $company->pic_name)" required />
                    <x-input-error :messages="$errors->get('pic_name')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="pic_position" value="Jabatan PIC" />
                    <x-text-input id="pic_position" name="pic_position" type="text" class="mt-1 block w-full" :value="old('pic_position', $company->pic_position)" required />
                    <x-input-error :messages="$errors->get('pic_position')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="pic_phone" value="Telepon PIC" />
                    <x-text-input id="pic_phone" name="pic_phone" type="text" class="mt-1 block w-full" :value="old('pic_phone', $company->pic_phone)" required />
                    <x-input-error :messages="$errors->get('pic_phone')" class="mt-1" />
                </div>
            </div>

            <div>
                <x-input-label for="description" value="Deskripsi" />
                <textarea id="description" name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2457C5] focus:ring-[#2457C5]">{{ old('description', $company->description) }}</textarea>
            </div>

            <div>
                <x-input-label for="logo" value="Logo" />
                @if ($company->logo_path)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($company->logo_path) }}" class="mb-2 h-16 w-16 rounded object-cover">
                @endif
                <input id="logo" name="logo" type="file" accept="image/*" class="mt-1 block text-sm">
                <x-input-error :messages="$errors->get('logo')" class="mt-1" />
            </div>

            <x-primary-button>Simpan Perubahan</x-primary-button>
        </form>
    </x-card>
</x-dashboard-layout>
