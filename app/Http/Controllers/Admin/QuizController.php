<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Reponse;
use App\Models\SousChapitre;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::with('sousChapitre.chapitre.formation')->withCount('questions')->latest()->get();
        return view('admin.quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        $sousChapitres = SousChapitre::with('chapitre.formation')->get();
        return view('admin.quizzes.create', compact('sousChapitres'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'sous_chapitre_id' => 'required|exists:sous_chapitres,id',
        ]);

        $quiz = Quiz::create($validated);

        return redirect()->route('admin.quizzes.edit', $quiz)
            ->with('success', 'Quiz créé. Ajoutez maintenant des questions.');
    }

    public function edit(Quiz $quiz)
    {
        $quiz->load('questions.reponses', 'sousChapitre.chapitre.formation');
        $sousChapitres = SousChapitre::with('chapitre.formation')->get();
        return view('admin.quizzes.edit', compact('quiz', 'sousChapitres'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'sous_chapitre_id' => 'required|exists:sous_chapitres,id',
        ]);

        $quiz->update($validated);

        return redirect()->route('admin.quizzes.edit', $quiz)
            ->with('success', 'Quiz mis à jour.');
    }

    public function publish(Quiz $quiz)
    {
        if (!$quiz->published && $quiz->questions()->count() === 0) {
            return redirect()->route('admin.quizzes.index')
                ->with('error', 'Impossible de publier un quiz sans questions.');
        }

        $quiz->update(['published' => !$quiz->published]);
        $status = $quiz->published ? 'publié' : 'dépublié';

        return redirect()->route('admin.quizzes.index')
            ->with('success', "Quiz {$status} avec succès.");
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Quiz supprimé.');
    }

    public function addQuestion(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'texte' => 'required|string',
            'reponses' => 'required|array|min:2',
            'reponses.*.texte' => 'required|string',
            'correcte' => 'required|integer|min:0|max:' . (count($request->input('reponses', [])) - 1),
        ]);

        $question = $quiz->questions()->create(['texte' => $validated['texte']]);

        foreach ($validated['reponses'] as $index => $reponseData) {
            $question->reponses()->create([
                'texte' => $reponseData['texte'],
                'est_correcte' => $index == $validated['correcte'],
            ]);
        }

        return redirect()->route('admin.quizzes.edit', $quiz)
            ->with('success', 'Question ajoutée.');
    }

    public function deleteQuestion(Question $question)
    {
        $quiz = $question->quiz;
        $question->delete();

        return redirect()->route('admin.quizzes.edit', $quiz)
            ->with('success', 'Question supprimée.');
    }

    public function results(Quiz $quiz)
    {
        $quiz->load('results.user');
        return view('admin.quizzes.results', compact('quiz'));
    }
}
