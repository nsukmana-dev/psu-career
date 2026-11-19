<x-admin-layout title="FAQ">
    <x-admin.card title="Tambah FAQ" class="mb-5">
        <form method="POST" action="{{ route('admin.faqs.store') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <x-admin.label value="Audiens" />
                    <select name="audience" required class="w-full rounded-xl border border-line bg-cream px-3 py-2.5 text-sm focus:border-coral-500 focus:outline-none focus:ring-2 focus:ring-coral-100">
                        @foreach (\App\Enums\FaqAudience::cases() as $audience)
                            <option value="{{ $audience->value }}">{{ $audience === \App\Enums\FaqAudience::Student ? 'Mahasiswa' : 'Perusahaan' }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('audience')" class="mt-1" />
                </div>
                <div>
                    <x-admin.label value="Urutan" />
                    <x-admin.input name="sort_order" type="number" value="0" />
                    <x-input-error :messages="$errors->get('sort_order')" class="mt-1" />
                </div>
                <div class="sm:col-span-2">
                    <x-admin.label value="Pertanyaan" />
                    <x-admin.input name="question" type="text" required />
                    <x-input-error :messages="$errors->get('question')" class="mt-1" />
                </div>
                <div class="sm:col-span-2">
                    <x-admin.label value="Jawaban" />
                    <textarea name="answer" rows="3" required class="w-full rounded-xl border border-line bg-cream px-3 py-2.5 text-sm focus:border-coral-500 focus:outline-none focus:ring-2 focus:ring-coral-100"></textarea>
                    <x-input-error :messages="$errors->get('answer')" class="mt-1" />
                </div>
            </div>
            <x-admin.btn type="submit" variant="primary">Tambah FAQ</x-admin.btn>
        </form>
    </x-admin.card>

    @foreach (\App\Enums\FaqAudience::cases() as $audience)
        <x-admin.card :title="($audience === \App\Enums\FaqAudience::Student ? 'FAQ Mahasiswa' : 'FAQ Perusahaan')" class="mb-5">
            @php $items = $faqs->get($audience->value, collect()); @endphp
            @if ($items->isEmpty())
                <p class="text-sm text-ink-soft">Belum ada FAQ.</p>
            @else
                <ul class="divide-y divide-line">
                    @foreach ($items as $faq)
                        <li class="py-3.5" x-data="{ editing: false }">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold">{{ $faq->question }}</p>
                                    <p class="mt-1 text-sm text-ink-soft">{{ $faq->answer }}</p>
                                    <x-admin.pill :color="$faq->is_active ? 'good' : 'slate'" class="mt-2">
                                        {{ $faq->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </x-admin.pill>
                                </div>
                                <div class="flex shrink-0 items-center gap-3 text-xs font-bold">
                                    <button type="button" class="text-coral-700 hover:underline" @click="editing = ! editing">Ubah</button>
                                    <form method="POST" action="{{ route('admin.faqs.toggle', $faq) }}">
                                        @csrf
                                        <button type="submit" class="text-ink-soft hover:underline">{{ $faq->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}" onsubmit="return confirm('Hapus FAQ ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-bad hover:underline">Hapus</button>
                                    </form>
                                </div>
                            </div>
                            <form x-show="editing" x-cloak method="POST" action="{{ route('admin.faqs.update', $faq) }}" class="mt-3 space-y-3 rounded-2xl border border-line p-4">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="audience" value="{{ $faq->audience->value }}">
                                <div>
                                    <x-admin.label value="Pertanyaan" />
                                    <x-admin.input name="question" type="text" :value="$faq->question" required />
                                </div>
                                <div>
                                    <x-admin.label value="Jawaban" />
                                    <textarea name="answer" rows="3" required class="w-full rounded-xl border border-line bg-cream px-3 py-2.5 text-sm focus:border-coral-500 focus:outline-none focus:ring-2 focus:ring-coral-100">{{ $faq->answer }}</textarea>
                                </div>
                                <div class="w-32">
                                    <x-admin.label value="Urutan" />
                                    <x-admin.input name="sort_order" type="number" :value="$faq->sort_order" />
                                </div>
                                <x-admin.btn type="submit" variant="primary">Simpan</x-admin.btn>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-admin.card>
    @endforeach
</x-admin-layout>
