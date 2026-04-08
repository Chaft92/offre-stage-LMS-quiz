<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-900">Cr&eacute;er une formation</h2>
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
                            <x-text-input id="niveau" name="niveau" type="text" class="mt-1 block w-full" :value="old('niveau')" placeholder="D&eacute;butant, Interm&eacute;diaire..." />
                        </div>
                        <div>
                            <x-input-label for="duree" value="Dur&eacute;e" />
                            <x-text-input id="duree" name="duree" type="text" class="mt-1 block w-full" :value="old('duree')" placeholder="2 semaines..." />
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <a href="{{ route('admin.formations.index') }}" class="mr-3 text-gray-500 hover:text-gray-700 py-2 transition">Annuler</a>
                        <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-xl hover:shadow-lg transition-all font-medium text-sm">Cr&eacute;er la formation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>