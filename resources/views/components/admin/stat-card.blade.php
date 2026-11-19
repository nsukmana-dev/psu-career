@props(['label', 'value', 'trend' => null, 'trendColor' => 'good', 'series' => null])

@php
    $sparkline = null;
    if ($series && count($series) > 1) {
        $w = 78; $h = 30; $pad = 3;
        $min = min($series); $max = max($series);
        $range = ($max - $min) ?: 1;
        $points = [];
        foreach (array_values($series) as $i => $v) {
            $x = $pad + ($i * ($w - $pad * 2)) / (count($series) - 1);
            $y = $pad + ($h - $pad * 2) * (1 - ($v - $min) / $range);
            $points[] = [$x, $y];
        }
        $line = '';
        foreach ($points as $i => $p) {
            $line .= ($i === 0 ? 'M' : 'L').number_format($p[0], 1).','.number_format($p[1], 1).' ';
        }
        $last = end($points);
        $first = $points[0];
        $fill = $line.'L'.number_format($last[0], 1).','.($h - $pad).' L'.number_format($first[0], 1).','.($h - $pad).' Z';
        $sparkline = ['line' => trim($line), 'fill' => $fill, 'last' => $last];
    }
    $color = $trendColor === 'warn' ? '#C98A2E' : '#5E8C6A';
@endphp

<div class="rounded-2xl border border-line bg-surface p-5 shadow-sm">
    <p class="mb-2 text-xs font-bold text-ink-soft">{{ $label }}</p>
    <div class="flex items-end justify-between gap-2">
        <p class="font-display text-[28px] font-extrabold leading-none tabular-nums">{{ $value }}</p>
        @if ($sparkline)
            <svg class="h-[30px] w-[78px] flex-shrink-0" viewBox="0 0 78 30">
                <path d="{{ $sparkline['fill'] }}" fill="{{ $color }}" opacity="0.14"></path>
                <path d="{{ $sparkline['line'] }}" fill="none" stroke="{{ $color }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                <circle cx="{{ number_format($sparkline['last'][0], 1) }}" cy="{{ number_format($sparkline['last'][1], 1) }}" r="2.6" fill="{{ $color }}"></circle>
            </svg>
        @endif
    </div>
    @if ($trend)
        <p class="mt-2 text-xs font-bold {{ $trendColor === 'warn' ? 'text-warn' : 'text-good' }}">{{ $trend }}</p>
    @endif
</div>
