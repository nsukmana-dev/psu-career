<x-dashboard-layout role="admin" title="Tambah Perusahaan Manual">
    <x-card>
        <form method="POST" action="{{ route('admin.companies.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label for="name" value="Nama Perusahaan" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="industry_id" value="Industri" />
                    <select id="industry_id" name="industry_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2457C5] focus:ring-[#2457C5]">
                        <option value="">Pilih industri</option>
                        @foreach ($industries as $industry)
                            <option value="{{ $industry->id }}" @selected((int) old('industry_id') === $industry->id)>{{ $industry->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label for="city" value="Kota" />
                    <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city')" />
                </div>

                <div>
                    <x-input-label for="website" value="Website" />
                    <x-text-input id="website" name="website" type="url" class="mt-1 block w-full" :value="old('website')" />
                </div>

                <div class="sm:col-span-2">
                    <x-input-label for="address" value="Alamat" />
                    <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address')" />
                </div>

                <div>
                    <x-input-label for="pic_name" value="Nama PIC" />
                    <x-text-input id="pic_name" name="pic_name" type="text" class="mt-1 block w-full" :value="old('pic_name')" required />
                    <x-input-error :messages="$errors->get('pic_name')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="pic_position" value="Jabatan PIC" />
                    <x-text-input id="pic_position" name="pic_position" type="text" class="mt-1 block w-full" :value="old('pic_position')" required />
                    <x-input-error :messages="$errors->get('pic_position')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="pic_phone" value="Telepon PIC" />
                    <x-text-input id="pic_phone" name="pic_phone" type="text" class="mt-1 block w-full" :value="old('pic_phone')" required />
                    <x-input-error :messages="$errors->get('pic_phone')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="email" value="Email Login PIC" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    <p class="mt-1 text-xs text-slate-500">Email undangan set password akan dikirim ke alamat ini.</p>
                </div>
            </div>

            <div>
                <x-input-label for="description" value="Deskripsi" />
                <textarea id="description" name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2457C5] focus:ring-[#2457C5]">{{ old('description') }}</textarea>
            </div>

            <div>
                <x-input-label for="logo" value="Logo (opsional)" />
                <input id="logo" name="logo" type="file" accept="image/*" class="mt-1 block text-sm">
                <x-input-error :messages="$errors->get('logo')" class="mt-1" />
            </div>

            <x-primary-button>Buat Perusahaan &amp; Kirim Undangan</x-primary-button>
        </form>
    </x-card>
</x-dashboard-layout>
