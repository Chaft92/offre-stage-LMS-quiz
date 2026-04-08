<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SousChapitre;
use App\Models\Chapitre;
use Illuminate\Http\Request;

class SousChapitreController extends Controller
{
    public function create(Chapitre $chapitre)
    {
        return view('admin.sous-chapitres.create', compact('chapitre'));
    }

    public function store(Request $request, Chapitre $chapitre)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'nullable|string',
        ]);

        $chapitre->sousChapitres()->create($validated);

        return redirect()->route('admin.formations.show', $chapitre->formation)
            ->with('success', 'Sous-chapitre ajouté.');
    }

    public function edit(SousChapitre $sousChapitre)
    {
        return view('admin.sous-chapitres.edit', compact('sousChapitre'));
    }

    public function update(Request $request, SousChapitre $sousChapitre)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'nullable|string',
        ]);

        $sousChapitre->update($validated);

        return redirect()->route('admin.formations.show', $sousChapitre->chapitre->formation)
            ->with('success', 'Sous-chapitre mis à jour.');
    }

    public function destroy(SousChapitre $sousChapitre)
    {
        $formation = $sousChapitre->chapitre->formation;
        $sousChapitre->delete();

        return redirect()->route('admin.formations.show', $formation)
            ->with('success', 'Sous-chapitre supprimé.');
    }
}
