<x-admin-layout title="Template CV">
    <p class="mb-5 text-sm text-ink-soft">
        Menambah template baru (Blade view) perlu kode — lihat <code class="rounded bg-surface px-1.5 py-0.5">README.md</code> bagian "Menambah Template CV Baru". Di sini admin hanya mengatur preview, status aktif, dan urutan template yang sudah ada.
    </p>

    <div x-data="{ open: false, active: { id: null, name: '', sort_order: 0, preview_url: null, action_url: '' }, openEdit(data) { this.active = data; this.open = true; } }">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            @foreach ($templates as $template)
                @php $data = ['id' => $template->id, 'name' => $template->name, 'sort_order' => $template->sort_order, 'preview_url' => $template->preview_image ? \Illuminate\Support\Facades\Storage::url($template->preview_image) : null, 'is_active' => $template->is_active, 'action_url' => route('admin.cv-templates.update', $template), 'toggle_url' => route('admin.cv-templates.toggle-active', $template)]; @endphp
                <x-admin.card class="cursor-pointer transition hover:shadow-md" @click="openEdit({{ \Illuminate\Support\Js::from($data) }})">
                    <div class="flex items-center gap-3">
                        @if ($template->preview_image)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($template->preview_image) }}" class="h-20 w-16 rounded-xl border border-line object-cover">
                        @else
                            <div class="h-20 w-16 rounded-xl border border-dashed border-line"></div>
                        @endif
                        <div>
                            <p class="font-semibold">{{ $template->name }}</p>
                            <p class="text-xs text-ink-soft">{{ $template->slug }} · urutan {{ $template->sort_order }}</p>
                            <x-admin.pill :color="$template->is_active ? 'good' : 'slate'" class="mt-1.5">{{ $template->is_active ? 'Aktif' : 'Nonaktif' }}</x-admin.pill>
                        </div>
                    </div>
                </x-admin.card>
            @endforeach
        </div>

        <x-admin.modal show="open" title="Ubah Template CV" max-width="max-w-sm">
            <form method="POST" :action="active.action_url" enctype="multipart/form-data" id="templateForm" class="space-y-4">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <div>
                    <x-admin.label value="Nama" />
                    <x-admin.input type="text" name="name" x-model="active.name" required />
                </div>
                <div>
                    <x-admin.label value="Urutan" />
                    <x-admin.input type="number" name="sort_order" x-model="active.sort_order" required />
                </div>
                <div>
                    <x-admin.label value="Gambar Preview" />
                    <img :src="active.preview_url" x-show="active.preview_url" class="mb-2 h-20 w-16 rounded-xl border border-line object-cover">
                    <input name="preview_image" type="file" accept="image/*" class="block text-sm">
                </div>
            </form>
            <x-slot:footer>
                <x-admin.btn type="button" variant="ghost" class="mr-auto" x-bind:class="active.is_active ? '!text-bad' : '!text-good'" @click="postTo(active.toggle_url)" x-text="active.is_active ? 'Nonaktifkan' : 'Aktifkan'"></x-admin.btn>
                <x-admin.btn type="submit" form="templateForm" variant="primary">Simpan</x-admin.btn>
            </x-slot:footer>
        </x-admin.modal>
    </div>
</x-admin-layout>
