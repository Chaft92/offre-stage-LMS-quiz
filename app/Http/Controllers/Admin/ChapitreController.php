<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapitre;
use App\Models\Formation;
use Illuminate\Http\Request;

class ChapitreController extends Controller
{
    public function create(Formation $formation)
    {
        return view('admin.chapitres.create', compact('formation'));
    }

    public function store(Request $request, Formation $formation)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $formation->chapitres()->create($validated);

        return redirect()->route('admin.formations.show', $formation)
            ->with('success', 'Chapitre ajouté.');
    }

    public function edit(Chapitre $chapitre)
    {
        return view('admin.chapitres.edit', compact('chapitre'));
    }

    public function update(Request $request, Chapitre $chapitre)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $chapitre->update($validated);

        return redirect()->route('admin.formations.show', $chapitre->formation)
            ->with('success', 'Chapitre mis à jour.');
    }

    public function destroy(Chapitre $chapitre)
    {
        $formation = $chapitre->formation;
        $chapitre->delete();

        return redirect()->route('admin.formations.show', $formation)
            ->with('success', 'Chapitre supprimé.');
    }
}
