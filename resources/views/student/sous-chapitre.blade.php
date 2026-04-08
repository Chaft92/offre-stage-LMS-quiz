<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <div class="flex items-center gap-2 text-sm text-gray-400 mb-1">
                    <a href="{{ route('student.formation', $sousChapitre->chapitre->formation) }}" class="hover:text-indigo-600 transition">{{ $sousChapitre->chapitre->formation->nom }}</a>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span>{{ $sousChapitre->chapitre->titre }}</span>
                </div>
                <h2 class="font-bold text-xl text-gray-900">{{ $sousChapitre->titre }}</h2>
            </div>
            <a href="{{ route('student.formation', $sousChapitre->chapitre->formation) }}" class="inline-flex items-center gap-1.5 text-gray-500 hover:text-indigo-600 text-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Retour
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 mb-6 prose max-w-none prose-indigo">
                {!! $sousChapitre->contenu !!}
            </div>

            @foreach($sousChapitre->quizzes->where('published', true) as $quiz)
                <a href="{{ route('student.quiz', $quiz) }}" class="group block bg-gradient-to-r from-emerald-50 to-cyan-50 rounded-2xl p-6 shadow-sm border border-emerald-100 hover:shadow-md hover:border-emerald-200 transition-all mb-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-cyan-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $quiz->titre }}</h3>
                                <p class="text-gray-500 text-sm">Cliquez pour passer le quiz</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-emerald-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</x-app-layout>