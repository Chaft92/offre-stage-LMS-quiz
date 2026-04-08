<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-xl text-gray-900">{{ $quiz->titre }}</h2>
                <p class="text-sm text-gray-500">{{ $quiz->sousChapitre->chapitre->formation->nom ?? '' }} &middot; {{ $quiz->sousChapitre->titre ?? '' }}</p>
            </div>
            <a href="{{ route('admin.quizzes.index') }}" class="inline-flex items-center gap-1.5 text-gray-500 hover:text-indigo-600 text-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Retour
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ session('success') }}
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
            @endif

            <!-- Quiz info -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
                <form action="{{ route('admin.quizzes.update', $quiz) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="titre" value="Titre" />
                            <x-text-input id="titre" name="titre" type="text" class="mt-1 block w-full" :value="$quiz->titre" required />
                        </div>
                        <div>
                            <x-input-label for="sous_chapitre_id" value="Sous-chapitre" />
                            <select name="sous_chapitre_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm">
                                @foreach($sousChapitres as $sc)
                                    <option value="{{ $sc->id }}" {{ $quiz->sous_chapitre_id == $sc->id ? 'selected' : '' }}>
                                        {{ $sc->chapitre->formation->nom }} &gt; {{ $sc->titre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-xl hover:shadow-lg transition-all font-medium text-sm">Sauvegarder</button>
                    </div>
                </form>
            </div>

            <!-- Questions -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Questions ({{ $quiz->questions->count() }})
                </h3>
                @forelse($quiz->questions as $i => $question)
                    <div class="py-4 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                        <div class="flex justify-between items-start gap-4">
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-md bg-indigo-50 text-indigo-600 text-xs font-bold mr-2">{{ $i + 1 }}</span>
                                    {{ $question->texte }}
                                </p>
                                <div class="ml-8 mt-2 space-y-1">
                                    @foreach($question->reponses as $reponse)
                                        <div class="flex items-center gap-2 text-sm {{ $reponse->est_correcte ? 'text-emerald-600 font-semibold' : 'text-gray-500' }}">
                                            @if($reponse->est_correcte)
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            @else
                                                <div class="w-4 h-4 rounded-full border-2 border-gray-300"></div>
                                            @endif
                                            {{ $reponse->texte }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" onsubmit="return confirm('Supprimer cette question ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400 text-center py-6">Aucune question. Ajoutez-en ci-dessous ou utilisez l'aide IA.</p>
                @endforelse
            </div>

            <!-- Add question manually -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ajouter une question</h3>
                <form action="{{ route('admin.quizzes.questions.store', $quiz) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <x-input-label for="texte" value="Question" />
                        <x-text-input id="texte" name="texte" type="text" class="mt-1 block w-full" required placeholder="Saisissez la question..." />
                        <x-input-error :messages="$errors->get('texte')" class="mt-2" />
                    </div>
                    <div class="space-y-2 mb-4">
                        @for($i = 0; $i < 4; $i++)
                            <div class="flex items-center gap-3">
                                <input type="radio" name="correcte" value="{{ $i }}" {{ $i == 0 ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500">
                                <input type="text" name="reponses[{{ $i }}][texte]" placeholder="R&eacute;ponse {{ $i + 1 }}" class="flex-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm text-sm" required>
                            </div>
                        @endfor
                    </div>
                    <p class="text-xs text-gray-400 mb-4">S&eacute;lectionnez le bouton radio de la bonne r&eacute;ponse.</p>
                    <div class="flex justify-end">
                        <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-xl hover:shadow-lg transition-all font-medium text-sm">Ajouter la question</button>
                    </div>
                </form>
            </div>

            <!-- AI Help -->
            <div class="bg-gradient-to-br from-purple-50 via-indigo-50 to-cyan-50 rounded-2xl p-6 shadow-sm border border-purple-100" x-data="aiHelper()">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Aide IA - G&eacute;n&eacute;rer des questions</h3>
                        <p class="text-sm text-gray-500">L'IA g&eacute;n&egrave;re des questions et r&eacute;ponses automatiquement.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <x-input-label value="Sujet / Th&egrave;me" />
                        <x-text-input x-model="sujet" type="text" class="mt-1 block w-full" placeholder="Ex: Les verbes irr&eacute;guliers en anglais" />
                    </div>
                    <div>
                        <x-input-label value="Nombre de questions" />
                        <x-text-input x-model="nombreQuestions" type="number" min="1" max="30" class="mt-1 block w-full" />
                    </div>
                </div>

                <button @click="generate()" :disabled="loading"
                    class="px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:shadow-lg hover:shadow-purple-200 transition-all duration-300 font-medium disabled:opacity-50 flex items-center gap-2">
                    <template x-if="!loading">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            G&eacute;n&eacute;rer avec l'IA
                        </span>
                    </template>
                    <template x-if="loading">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            G&eacute;n&eacute;ration en cours...
                        </span>
                    </template>
                </button>

                <div x-show="error" x-transition class="mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm" x-text="error"></div>

                <div x-show="questions.length > 0" class="mt-6 space-y-3">
                    <h4 class="font-semibold text-gray-900">Questions g&eacute;n&eacute;r&eacute;es :</h4>
                    <template x-for="(q, qi) in questions" :key="qi">
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                            <p class="font-medium text-gray-900" x-text="(qi+1) + '. ' + q.texte"></p>
                            <div class="ml-4 mt-2 space-y-1">
                                <template x-for="(r, ri) in q.reponses" :key="ri">
                                    <p class="text-sm flex items-center gap-2" :class="r.est_correcte ? 'text-emerald-600 font-semibold' : 'text-gray-500'">
                                        <span x-text="r.est_correcte ? '\u2713' : '\u25CB'"></span>
                                        <span x-text="r.texte"></span>
                                    </p>
                                </template>
                            </div>
                            <form :action="'{{ route('admin.quizzes.questions.store', $quiz) }}'" method="POST" class="mt-3">
                                @csrf
                                <input type="hidden" name="texte" :value="q.texte">
                                <template x-for="(r, ri) in q.reponses" :key="'r'+ri">
                                    <input type="hidden" :name="'reponses['+ri+'][texte]'" :value="r.texte">
                                </template>
                                <input type="hidden" name="correcte" :value="q.reponses.findIndex(r => r.est_correcte)">
                                <button type="submit" class="text-emerald-600 hover:text-emerald-700 text-sm font-medium flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Ajouter au quiz
                                </button>
                            </form>
                        </div>
                    </template>

                    <button @click="addAll()" class="w-full px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white rounded-xl hover:shadow-lg transition-all font-medium flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Ajouter toutes les questions au quiz
                    </button>
                </div>
            </div>

            <script>
                function aiHelper() {
                    return {
                        sujet: '',
                        nombreQuestions: 5,
                        loading: false,
                        error: '',
                        questions: [],
                        async generate() {
                            this.loading = true;
                            this.error = '';
                            this.questions = [];
                            try {
                                const response = await fetch('{{ route('admin.ai.generate') }}', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                                    body: JSON.stringify({ sujet: this.sujet, nombre_questions: this.nombreQuestions })
                                });
                                const data = await response.json();
                                if (data.success) {
                                    this.questions = data.data.questions;
                                } else {
                                    this.error = data.message || 'Erreur lors de la g\u00e9n\u00e9ration.';
                                }
                            } catch (e) {
                                this.error = 'Erreur de connexion. V\u00e9rifiez votre cl\u00e9 API OpenRouter dans le .env.';
                            }
                            this.loading = false;
                        },
                        async addAll() {
                            for (const q of this.questions) {
                                const formData = new FormData();
                                formData.append('_token', '{{ csrf_token() }}');
                                formData.append('texte', q.texte);
                                q.reponses.forEach((r, i) => formData.append('reponses['+i+'][texte]', r.texte));
                                formData.append('correcte', q.reponses.findIndex(r => r.est_correcte));
                                await fetch('{{ route('admin.quizzes.questions.store', $quiz) }}', { method: 'POST', body: formData });
                            }
                            window.location.reload();
                        }
                    }
                }
            </script>
        </div>
    </div>
</x-app-layout>