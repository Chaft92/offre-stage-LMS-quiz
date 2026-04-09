<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-900">Créer une formation</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <form action="{{ route('admin.formations.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <x-input-label for="nom" value="Nom de la formation" />
                        <x-text-input id="nom" name="nom" type="text" class="mt-1 block w-full" :value="old('nom')" required />
                        <x-input-error :messages="$errors->get('nom')" class="mt-2" />
                    </div>
                    <div class="mb-4">
                        <x-input-label for="description" value="Description" />
                        <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <x-input-label for="niveau" value="Niveau" />
                            <x-text-input id="niveau" name="niveau" type="text" class="mt-1 block w-full" :value="old('niveau')" placeholder="Débutant, Intermédiaire..." />
                        </div>
                        <div>
                            <x-input-label for="duree" value="Durée" />
                            <x-text-input id="duree" name="duree" type="text" class="mt-1 block w-full" :value="old('duree')" placeholder="2 semaines..." />
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <a href="{{ route('admin.formations.index') }}" class="mr-3 text-gray-500 hover:text-gray-700 py-2 transition">Annuler</a>
                        <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-xl hover:shadow-lg transition-all font-medium text-sm">Créer la formation</button>
                    </div>
                </form>
            </div>

            <!-- AI Generation -->
            <div class="bg-gradient-to-br from-purple-50 via-indigo-50 to-cyan-50 rounded-2xl p-6 shadow-sm border border-purple-100 mt-6" x-data="aiFormationGenerator()">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Générer une formation complète par IA</h3>
                        <p class="text-sm text-gray-500">L'IA génère la formation avec tous ses chapitres et sous-chapitres.</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <x-input-label value="Sujet de la formation" />
                        <x-text-input x-model="sujet" type="text" class="mt-1 block w-full" placeholder="Ex: Développement web avec Laravel, Introduction au Machine Learning..." />
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <x-input-label value="Nombre de chapitres" />
                            <x-text-input x-model="nbChapitres" type="number" min="1" max="20" class="mt-1 block w-full" />
                        </div>
                        <div>
                            <x-input-label value="Sous-chapitres par chapitre" />
                            <x-text-input x-model="nbSousChapitres" type="number" min="1" max="10" class="mt-1 block w-full" />
                        </div>
                        <div>
                            <x-input-label value="Niveau" />
                            <select x-model="niveau" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm text-sm">
                                <option value="Débutant">Débutant</option>
                                <option value="Intermédiaire" selected>Intermédiaire</option>
                                <option value="Avancé">Avancé</option>
                            </select>
                        </div>
                    </div>

                    <div x-show="sujet && nbChapitres && nbSousChapitres" x-transition class="bg-white/60 rounded-xl p-4 border border-purple-100">
                        <p class="text-sm text-gray-600">
                            <span class="font-semibold text-indigo-600">Résumé :</span>
                            L'IA va générer une formation sur <span class="font-semibold" x-text="sujet"></span>
                            avec <span class="font-bold text-indigo-600" x-text="nbChapitres"></span> chapitre(s)
                            et <span class="font-bold text-indigo-600" x-text="nbChapitres * nbSousChapitres"></span> sous-chapitre(s) au total.
                        </p>
                    </div>

                    <button @click="generate()" :disabled="loading || !sujet || !nbChapitres || !nbSousChapitres"
                        class="w-full px-6 py-3.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:shadow-lg hover:shadow-purple-200 transition-all duration-300 font-semibold disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        <template x-if="!loading">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                Générer la formation complète
                            </span>
                        </template>
                        <template x-if="loading">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                Génération en cours (cela peut prendre 30-60s)...
                            </span>
                        </template>
                    </button>
                </div>

                <div x-show="error" x-transition class="mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm" x-text="error"></div>
                <div x-show="successMsg" x-transition class="mt-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm" x-text="successMsg"></div>
            </div>

            <script>
                function aiFormationGenerator() {
                    return {
                        sujet: '',
                        nbChapitres: 3,
                        nbSousChapitres: 2,
                        niveau: 'Intermédiaire',
                        loading: false,
                        error: '',
                        successMsg: '',
                        async generate() {
                            this.loading = true;
                            this.error = '';
                            this.successMsg = '';
                            try {
                                const response = await fetch('{{ route("admin.ai.generate-formation") }}', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                                    body: JSON.stringify({
                                        sujet: this.sujet,
                                        nombre_chapitres: parseInt(this.nbChapitres),
                                        nombre_sous_chapitres: parseInt(this.nbSousChapitres),
                                        niveau: this.niveau
                                    })
                                });
                                const data = await response.json();
                                if (data.success) {
                                    this.successMsg = 'Formation "' + data.data.nom + '" créée avec ' + data.data.nb_chapitres + ' chapitres ! Redirection...';
                                    setTimeout(() => window.location.href = data.data.redirect_url, 1500);
                                } else {
                                    this.error = data.message || 'Erreur lors de la génération.';
                                }
                            } catch (e) {
                                this.error = 'Erreur de connexion. Vérifiez la configuration API Groq.';
                            }
                            this.loading = false;
                        }
                    }
                }
            </script>
        </div>
    </div>
</x-app-layout>