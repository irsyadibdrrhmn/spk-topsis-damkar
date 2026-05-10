<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SPK TOPSIS Damkar') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div class="app-shell">
    @include('layouts.navigation')
    <main class="lg:pl-72">
        @isset($header)
            <header class="topbar">
                <div class="px-6 lg:px-10 py-5">{{ $header }}</div>
            </header>
        @endisset
        <section class="px-6 lg:px-10 py-8 fade-in">{{ $slot }}</section>
    </main>
</div>
</body>
</html>
