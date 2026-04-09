<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-xl text-gray-900">{{ $formation->nom }}</h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ $formation->niveau }}  {{ $formation->duree }}</p>
            </div>
            <a href="{{ route('student.dashboard') }}" class="inline-flex items-center gap-1.5 text-gray-500 hover:text-indigo-600 text-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Retour
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8">
                <p class="text-gray-600 leading-relaxed">{{ $formation->description }}</p>
            </div>

            <div class="space-y-4">
                @foreach($formation->chapitres as $chapitre)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 bg-gradient-to-r from-indigo-50/50 to-transparent border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 text-sm font-bold">{{ $loop->iteration }}</div>
                                {{ $chapitre->titre }}
                            </h3>
                            @if($chapitre->description)
                                <p class="text-gray-500 text-sm mt-1 ml-10">{{ $chapitre->description }}</p>
                            @endif
                        </div>
                        <div class="divide-y divide-gray-50">
                            @foreach($chapitre->sousChapitres as $sc)
                                <a href="{{ route('student.sous-chapitre', $sc) }}" class="flex items-center justify-between px-6 py-4 hover:bg-indigo-50/50 transition group">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span class="text-gray-700 group-hover:text-indigo-600 transition font-medium">{{ $sc->titre }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if($sc->quizzes->where('published', true)->count())
                                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium bg-emerald-50 text-emerald-700 rounded-lg border border-emerald-100">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                                Quiz
                                            </span>
                                        @endif
                                        <svg class="w-4 h-4 text-gray-300 group-hover:text-indigo-400 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>