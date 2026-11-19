@props(['theme' => 'home', 'title' => 'PSU Career'])

@php
    $theme = match ($theme) {
        'student' => [
            'font' => 'Plus Jakarta Sans',
            'bodyBg' => 'bg-white',
            'bodyText' => 'text-slate-800',
            'navBg' => 'bg-[#12325B]',
            'navText' => 'text-white',
        ],
        'company' => [
            'font' => 'Manrope',
            'bodyBg' => 'bg-white',
            'bodyText' => 'text-slate-800',
            'navBg' => 'bg-[#071536]',
            'navText' => 'text-white',
        ],
        default => [
            'font' => 'Plus Jakarta Sans',
            'bodyBg' => 'bg-white',
            'bodyText' => 'text-slate-800',
            'navBg' => 'bg-[#0F2A4A]',
            'navText' => 'text-white',
        ],
    };

    $fontUrl = 'https://fonts.googleapis.com/css2?family='.str_replace(' ', '+', $theme['font']).':wght@400;500;600;700;800&display=swap';
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/branding/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="{{ $fontUrl }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body, h1, h2, h3, h4, h5, h6 { font-family: '{{ $theme['font'] }}', sans-serif; }</style>
</head>
<body class="{{ $theme['bodyBg'] }} {{ $theme['bodyText'] }} antialiased">
    <nav class="{{ $theme['navBg'] }} {{ $theme['navText'] }}">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            {{ $navbar }}
        </div>
    </nav>

    {{ $slot }}
</body>
</html>
