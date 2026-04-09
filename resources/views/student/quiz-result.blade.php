<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-900">Résultat : {{ $quiz->titre }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl p-10 shadow-sm border border-gray-100 text-center mb-8">
                <!-- Score circle -->
                <div class="relative w-40 h-40 mx-auto mb-6">
                    <svg class="w-40 h-40 transform -rotate-90" viewBox="0 0 120 120">
                        <circle cx="60" cy="60" r="54" fill="none" stroke="#f1f5f9" stroke-width="10"/>
                        <circle cx="60" cy="60" r="54" fill="none" stroke="{{ $result->score_sur_20 >= 10 ? '#10b981' : '#ef4444' }}" stroke-width="10" stroke-linecap="round" stroke-dasharray="{{ 339.292 * ($result->score_sur_20 / 20) }} 339.292" class="transition-all duration-1000"/>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-4xl font-bold {{ $result->score_sur_20 >= 10 ? 'text-emerald-600' : 'text-red-600' }}">{{ $result->score_sur_20 }}</span>
                        <span class="text-gray-400 text-sm">/20</span>
                    </div>
                </div>

                <p class="text-gray-500 mb-2">
                    {{ $result->score }}/{{ $result->total_questions }} bonne(s) réponse(s)
                </p>

                @if($result->score_sur_20 >= 10)
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-700 rounded-xl font-semibold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Bravo, vous avez réussi !
                    </div>
                @else
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-amber-50 text-amber-700 rounded-xl font-semibold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Continuez vos efforts !
                    </div>
                @endif
            </div>

            <div class="flex flex-wrap justify-center gap-3">
                <a href="{{ route('student.quiz', $quiz) }}" class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:shadow-lg transition-all font-medium">Repasser le quiz</a>
                <a href="{{ route('student.notes') }}" class="px-6 py-2.5 bg-white text-gray-700 border border-gray-200 rounded-xl hover:bg-gray-50 transition-all font-medium">Voir mes notes</a>
                <a href="{{ route('student.sous-chapitre', $quiz->sousChapitre) }}" class="px-6 py-2.5 bg-white text-gray-700 border border-gray-200 rounded-xl hover:bg-gray-50 transition-all font-medium">Retour au cours</a>
            </div>
        </div>
    </div>
</x-app-layout>