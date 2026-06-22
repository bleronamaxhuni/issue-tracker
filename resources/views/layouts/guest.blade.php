<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Issue Tracker') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=ibm-plex-sans:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="flex min-h-screen flex-col items-center justify-center px-4 py-12">
            <a href="/" class="mb-8 text-sm font-medium tracking-tight text-stone-900">
                {{ config('app.name', 'Issue Tracker') }}
            </a>

            <div class="w-full max-w-sm border border-stone-200 p-6 sm:p-8">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
