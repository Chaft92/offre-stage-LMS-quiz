<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-900">Cr&eacute;er un quiz</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- Standard creation -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Cr&eacute;ation manuelle</h3>
                <form action="{{ route('admin.quizzes.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <x-input-label for="titre" value="Titre du quiz" />
                        <x-text-input id="titre" name="titre" type="text" class="mt-1 block w-full" :value="old('titre')" required />
                        <x-input-error :messages="$errors->get('titre')" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <x-input-label for="sous_chapitre_id" value="Li&eacute; au sous-chapitre" />
                        <select id="sous_chapitre_id" name="sous_chapitre_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm" required>
                            <option value="">-- Choisir --</option>
                            @foreach($sousChapitres as $sc)
                                <option value="{{ $sc->id }}" {{ old('sous_chapitre_id') == $sc->id ? 'selected' : '' }}>
                                    {{ $sc->chapitre->formation->nom }} &gt; {{ $sc->chapitre->titre }} &gt; {{ $sc->titre }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('sous_chapitre_id')" class="mt-2" />
                    </div>
                    <div class="flex justify-end">
                        <a href="{{ route('admin.quizzes.index') }}" class="mr-3 text-gray-500 hover:text-gray-700 py-2 transition">Annuler</a>
                        <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-xl hover:shadow-lg transition-all font-medium text-sm">Cr&eacute;er le quiz</button>
                    </div>
                </form>
            </div>

            <!-- AI auto-generate -->
            <div class="bg-gradient-to-br from-purple-50 via-indigo-50 to-cyan-50 rounded-2xl p-6 shadow-sm border border-purple-100" x-data="aiQuizCreator()">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Laisser l'IA faire</h3>
                        <p class="text-sm text-gray-500">Donnez un sujet et l'IA g&eacute;n&egrave;re automatiquement le quiz complet.</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <x-input-label value="Sujet du quiz" />
                        <x-text-input x-model="sujet" type="text" class="mt-1 block w-full" placeholder="Ex: Les verbes irr&eacute;guliers en anglais, la photosyn&egrave;se..." />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label value="Sous-chapitre" />
                            <select x-model="sousChapitreId" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm">
                                <option value="">-- Choisir --</option>
                                @foreach($sousChapitres as $sc)
                                    <option value="{{ $sc->id }}">
                                        {{ $sc->chapitre->formation->nom }} &gt; {{ $sc->titre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label value="Nombre de questions" />
                            <x-text-input x-model="nombreQuestions" type="number" min="1" max="30" class="mt-1 block w-full" />
                        </div>
                    </div>
                    <button @click="generate()" :disabled="loading || !sujet || !sousChapitreId"
                        class="w-full px-6 py-3.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:shadow-lg hover:shadow-purple-200 transition-all duration-300 font-semibold disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        <template x-if="!loading">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                Laisser l'IA faire
                            </span>
                        </template>
                        <template x-if="loading">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                G&eacute;n&eacute;ration en cours...
                            </span>
                        </template>
                    </button>
                </div>

                <div x-show="error" x-transition class="mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm" x-text="error"></div>
                <div x-show="successMsg" x-transition class="mt-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm" x-text="successMsg"></div>
            </div>

            <script>
                function aiQuizCreator() {
                    return {
                        sujet: '',
                        sousChapitreId: '',
                        nombreQuestions: 5,
                        loading: false,
                        error: '',
                        successMsg: '',
                        async generate() {
                            this.loading = true;
                            this.error = '';
                            this.successMsg = '';
                            try {
                                // Step 1: Create the quiz
                                const createForm = new FormData();
                                createForm.append('_token', '{{ csrf_token() }}');
                                createForm.append('titre', this.sujet);
                                createForm.append('sous_chapitre_id', this.sousChapitreId);
                                const createResp = await fetch('{{ route("admin.quizzes.store") }}', {
                                    method: 'POST',
                                    body: createForm,
                                    redirect: 'follow'
                                });
                                // Get the quiz edit URL from redirect
                                const editUrl = createResp.url;
                                const quizId = editUrl.match(/quizzes\/(\d+)/)?.[1];
                                if (!quizId) { this.error = 'Erreur lors de la cr\u00e9ation du quiz.'; this.loading = false; return; }

                                // Step 2: Generate questions with AI
                                const aiResp = await fetch('{{ route("admin.ai.generate") }}', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                                    body: JSON.stringify({ sujet: this.sujet, nombre_questions: this.nombreQuestions })
                                });
                                const aiData = await aiResp.json();
                                if (!aiData.success) { window.location.href = editUrl; return; }

                                // Step 3: Add all questions
                                for (const q of aiData.data.questions) {
                                    const fd = new FormData();
                                    fd.append('_token', '{{ csrf_token() }}');
                                    fd.append('texte', q.texte);
                                    q.reponses.forEach((r, i) => fd.append('reponses['+i+'][texte]', r.texte));
                                    fd.append('correcte', q.reponses.findIndex(r => r.est_correcte));
                                    await fetch(`{{ url('admin/quizzes') }}/` + quizId + '/questions', { method: 'POST', body: fd });
                                }

                                this.successMsg = 'Quiz cr\u00e9\u00e9 avec ' + aiData.data.questions.length + ' questions ! Redirection...';
                                setTimeout(() => window.location.href = editUrl, 1500);
                            } catch (e) {
                                this.error = 'Erreur de connexion. V\u00e9rifiez votre cl\u00e9 API OpenRouter.';
                            }
                            this.loading = false;
                        }
                    }
                }
            </script>
        </div>
    </div>
</x-app-layout>