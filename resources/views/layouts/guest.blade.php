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
            .animate-float { animation: float 6s ease-in-out infinite; }
            .animate-float-delayed { animation: float 6s ease-in-out 2s infinite; }
            @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-20px); } }
        </style>
    </head>
    <body class="antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center relative overflow-hidden bg-gradient-to-br from-slate-50 via-white to-indigo-50/30">
            {{-- Animated blobs --}}
            <div class="absolute top-10 left-10 w-72 h-72 bg-purple-200 opacity-30 rounded-full mix-blend-multiply filter blur-xl animate-float"></div>
            <div class="absolute top-40 right-10 w-72 h-72 bg-indigo-200 opacity-30 rounded-full mix-blend-multiply filter blur-xl animate-float-delayed"></div>
            <div class="absolute -bottom-8 left-1/2 w-72 h-72 bg-cyan-200 opacity-30 rounded-full mix-blend-multiply filter blur-xl animate-float"></div>

            <div class="relative z-10 w-full flex flex-col items-center px-4 py-12">
                {{-- Logo --}}
                <a href="/" class="flex items-center gap-2 mb-8">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <span class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">LMS Quiz</span>
                </a>

                {{-- Card --}}
                <div class="w-full sm:max-w-md bg-white/80 backdrop-blur-md shadow-xl border border-gray-100 rounded-2xl px-8 py-8">
                    {{ $slot }}
                </div>

                {{-- Footer --}}
                <p class="mt-8 text-xs text-gray-400"> {{ date('Y') }} LMS Quiz  Fait par Julien YILDIZ.</p>
            </div>
        </div>
    </body>
</html>
