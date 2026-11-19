<x-admin-layout title="Industri">
    <div x-data="{
        open: false,
        active: { id: null, name: '', action_url: '', delete_url: '' },
        openEdit(data) { this.active = data; this.open = true; }
    }">
        <div class="mb-5 grid grid-cols-2 gap-3.5 lg:grid-cols-3">
            <x-admin.stat-card label="Total Industri" :value="$industries->count()" />
            <x-admin.stat-card label="Belum Ada Perusahaan" :value="$industries->where('companies_count', 0)->count()" />
        </div>

        <x-admin.card title="Tambah Industri" class="mb-5">
            <form method="POST" action="{{ route('admin.industries.store') }}" class="flex flex-wrap items-end gap-3">
                @csrf
                <div class="min-w-[200px] flex-1">
                    <x-admin.label value="Nama Industri" />
                    <x-admin.input type="text" name="name" required />
                </div>
                <x-admin.btn type="submit" variant="primary">Tambah</x-admin.btn>
            </form>
        </x-admin.card>

        <p class="mb-3 text-xs font-bold uppercase tracking-wider text-ink-soft">Daftar Industri</p>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($industries as $industry)
                @php $data = ['id' => $industry->id, 'name' => $industry->name, 'action_url' => route('admin.industries.update', $industry), 'delete_url' => route('admin.industries.destroy', $industry)]; @endphp
                <x-admin.card class="cursor-pointer !p-4 transition hover:-translate-y-px hover:shadow-md" @click="openEdit({{ \Illuminate\Support\Js::from($data) }})">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-sm font-semibold">{{ $industry->name }}</span>
                        <x-admin.pill :color="$industry->companies_count > 0 ? 'good' : 'slate'">{{ $industry->companies_count }} perusahaan</x-admin.pill>
                    </div>
                </x-admin.card>
            @endforeach
        </div>

        <x-admin.modal show="open" title="Ubah Industri" max-width="max-w-sm">
            <form method="POST" :action="active.action_url" id="industryForm">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <x-admin.label value="Nama" />
                <x-admin.input type="text" name="name" x-model="active.name" required />
            </form>
            <x-slot:footer>
                <form :action="active.delete_url" method="POST" @submit="if (!confirm('Hapus industri ini?')) $event.preventDefault()" class="mr-auto">
                    @csrf @method('DELETE')
                    <x-admin.btn type="submit" variant="ghost" class="!text-bad">Hapus</x-admin.btn>
                </form>
                <x-admin.btn type="submit" form="industryForm" variant="primary">Simpan</x-admin.btn>
            </x-slot:footer>
        </x-admin.modal>
    </div>
</x-admin-layout>
