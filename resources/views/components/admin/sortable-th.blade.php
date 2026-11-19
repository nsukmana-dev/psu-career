@props(['field', 'label'])

@php
    $currentSort = request('sort');
    $currentDir = request('dir', 'asc');
    $isActive = $currentSort === $field;
    $nextDir = $isActive && $currentDir === 'asc' ? 'desc' : 'asc';
    $url = request()->fullUrlWithQuery(['sort' => $field, 'dir' => $nextDir]);
@endphp

<th class="whitespace-nowrap px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-ink-soft">
    <a href="{{ $url }}" class="inline-flex items-center gap-1 hover:text-ink">
        {{ $label }}
        <svg class="h-2.5 w-2.5 transition {{ $isActive ? 'opacity-100' : 'opacity-30' }} {{ $isActive && $currentDir === 'desc' ? 'rotate-180' : '' }}" viewBox="0 0 10 6" fill="none"><path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </a>
</th>
