@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full rounded-xl border border-line bg-cream px-3 py-2.5 text-sm text-ink transition placeholder:text-ink-soft focus:border-coral-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-coral-100 disabled:opacity-60']) }}>
