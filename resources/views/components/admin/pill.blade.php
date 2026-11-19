@props(['color' => 'slate', 'pulse' => false])

@php
    $colors = [
        'slate' => 'bg-surface text-ink-soft',
        'good' => 'bg-good-soft text-good',
        'warn' => 'bg-warn-soft text-warn',
        'bad' => 'bg-bad-soft text-bad',
    ];
    $dots = [
        'slate' => 'bg-ink-soft',
        'good' => 'bg-good',
        'warn' => 'bg-warn',
        'bad' => 'bg-bad',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-bold '.($colors[$color] ?? $colors['slate'])]) }}>
    <span class="h-1.5 w-1.5 flex-shrink-0 rounded-full {{ $dots[$color] ?? $dots['slate'] }} {{ $pulse ? 'animate-pulse' : '' }}"></span>
    {{ $slot }}
</span>
