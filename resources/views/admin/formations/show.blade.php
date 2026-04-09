<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-xl text-gray-900">{{ $formation->nom }}</h2>
                <p class="text-sm text-gray-500">{{ $formation->niveau }}  {{ $formation->duree }}</p>
            </div>
            <a href="{{ route('admin.formations.index') }}" class="inline-flex items-center gap-1.5 text-gray-500 hover:text-indigo-600 text-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Retour
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ session('success') }}
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
            @endif

            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8">
                <p class="text-gray-600 leading-relaxed">{{ $formation->description }}</p>
            </div>

            <div class="space-y-4">
                @foreach($formation->chapitres as $chapitre)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 bg-gradient-to-r from-indigo-50/50 to-transparent border-b border-gray-100">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                    <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 text-sm font-bold">{{ $loop->iteration }}</div>
                                    {{ $chapitre->titre }}
                                </h3>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('admin.sous-chapitres.create', $chapitre) }}" class="p-2 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition text-sm" title="Ajouter sous-chapitre">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    </a>
                                    <a href="{{ route('admin.chapitres.edit', $chapitre) }}" class="p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition" title="Modifier">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.chapitres.destroy', $chapitre) }}" method="POST" onsubmit="return confirm('Supprimer ce chapitre ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Supprimer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @if($chapitre->description)
                                <p class="text-gray-500 text-sm mt-1 ml-10">{{ $chapitre->description }}</p>
                            @endif
                        </div>
                        <div class="divide-y divide-gray-50">
                            @foreach($chapitre->sousChapitres as $sc)
                                <div class="flex items-center justify-between px-6 py-3">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span class="text-gray-700 text-sm font-medium">{{ $sc->titre }}</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <a href="{{ route('admin.sous-chapitres.edit', $sc) }}" class="p-1.5 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form action="{{ route('admin.sous-chapitres.destroy', $sc) }}" method="POST" onsubmit="return confirm('Supprimer ?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <a href="{{ route('admin.chapitres.create', $formation) }}" class="inline-flex items-center gap-2 mt-6 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white text-sm font-medium rounded-xl hover:shadow-lg transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Ajouter un chapitre
            </a>

            <!-- Gestion des étudiants inscrits -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mt-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Étudiants inscrits ({{ $enrolledUsers->count() }})
                </h3>

                {{-- Formulaire d'inscription --}}
                @if($availableStudents->count() > 0)
                    <form action="{{ route('admin.formations.enroll', $formation) }}" method="POST" class="mb-6" x-data="{ selected: [] }">
                        @csrf
                        <div class="flex flex-wrap gap-3 items-end">
                            <div class="flex-1 min-w-[250px]">
                                <x-input-label value="Inscrire des étudiants" />
                                <select name="user_ids[]" multiple x-model="selected"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm text-sm"
                                    size="{{ min($availableStudents->count(), 5) }}">
                                    @foreach($availableStudents as $student)
                                        <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-400 mt-1">Maintenez Ctrl pour sélectionner plusieurs étudiants.</p>
                            </div>
                            <button type="submit" :disabled="selected.length === 0"
                                class="px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white rounded-xl hover:shadow-lg transition-all font-medium text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                Inscrire
                            </button>
                        </div>
                    </form>
                @else
                    <p class="text-sm text-gray-400 mb-4">Tous les étudiants sont déjà inscrits à cette formation.</p>
                @endif

                {{-- Liste des inscrits --}}
                @if($enrolledUsers->count() > 0)
                    <div class="divide-y divide-gray-50">
                        @foreach($enrolledUsers as $user)
                            <div class="flex items-center justify-between py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold text-sm">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <form action="{{ route('admin.formations.unenroll', [$formation, $user]) }}" method="POST" onsubmit="return confirm('Désinscrire {{ $user->name }} ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Désinscrire">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6"/></svg>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-gray-400 py-4">Aucun étudiant inscrit. Utilisez le formulaire ci-dessus pour en ajouter.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>