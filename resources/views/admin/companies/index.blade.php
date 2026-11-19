<x-admin-layout title="Perusahaan Mitra">
    <div class="mb-6 grid grid-cols-2 gap-3.5 lg:grid-cols-3">
        <x-admin.stat-card label="Perusahaan Aktif" :value="$activeCount" />
        <x-admin.stat-card label="Ditangguhkan" :value="$suspendedCount" trend="perlu ditinjau" trend-color="warn" />
    </div>

    <div x-data="{
        open: false,
        mode: 'create',
        form: { id: null, name: '', industry_id: '', city: '', website: '', address: '', description: '', pic_name: '', pic_position: '', pic_phone: '', email: '', logo_url: null, status: '', action_url: '', suspend_url: '', activate_url: '', reset_url: '' },
        openCreate() {
            this.mode = 'create';
            this.form = { id: null, name: '', industry_id: '', city: '', website: '', address: '', description: '', pic_name: '', pic_position: '', pic_phone: '', email: '', logo_url: null, status: '', action_url: '{{ route('admin.companies.store') }}', suspend_url: '', activate_url: '', reset_url: '' };
            this.open = true;
        },
        openEdit(data) {
            this.mode = 'edit';
            this.form = data;
            this.open = true;
        }
    }">
        <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2.5">
                <form method="GET" class="relative min-w-[220px] flex-1">
                    <input type="hidden" name="status" value="{{ request('status', '') }}">
                    <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-soft" fill="none" viewBox="0 0 16 16" stroke="currentColor" stroke-width="1.6"><circle cx="7" cy="7" r="5"/><path d="M11 11l3.5 3.5" stroke-linecap="round"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama perusahaan…" class="w-full rounded-full border border-line bg-white py-2.5 pl-10 pr-4 text-sm focus:border-coral-500 focus:outline-none focus:ring-2 focus:ring-coral-100">
                </form>
                <div class="inline-flex rounded-full border border-line bg-surface p-1">
                    @foreach (['' => 'Semua', 'active' => 'Aktif', 'suspended' => 'Ditangguhkan'] as $val => $label)
                        <a href="{{ request()->fullUrlWithQuery(['status' => $val, 'page' => null]) }}" class="rounded-full px-3.5 py-1.5 text-xs font-bold transition {{ request('status', '') === $val ? 'bg-white text-ink shadow-sm' : 'text-ink-soft hover:text-ink' }}">{{ $label }}</a>
                    @endforeach
                </div>
            </div>
            <x-admin.btn type="button" variant="primary" @click="openCreate()">+ Tambah Perusahaan</x-admin.btn>
        </div>

        <x-admin.card class="overflow-hidden !p-0">
            @if ($companies->isEmpty())
                <p class="px-6 py-12 text-center text-sm text-ink-soft">Belum ada perusahaan mitra.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[640px] border-collapse text-sm">
                        <thead>
                            <tr class="border-b border-line bg-surface">
                                <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-ink-soft">Nama</th>
                                <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-ink-soft">Industri</th>
                                <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-ink-soft">Kota</th>
                                <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-ink-soft">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($companies as $company)
                                @php
                                    $data = [
                                        'id' => $company->id,
                                        'name' => $company->name,
                                        'industry_id' => $company->industry_id,
                                        'city' => $company->city,
                                        'website' => $company->website,
                                        'address' => $company->address,
                                        'description' => $company->description,
                                        'pic_name' => $company->pic_name,
                                        'pic_position' => $company->pic_position,
                                        'pic_phone' => $company->pic_phone,
                                        'logo_url' => $company->logo_path ? \Illuminate\Support\Facades\Storage::url($company->logo_path) : null,
                                        'status' => $company->status->value,
                                        'action_url' => route('admin.companies.update', $company),
                                        'suspend_url' => route('admin.companies.suspend', $company),
                                        'activate_url' => route('admin.companies.activate', $company),
                                        'reset_url' => route('admin.companies.reset-password', $company),
                                    ];
                                @endphp
                                <tr class="cursor-pointer border-b border-line last:border-b-0 transition hover:bg-surface" @click="openEdit({{ \Illuminate\Support\Js::from($data) }})">
                                    <td class="px-4 py-3.5 font-semibold">{{ $company->name }}</td>
                                    <td class="px-4 py-3.5 text-ink-soft">{{ $company->industry?->name ?? '-' }}</td>
                                    <td class="px-4 py-3.5 text-ink-soft">{{ $company->city ?? '-' }}</td>
                                    <td class="px-4 py-3.5"><x-admin.pill :color="$company->status->value === 'active' ? 'good' : 'bad'">{{ $company->status->label() }}</x-admin.pill></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="flex flex-wrap items-center justify-between gap-3 border-t border-line px-5 py-3.5 text-xs text-ink-soft">
                    <span>Menampilkan {{ $companies->firstItem() }}–{{ $companies->lastItem() }} dari {{ $companies->total() }}</span>
                    {{ $companies->onEachSide(1)->links() }}
                </div>
            @endif
        </x-admin.card>

        <x-admin.modal show="open" title="Perusahaan" max-width="max-w-xl">
            <h2 class="-mt-1 mb-4 text-base font-bold" x-text="mode === 'create' ? 'Tambah Perusahaan Manual' : 'Ubah Data Perusahaan'"></h2>
            <form method="POST" :action="form.action_url" enctype="multipart/form-data" id="companyForm" class="space-y-4">
                @csrf
                <input type="hidden" name="_method" :value="mode === 'edit' ? 'PUT' : 'POST'">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <x-admin.label value="Nama Perusahaan" />
                        <x-admin.input type="text" name="name" x-model="form.name" required />
                    </div>
                    <div>
                        <x-admin.label value="Industri" />
                        <select name="industry_id" x-model="form.industry_id" class="w-full rounded-xl border border-line bg-cream px-3 py-2.5 text-sm focus:border-coral-500 focus:outline-none focus:ring-2 focus:ring-coral-100">
                            <option value="">Pilih industri</option>
                            @foreach ($industries as $industry)
                                <option value="{{ $industry->id }}">{{ $industry->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-admin.label value="Kota" />
                        <x-admin.input type="text" name="city" x-model="form.city" />
                    </div>
                    <div>
                        <x-admin.label value="Website" />
                        <x-admin.input type="url" name="website" x-model="form.website" />
                    </div>
                    <div class="sm:col-span-2">
                        <x-admin.label value="Alamat" />
                        <x-admin.input type="text" name="address" x-model="form.address" />
                    </div>
                    <div>
                        <x-admin.label value="Nama PIC" />
                        <x-admin.input type="text" name="pic_name" x-model="form.pic_name" required />
                    </div>
                    <div>
                        <x-admin.label value="Jabatan PIC" />
                        <x-admin.input type="text" name="pic_position" x-model="form.pic_position" required />
                    </div>
                    <div>
                        <x-admin.label value="Telepon PIC" />
                        <x-admin.input type="text" name="pic_phone" x-model="form.pic_phone" required />
                    </div>
                    <div x-show="mode === 'create'">
                        <x-admin.label value="Email Login PIC" />
                        <x-admin.input type="email" name="email" x-model="form.email" />
                        <p class="mt-1 text-xs text-ink-soft">Email undangan atur password dikirim ke alamat ini.</p>
                    </div>
                </div>
                <div>
                    <x-admin.label value="Deskripsi" />
                    <textarea name="description" x-model="form.description" rows="3" class="w-full rounded-xl border border-line bg-cream px-3 py-2.5 text-sm focus:border-coral-500 focus:outline-none focus:ring-2 focus:ring-coral-100"></textarea>
                </div>
                <div>
                    <x-admin.label value="Logo" />
                    <img :src="form.logo_url" x-show="form.logo_url" class="mb-2 h-16 w-16 rounded-xl border border-line object-cover">
                    <input name="logo" type="file" accept="image/*" class="block text-sm">
                </div>
            </form>

            <x-slot:footer>
                <template x-if="mode === 'edit'">
                    <div class="mr-auto flex gap-2">
                        <x-admin.btn type="button" variant="ghost" @click="postTo(form.reset_url)">Reset Password</x-admin.btn>
                        <template x-if="form.status === 'active'">
                            <x-admin.btn type="button" variant="ghost" class="!text-bad" @click="confirm('Tangguhkan perusahaan ini?') && postTo(form.suspend_url)">Tangguhkan</x-admin.btn>
                        </template>
                        <template x-if="form.status === 'suspended'">
                            <x-admin.btn type="button" variant="ghost" class="!text-good" @click="postTo(form.activate_url)">Aktifkan</x-admin.btn>
                        </template>
                    </div>
                </template>
                <x-admin.btn type="submit" form="companyForm" variant="primary" x-text="mode === 'create' ? 'Buat Perusahaan' : 'Simpan Perubahan'"></x-admin.btn>
            </x-slot:footer>
        </x-admin.modal>
    </div>
</x-admin-layout>
