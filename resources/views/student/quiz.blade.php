<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <div class="flex items-center gap-2 text-sm text-gray-400 mb-1">
                    <span>{{ $quiz->sousChapitre->chapitre->formation->nom }}</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span>{{ $quiz->sousChapitre->titre }}</span>
                </div>
                <h2 class="font-bold text-xl text-gray-900">{{ $quiz->titre }}</h2>
            </div>
            <a href="{{ route('student.sous-chapitre', $quiz->sousChapitre) }}" class="inline-flex items-center gap-1.5 text-gray-500 hover:text-indigo-600 text-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Retour
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if($existingResult)
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 rounded-2xl p-6 mb-8">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br {{ $existingResult->score_sur_20 >= 10 ? 'from-emerald-500 to-emerald-600' : 'from-red-500 to-red-600' }} flex items-center justify-center">
                            <span class="text-2xl font-bold text-white">{{ $existingResult->score_sur_20 }}</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Vous avez d&eacute;j&agrave; pass&eacute; ce quiz</h3>
                            <p class="text-gray-500 text-sm">Score : {{ $existingResult->score }}/{{ $existingResult->total_questions }} &middot; Vous pouvez repasser le quiz pour am&eacute;liorer votre note.</p>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('student.quiz.submit', $quiz) }}" method="POST" x-data="{ current: 0, total: {{ $quiz->questions->count() }} }">
                @csrf

                <!-- Progress bar -->
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-6">
                    <div class="flex items-center justify-between text-sm text-gray-500 mb-2">
                        <span>Progression</span>
                        <span>{{ $quiz->questions->count() }} question(s)</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-2 rounded-full transition-all duration-500" :style="'width: ' + (current / total * 100) + '%'"></div>
                    </div>
                </div>

                @foreach($quiz->questions as $i => $question)
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-4">
                        <p class="font-semibold text-lg text-gray-900 mb-4">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 text-sm font-bold mr-2">{{ $i + 1 }}</span>
                            {{ $question->texte }}
                        </p>
                        <div class="space-y-2">
                            @foreach($question->reponses as $reponse)
                                <label class="flex items-center gap-3 p-3 rounded-xl border-2 border-gray-100 hover:border-indigo-200 hover:bg-indigo-50/50 cursor-pointer transition-all group">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $reponse->id }}" required
                                        class="text-indigo-600 focus:ring-indigo-500 w-4 h-4"
                                        @change="current = document.querySelectorAll('input[type=radio]:checked').length">
                                    <span class="text-gray-700 group-hover:text-gray-900 transition">{{ $reponse->texte }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <div class="flex justify-center mt-8">
                    <button type="submit" class="px-10 py-3.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:shadow-lg hover:shadow-indigo-200 transition-all duration-300 font-semibold text-lg">
                        Soumettre mes r&eacute;ponses
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>