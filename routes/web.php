<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Admin\FormationController;
use App\Http\Controllers\Admin\ChapitreController;
use App\Http\Controllers\Admin\SousChapitreController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\NoteController;
use App\Http\Controllers\Admin\AIController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('student.dashboard');
})->middleware(['auth'])->name('dashboard');

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        $stats = [
            'formations' => \App\Models\Formation::count(),
            'quizzes' => \App\Models\Quiz::count(),
            'students' => \App\Models\User::where('role', 'apprenant')->count(),
            'results' => \App\Models\QuizResult::count(),
        ];
        return view('admin.dashboard', compact('stats'));
    })->name('dashboard');

    Route::resource('formations', FormationController::class);

    Route::get('formations/{formation}/chapitres/create', [ChapitreController::class, 'create'])->name('chapitres.create');
    Route::post('formations/{formation}/chapitres', [ChapitreController::class, 'store'])->name('chapitres.store');
    Route::get('chapitres/{chapitre}/edit', [ChapitreController::class, 'edit'])->name('chapitres.edit');
    Route::put('chapitres/{chapitre}', [ChapitreController::class, 'update'])->name('chapitres.update');
    Route::delete('chapitres/{chapitre}', [ChapitreController::class, 'destroy'])->name('chapitres.destroy');

    Route::get('chapitres/{chapitre}/sous-chapitres/create', [SousChapitreController::class, 'create'])->name('sous-chapitres.create');
    Route::post('chapitres/{chapitre}/sous-chapitres', [SousChapitreController::class, 'store'])->name('sous-chapitres.store');
    Route::get('sous-chapitres/{sousChapitre}/edit', [SousChapitreController::class, 'edit'])->name('sous-chapitres.edit');
    Route::put('sous-chapitres/{sousChapitre}', [SousChapitreController::class, 'update'])->name('sous-chapitres.update');
    Route::delete('sous-chapitres/{sousChapitre}', [SousChapitreController::class, 'destroy'])->name('sous-chapitres.destroy');

    Route::resource('quizzes', QuizController::class)->except(['show']);
    Route::post('quizzes/{quiz}/publish', [QuizController::class, 'publish'])->name('quizzes.publish');
    Route::post('quizzes/{quiz}/questions', [QuizController::class, 'addQuestion'])->name('quizzes.questions.store');
    Route::delete('questions/{question}', [QuizController::class, 'deleteQuestion'])->name('questions.destroy');
    Route::get('quizzes/{quiz}/results', [QuizController::class, 'results'])->name('quizzes.results');

    Route::get('notes', [NoteController::class, 'index'])->name('notes.index');

    Route::post('ai/generate', [AIController::class, 'generate'])->middleware('throttle:5,1')->name('ai.generate');
});

// Student routes
Route::middleware(['auth'])->prefix('etudiant')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    Route::get('/formation/{formation}', [StudentController::class, 'formation'])->name('formation');
    Route::get('/sous-chapitre/{sousChapitre}', [StudentController::class, 'sousChapitre'])->name('sous-chapitre');
    Route::get('/quiz/{quiz}', [StudentController::class, 'quiz'])->name('quiz');
    Route::post('/quiz/{quiz}', [StudentController::class, 'submitQuiz'])->name('quiz.submit');
    Route::get('/quiz/{quiz}/resultat', [StudentController::class, 'quizResult'])->name('quiz.result');
    Route::get('/notes', [StudentController::class, 'notes'])->name('notes');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
