<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Formation;
use App\Models\User;
use Illuminate\Http\Request;

class FormationController extends Controller
{
    public function index()
    {
        $formations = Formation::withCount('chapitres')->latest()->get();
        return view('admin.formations.index', compact('formations'));
    }

    public function create()
    {
        return view('admin.formations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'niveau' => 'nullable|string|max:100',
            'duree' => 'nullable|string|max:100',
        ]);

        Formation::create($validated);

        return redirect()->route('admin.formations.index')
            ->with('success', 'Formation créée avec succès.');
    }

    public function show(Formation $formation)
    {
        $formation->load('chapitres.sousChapitres');
        $enrolledUsers = $formation->users()->orderBy('name')->get();
        $availableStudents = User::where('role', 'apprenant')
            ->whereNotIn('id', $enrolledUsers->pluck('id'))
            ->orderBy('name')
            ->get();
        return view('admin.formations.show', compact('formation', 'enrolledUsers', 'availableStudents'));
    }

    public function edit(Formation $formation)
    {
        return view('admin.formations.edit', compact('formation'));
    }

    public function update(Request $request, Formation $formation)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'niveau' => 'nullable|string|max:100',
            'duree' => 'nullable|string|max:100',
        ]);

        $formation->update($validated);

        return redirect()->route('admin.formations.index')
            ->with('success', 'Formation mise à jour.');
    }

    public function destroy(Formation $formation)
    {
        $formation->delete();

        return redirect()->route('admin.formations.index')
            ->with('success', 'Formation supprimée.');
    }

    public function enroll(Request $request, Formation $formation)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $formation->users()->syncWithoutDetaching($validated['user_ids']);

        return redirect()->route('admin.formations.show', $formation)
            ->with('success', count($validated['user_ids']) . ' étudiant(s) inscrit(s) avec succès.');
    }

    public function unenroll(Formation $formation, User $user)
    {
        $formation->users()->detach($user->id);

        return redirect()->route('admin.formations.show', $formation)
            ->with('success', $user->name . ' a été désinscrit de la formation.');
    }
}
