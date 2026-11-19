@props(['title' => null])

<div {{ $attributes->merge(['class' => 'rounded-3xl border border-line bg-white p-6 shadow-sm']) }}>
    @if ($title)
        <h2 class="mb-4 text-base font-bold text-ink">{{ $title }}</h2>
    @endif
    {{ $slot }}
</div>
