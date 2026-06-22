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
    <body class="font-sans antialiased" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen lg:flex">
            @include('layouts.navigation')

            <div class="flex min-h-screen flex-1 flex-col">
                <header class="flex h-14 shrink-0 items-center gap-3 border-b border-stone-200 bg-white px-4 lg:hidden">
                    <button
                        type="button"
                        @click="sidebarOpen = true"
                        class="inline-flex items-center justify-center rounded p-2 text-stone-600 hover:bg-stone-100"
                        aria-label="{{ __('Open menu') }}"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <span class="font-semibold tracking-tight text-stone-900">{{ config('app.name', 'Issue Tracker') }}</span>
                </header>

                <main class="flex-1 px-4 py-8 sm:px-8">
                    <div class="mx-auto max-w-3xl">
                        @isset($header)
                            <header class="mb-10 border-b border-stone-200 pb-6">
                                {{ $header }}
                            </header>
                        @endisset

                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
