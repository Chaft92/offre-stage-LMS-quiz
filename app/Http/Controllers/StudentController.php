<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\Quiz;
use App\Models\QuizResult;
use App\Models\SousChapitre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $formations = $user->formations()->with('chapitres.sousChapitres')->get();
        $recentResults = $user->quizResults()->with('quiz')->latest()->take(5)->get();

        return view('student.dashboard', compact('formations', 'recentResults'));
    }

    public function formation(Formation $formation)
    {
        if (!Auth::user()->formations()->where('formations.id', $formation->id)->exists()) {
            abort(403, 'Vous n\'êtes pas inscrit à cette formation.');
        }

        $formation->load('chapitres.sousChapitres.quizzes');
        return view('student.formation', compact('formation'));
    }

    public function sousChapitre(SousChapitre $sousChapitre)
    {
        $sousChapitre->load('chapitre.formation', 'quizzes');

        if (!Auth::user()->formations()->where('formations.id', $sousChapitre->chapitre->formation_id)->exists()) {
            abort(403);
        }

        return view('student.sous-chapitre', compact('sousChapitre'));
    }

    public function quiz(Quiz $quiz)
    {
        if (!$quiz->published) {
            abort(404);
        }

        $quiz->load('questions.reponses', 'sousChapitre.chapitre.formation');

        if (!Auth::user()->formations()->where('formations.id', $quiz->sousChapitre->chapitre->formation_id)->exists()) {
            abort(403);
        }

        // Check if already taken
        $existingResult = QuizResult::where('user_id', Auth::id())
            ->where('quiz_id', $quiz->id)
            ->first();

        return view('student.quiz', compact('quiz', 'existingResult'));
    }

    public function submitQuiz(Request $request, Quiz $quiz)
    {
        if (!$quiz->published) {
            abort(404);
        }

        $quiz->load('questions.reponses', 'sousChapitre.chapitre.formation');

        if (!Auth::user()->formations()->where('formations.id', $quiz->sousChapitre->chapitre->formation_id)->exists()) {
            abort(403);
        }

        $validated = $request->validate([
            'answers' => 'required|array|size:' . $quiz->questions->count(),
            'answers.*' => 'required|integer|exists:reponses,id',
        ]);

        $score = 0;
        $totalQuestions = $quiz->questions->count();

        foreach ($quiz->questions as $question) {
            $selectedReponseId = $validated['answers'][$question->id] ?? null;
            if ($selectedReponseId) {
                $reponse = $question->reponses->find($selectedReponseId);
                if ($reponse && $reponse->est_correcte) {
                    $score++;
                }
            }
        }

        // Calculate score on 20
        if ($totalQuestions === 0) {
            return back()->with('error', 'Ce quiz ne contient aucune question.');
        }

        $scoreSur20 = round(($score / $totalQuestions) * 20, 2);

        $result = QuizResult::updateOrCreate(
            ['user_id' => Auth::id(), 'quiz_id' => $quiz->id],
            ['score' => $score, 'total_questions' => $totalQuestions, 'score_sur_20' => $scoreSur20]
        );

        return redirect()->route('student.quiz.result', $quiz)
            ->with('result', $result);
    }

    public function quizResult(Quiz $quiz)
    {
        $result = QuizResult::where('user_id', Auth::id())
            ->where('quiz_id', $quiz->id)
            ->firstOrFail();

        $quiz->load('questions.reponses', 'sousChapitre.chapitre.formation');

        return view('student.quiz-result', compact('quiz', 'result'));
    }

    public function notes()
    {
        $results = QuizResult::where('user_id', Auth::id())
            ->with('quiz.sousChapitre.chapitre.formation')
            ->latest()
            ->get();

        return view('student.notes', compact('results'));
    }
}
