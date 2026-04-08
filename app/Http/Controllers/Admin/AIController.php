<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AIController extends Controller
{
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'sujet' => 'required|string|max:500',
            'nombre_questions' => 'required|integer|min:1|max:30',
        ]);

        $prompt = "Génère un quiz éducatif en JSON sur le sujet : \"{$validated['sujet']}\". 
Le quiz doit contenir exactement {$validated['nombre_questions']} questions à choix multiples.
Chaque question doit avoir exactement 4 réponses dont une seule correcte.

Réponds UNIQUEMENT avec un JSON valide sans texte supplémentaire, dans ce format exact :
{
  \"questions\": [
    {
      \"texte\": \"La question ici ?\",
      \"reponses\": [
        {\"texte\": \"Réponse A\", \"est_correcte\": false},
        {\"texte\": \"Réponse B\", \"est_correcte\": true},
        {\"texte\": \"Réponse C\", \"est_correcte\": false},
        {\"texte\": \"Réponse D\", \"est_correcte\": false}
      ]
    }
  ]
}";

        try {
            // Using OpenRouter free API with a free model
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.openrouter.key', ''),
                'Content-Type' => 'application/json',
                'HTTP-Referer' => config('app.url'),
            ])->timeout(30)->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => 'mistralai/mistral-7b-instruct:free',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $content = $response->json('choices.0.message.content', '');

                // Extract JSON from markdown code blocks if present
                if (preg_match('/```(?:json)?\s*([\s\S]*?)```/', $content, $matches)) {
                    $content = trim($matches[1]);
                }

                $data = json_decode($content, true);
                if ($data && isset($data['questions'])) {
                    return response()->json(['success' => true, 'data' => $data]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'L\'IA n\'a pas pu générer le quiz. Veuillez réessayer ou saisir manuellement.',
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de connexion à l\'IA. Vérifiez la configuration API ou saisissez manuellement.',
            ], 500);
        }
    }
}
