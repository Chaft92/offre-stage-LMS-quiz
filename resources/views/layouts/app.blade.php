<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'LMS Quiz') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }
            .animate-fade-in { animation: fadeIn 0.5s ease-out; }
            @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
        </style>
    </head>
    <body class="antialiased">
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-indigo-50/30">
            @include('layouts.navigation')

            @isset($header)
                <header class="bg-white/70 backdrop-blur-md border-b border-gray-100">
                    <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="animate-fade-in">
                {{ $slot }}
            </main>

            <footer class="mt-12 bg-gray-50 border-t border-gray-200 py-8 text-center">
                <p class="text-sm font-semibold text-indigo-600 mb-1">LMS Quiz</p>
                <p class="text-xs text-gray-400">Mini LMS Pédagogique  {{ date('Y') }}  Fait par Julien YILDIZ.</p>
            </footer>
        </div>
    </body>
</html>
