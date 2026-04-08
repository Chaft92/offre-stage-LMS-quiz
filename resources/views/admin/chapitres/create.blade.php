<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-bold text-xl text-gray-900">Ajouter un chapitre</h2>
            <p class="text-sm text-gray-500">{{ $formation->nom }}</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
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
        </div>
    </div>
</x-app-layout>