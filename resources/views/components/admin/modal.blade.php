@props(['show', 'title', 'maxWidth' => 'max-w-lg'])

<div x-show="{{ $show }}" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-5" style="display: none;">
    <div class="absolute inset-0 bg-ink/50 backdrop-blur-sm" @click="{{ $show }} = false"></div>
    <div
        class="relative flex max-h-[86vh] w-full {{ $maxWidth }} flex-col overflow-hidden rounded-3xl bg-white shadow-2xl"
        x-show="{{ $show }}"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
    >
        <div class="flex flex-shrink-0 items-center justify-between rounded-t-3xl border-b border-line bg-surface px-6 py-5">
            <h2 class="text-lg font-bold text-ink">{{ $title }}</h2>
            <button type="button" @click="{{ $show }} = false" class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full border border-line bg-white text-ink-soft hover:bg-line" aria-label="Tutup">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 16 16" stroke="currentColor" stroke-width="1.8"><path d="M4 4l8 8M12 4l-8 8" stroke-linecap="round"/></svg>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto px-6 py-5">
            {{ $slot }}
        </div>
        @isset($footer)
            <div class="flex flex-shrink-0 flex-wrap justify-end gap-2 border-t border-line px-6 py-4">
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>
