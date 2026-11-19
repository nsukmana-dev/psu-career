@props(['variant' => 'primary', 'as' => 'button', 'type' => 'button'])

@php
    $variants = [
        'primary' => 'bg-ink text-cream hover:shadow-md hover:-translate-y-px',
        'coral' => 'bg-coral-500 text-[#2A140C] hover:shadow-md hover:-translate-y-px',
        'ghost' => 'bg-white border border-line text-ink shadow-sm hover:shadow-md hover:-translate-y-px',
        'danger' => 'bg-bad text-white hover:shadow-md hover:-translate-y-px',
    ];
    $classes = 'inline-flex items-center justify-center gap-1.5 rounded-full px-4 py-2.5 text-sm font-bold transition whitespace-nowrap '.($variants[$variant] ?? $variants['primary']);
@endphp

@if ($as === 'a')
    <a {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
