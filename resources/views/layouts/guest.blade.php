<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PSU Career') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('images/branding/logo.png') }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <style>body, h1, h2, h3, h4, h5, h6 { font-family: 'Plus Jakarta Sans', sans-serif; }</style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-50 antialiased">
        <div class="relative flex min-h-screen flex-col items-center justify-center overflow-hidden px-4 py-10">
            <div class="pointer-events-none absolute -left-32 -top-32 h-80 w-80 rounded-full bg-[#2457C5]/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -right-24 bottom-0 h-80 w-80 rounded-full bg-cyan-500/10 blur-3xl"></div>

            <a href="{{ route('landing.home') }}" class="relative z-10 mb-6 flex items-center gap-3 text-slate-800">
                <img src="{{ asset('images/branding/logo.png') }}" alt="Logo PSU Bekasi" class="h-12 w-12 object-contain">
                <span class="leading-tight">
                    <span class="block text-lg font-bold">PSU Career</span>
                    <span class="block text-xs text-slate-500">Universitas Panca Sakti Bekasi</span>
                </span>
            </a>

            <div class="relative z-10 w-full sm:max-w-md">
                <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-xl">
                    {{ $slot }}
                </div>
                <p class="mt-6 text-center text-sm text-slate-500">
                    <a href="{{ route('landing.home') }}" class="hover:text-[#2457C5]">&larr; Kembali ke beranda</a>
                </p>
            </div>
        </div>
    </body>
</html>
