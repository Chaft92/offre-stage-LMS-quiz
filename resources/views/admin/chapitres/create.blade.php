<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-bold text-xl text-gray-900">Ajouter un chapitre</h2>
            <p class="text-sm text-gray-500">{{ $formation->nom }}</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
                <form action="{{ route('admin.chapitres.store', $formation) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <x-input-label for="titre" value="Titre du chapitre" />
                        <x-text-input id="titre" name="titre" type="text" class="mt-1 block w-full" :value="old('titre')" required />
                        <x-input-error :messages="$errors->get('titre')" class="mt-2" />
                    </div>
                    <div class="mb-6">
                        <x-input-label for="description" value="Description" />
                        <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm">{{ old('description') }}</textarea>
                    </div>
                    <div class="flex justify-end">
                        <a href="{{ route('admin.formations.show', $formation) }}" class="mr-3 text-gray-500 hover:text-gray-700 py-2 transition">Annuler</a>
                        <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-xl hover:shadow-lg transition-all font-medium text-sm">Ajouter</button>
                    </div>
                </form>
            </div>

            <!-- AI Generation -->
            <div class="bg-gradient-to-br from-purple-50 via-indigo-50 to-cyan-50 rounded-2xl p-6 shadow-sm border border-purple-100" x-data="aiChapitreGenerator()">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Générer par IA</h3>
                        <p class="text-sm text-gray-500">Décrivez le thème et l'IA génère le titre et la description du chapitre.</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <x-input-label value="Thème du chapitre" />
                        <x-text-input x-model="sujet" type="text" class="mt-1 block w-full" placeholder="Ex: Les bases du HTML, Introduction à la POO..." />
                    </div>
                    <button @click="generate()" :disabled="loading || !sujet"
                        class="w-full px-6 py-3.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:shadow-lg hover:shadow-purple-200 transition-all duration-300 font-semibold disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        <template x-if="!loading">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                Générer par IA
                            </span>
                        </template>
                        <template x-if="loading">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                Génération en cours...
                            </span>
                        </template>
                    </button>
                </div>

                <div x-show="error" x-transition class="mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm" x-text="error"></div>
                <div x-show="successMsg" x-transition class="mt-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm" x-text="successMsg"></div>
            </div>

            <script>
                function aiChapitreGenerator() {
                    return {
                        sujet: '',
                        loading: false,
                        error: '',
                        successMsg: '',
                        async generate() {
                            this.loading = true;
                            this.error = '';
                            this.successMsg = '';
                            try {
                                const response = await fetch('{{ route("admin.ai.generate-chapitre") }}', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                                    body: JSON.stringify({ formation_nom: @js($formation->nom), sujet: this.sujet })
                                });
                                const data = await response.json();
                                if (data.success) {
                                    document.getElementById('titre').value = data.data.titre;
                                    document.getElementById('description').value = data.data.description;
                                    this.successMsg = 'Chapitre généré ! Vous pouvez modifier avant de valider.';
                                } else {
                                    this.error = data.message || 'Erreur lors de la génération.';
                                }
                            } catch (e) {
                                this.error = 'Erreur de connexion. Vérifiez la configuration API.';
                            }
                            this.loading = false;
                        }
                    }
                }
            </script>
        </div>
    </div>
</x-app-layout>