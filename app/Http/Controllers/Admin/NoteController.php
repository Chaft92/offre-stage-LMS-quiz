<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuizResult;
use App\Models\User;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $query = QuizResult::with(['user', 'quiz.sousChapitre.chapitre.formation']);

        if ($request->filled('student_id')) {
            $query->where('user_id', $request->student_id);
        }

        $results = $query->latest()->get();
        $students = User::where('role', 'apprenant')->orderBy('name')->get();

        return view('admin.notes.index', compact('results', 'students'));
    }
}
