@props(['value' => null])

<label {{ $attributes->merge(['class' => 'mb-1.5 block text-xs font-bold text-ink']) }}>
    {{ $value ?? $slot }}
</label>
