@php
    $pending = $skills->where('is_approved', false);
    $approved = $skills->where('is_approved', true);
    $grouped = $approved->groupBy(fn ($skill) => $skill->category ?: 'Lainnya')->sortKeys();
@endphp

<x-admin-layout title="Skill">
    <div x-data="{
        open: false,
        active: { id: null, name: '', category: '', action_url: '' },
        openEdit(data) { this.active = data; this.open = true; }
    }">
        <div class="mb-5 flex items-center justify-between">
            <p class="text-sm text-ink-soft">Kelola daftar skill yang tersedia untuk profil mahasiswa.</p>
        </div>

        <div class="mb-5 grid grid-cols-2 gap-3.5 lg:grid-cols-3">
            <x-admin.stat-card label="Skill Disetujui" :value="$approved->count()" />
            <x-admin.stat-card label="Menunggu Persetujuan" :value="$pending->count()" :trend="$pending->count() > 0 ? 'perlu ditinjau' : null" trend-color="warn" />
        </div>

        <x-admin.card title="Tambah Skill" class="mb-5">
            <form method="POST" action="{{ route('admin.skills.store') }}" class="flex flex-wrap items-end gap-3">
                @csrf
                <div class="min-w-[200px] flex-1">
                    <x-admin.label value="Nama Skill" />
                    <x-admin.input type="text" name="name" required />
                </div>
                <div>
                    <x-admin.label value="Kategori" />
                    <x-admin.input type="text" name="category" placeholder="IT, Bisnis, Soft Skill" />
                </div>
                <x-admin.btn type="submit" variant="primary">Tambah</x-admin.btn>
            </form>
        </x-admin.card>

        @if ($pending->isNotEmpty())
            <x-admin.card title="Menunggu Persetujuan" class="mb-5">
                <ul class="divide-y divide-line">
                    @foreach ($pending as $skill)
                        <li class="flex items-center justify-between py-2.5">
                            <span class="text-sm font-semibold">{{ $skill->name }}</span>
                            <div class="flex items-center gap-3">
                                <form method="POST" action="{{ route('admin.skills.approve', $skill) }}">
                                    @csrf
                                    <button type="submit" class="text-xs font-bold text-good hover:underline">Setujui</button>
                                </form>
                                <form method="POST" action="{{ route('admin.skills.destroy', $skill) }}" onsubmit="return confirm('Hapus skill ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs font-bold text-bad hover:underline">Hapus</button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </x-admin.card>
        @endif

        <x-admin.card title="Skill Disetujui">
            <div class="space-y-5">
                @foreach ($grouped as $category => $items)
                    <div>
                        <p class="mb-2.5 text-[11px] font-bold uppercase tracking-wider text-ink-soft">{{ $category }}</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($items as $skill)
                                @php $data = ['id' => $skill->id, 'name' => $skill->name, 'category' => $skill->category, 'action_url' => route('admin.skills.update', $skill), 'delete_url' => route('admin.skills.destroy', $skill)]; @endphp
                                <button type="button" class="cursor-pointer rounded-full border border-line bg-surface px-3.5 py-1.5 text-sm font-semibold transition hover:-translate-y-px hover:border-coral-300 hover:bg-coral-100 hover:shadow-sm" @click="openEdit({{ \Illuminate\Support\Js::from($data) }})">{{ $skill->name }}</button>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </x-admin.card>

        <x-admin.modal show="open" title="Ubah Skill" max-width="max-w-sm">
            <form method="POST" :action="active.action_url" id="skillForm" class="space-y-4">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <div>
                    <x-admin.label value="Nama" />
                    <x-admin.input type="text" name="name" x-model="active.name" required />
                </div>
                <div>
                    <x-admin.label value="Kategori" />
                    <x-admin.input type="text" name="category" x-model="active.category" />
                </div>
            </form>
            <x-slot:footer>
                <form :action="active.delete_url" method="POST" @submit="if (!confirm('Hapus skill ini?')) $event.preventDefault()" class="mr-auto">
                    @csrf @method('DELETE')
                    <x-admin.btn type="submit" variant="ghost" class="!text-bad">Hapus</x-admin.btn>
                </form>
                <x-admin.btn type="submit" form="skillForm" variant="primary">Simpan</x-admin.btn>
            </x-slot:footer>
        </x-admin.modal>
    </div>
</x-admin-layout>
