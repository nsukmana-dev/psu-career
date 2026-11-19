@props(['name'])

@php
    $paths = [
        'home' => '<path d="M4 13h6V4H4v9zm0 7h6v-5H4v5zm10 0h6V11h-6v9zm0-16v5h6V4h-6z" stroke-linejoin="round"/>',
        'check' => '<circle cx="12" cy="12" r="8.5"/><path d="M8.5 12.2l2.3 2.3 4.7-4.8" stroke-linecap="round" stroke-linejoin="round"/>',
        'refresh' => '<path d="M4 12a8 8 0 0 1 14-5.2M20 12a8 8 0 0 1-14 5.2" stroke-linecap="round"/><path d="M18 4v4h-4M6 20v-4h4" stroke-linecap="round" stroke-linejoin="round"/>',
        'clipboard' => '<rect x="5.5" y="5" width="13" height="16" rx="2"/><path d="M9 5a1.5 1.5 0 0 1 1.5-1.5h3A1.5 1.5 0 0 1 15 5" stroke-linecap="round"/><path d="M9 12l2 2 4-4" stroke-linecap="round" stroke-linejoin="round"/>',
        'inbox' => '<path d="M4 6.5A2.5 2.5 0 0 1 6.5 4h11A2.5 2.5 0 0 1 20 6.5v7A2.5 2.5 0 0 1 17.5 16H9l-4 3.5V6.5Z" stroke-linejoin="round"/>',
        'users' => '<circle cx="9" cy="8" r="3.2"/><path d="M3.5 19c.8-3.2 3-4.8 5.5-4.8s4.7 1.6 5.5 4.8" stroke-linecap="round"/><circle cx="17" cy="8.5" r="2.4"/><path d="M15.8 14.6c2 .1 3.6 1.5 4.2 4" stroke-linecap="round"/>',
        'building' => '<rect x="4" y="9" width="16" height="11" rx="1.5"/><path d="M9 9V6a1.5 1.5 0 0 1 1.5-1.5h3A1.5 1.5 0 0 1 15 6v3"/>',
        'book' => '<path d="M4 5.5A1.5 1.5 0 0 1 5.5 4H12v16H5.5A1.5 1.5 0 0 1 4 18.5v-13Z"/><path d="M20 5.5A1.5 1.5 0 0 0 18.5 4H12v16h6.5a1.5 1.5 0 0 0 1.5-1.5v-13Z"/>',
        'tag' => '<path d="M11.5 4H6a2 2 0 0 0-2 2v5.5a2 2 0 0 0 .6 1.4l8.5 8.5a2 2 0 0 0 2.8 0l5-5a2 2 0 0 0 0-2.8l-8.5-8.5a2 2 0 0 0-1.4-.6Z" stroke-linejoin="round"/><circle cx="8" cy="9" r="1.3"/>',
        'briefcase' => '<rect x="3.5" y="7.5" width="17" height="11.5" rx="1.8"/><path d="M8.5 7.5V6a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v1.5"/><path d="M3.5 12.5h17"/>',
        'document' => '<path d="M7 3.5h7l4 4V19a1.5 1.5 0 0 1-1.5 1.5H7A1.5 1.5 0 0 1 5.5 19V5A1.5 1.5 0 0 1 7 3.5Z" stroke-linejoin="round"/><path d="M14 3.5V8h4.5" stroke-linejoin="round"/>',
        'globe' => '<circle cx="12" cy="12" r="8.5"/><path d="M3.5 12h17M12 3.5c2.2 2.3 3.4 5.2 3.4 8.5s-1.2 6.2-3.4 8.5c-2.2-2.3-3.4-5.2-3.4-8.5S9.8 5.8 12 3.5Z"/>',
        'help' => '<circle cx="12" cy="12" r="8.5"/><path d="M9.5 9.3a2.5 2.5 0 1 1 3.8 2.1c-.9.6-1.3 1-1.3 2" stroke-linecap="round"/><circle cx="12" cy="16.3" r="0.2" fill="currentColor"/>',
        'clock' => '<circle cx="12" cy="12" r="8.5"/><path d="M12 7.5V12l3 2" stroke-linecap="round" stroke-linejoin="round"/>',
    ];
@endphp

<svg {{ $attributes }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
    {!! $paths[$name] ?? $paths['home'] !!}
</svg>
