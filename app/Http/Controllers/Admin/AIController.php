<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapitre;
use App\Models\Formation;
use App\Models\SousChapitre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AIController extends Controller
{
    /**
     * Call the Groq API with a prompt.
     */
    private function callGroq(string $prompt, float $temperature = 0.7): ?string
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.groq.key', ''),
            'Content-Type' => 'application/json',
        ])->timeout(30)->post('https://api.groq.com/openai/v1/chat/completions', [
            'model' => 'llama-3.3-70b-versatile',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => $temperature,
        ]);

        if ($response->successful()) {
            $content = $response->json('choices.0.message.content', '');

            // Extract JSON from markdown code blocks if present
            if (preg_match('/```(?:json)?\s*([\s\S]*?)```/', $content, $matches)) {
                $content = trim($matches[1]);
            }

            return $content;
        }

        return null;
    }

    /**
     * Generate quiz questions via AI.
     */
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
            $content = $this->callGroq($prompt);

            if ($content) {
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

    /**
     * Generate chapter title and description via AI.
     */
    public function generateChapitre(Request $request)
    {
        $validated = $request->validate([
            'formation_nom' => 'required|string|max:500',
            'sujet' => 'required|string|max:500',
        ]);

        $prompt = "Tu es un expert en pédagogie. Pour une formation intitulée \"{$validated['formation_nom']}\", génère un chapitre sur le thème : \"{$validated['sujet']}\".

Réponds UNIQUEMENT avec un JSON valide sans texte supplémentaire, dans ce format exact :
{
  \"titre\": \"Titre du chapitre\",
  \"description\": \"Description détaillée du chapitre en 2-3 phrases.\"
}";

        try {
            $content = $this->callGroq($prompt);

            if ($content) {
                $data = json_decode($content, true);
                if ($data && isset($data['titre'])) {
                    return response()->json(['success' => true, 'data' => $data]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'L\'IA n\'a pas pu générer le chapitre. Veuillez réessayer ou saisir manuellement.',
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de connexion à l\'IA. Vérifiez la configuration API.',
            ], 500);
        }
    }

    /**
     * Generate course (sous-chapitre) content via AI.
     */
    public function generateCours(Request $request)
    {
        $validated = $request->validate([
            'formation_nom' => 'required|string|max:500',
            'chapitre_titre' => 'required|string|max:500',
            'sujet' => 'required|string|max:500',
        ]);

        $prompt = "Tu es un expert en pédagogie. Pour la formation \"{$validated['formation_nom']}\", dans le chapitre \"{$validated['chapitre_titre']}\", génère un cours (sous-chapitre) sur le thème : \"{$validated['sujet']}\".

Le contenu doit être pédagogique, structuré avec des titres HTML (h3, h4), des paragraphes, des listes à puces si nécessaire. Le contenu doit faire environ 300-500 mots.

Réponds UNIQUEMENT avec un JSON valide sans texte supplémentaire, dans ce format exact :
{
  \"titre\": \"Titre du sous-chapitre\",
  \"contenu\": \"<h3>Titre</h3><p>Contenu HTML structuré...</p>\"
}";

        try {
            $content = $this->callGroq($prompt);

            if ($content) {
                $data = json_decode($content, true);
                if ($data && isset($data['titre'])) {
                    return response()->json(['success' => true, 'data' => $data]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'L\'IA n\'a pas pu générer le cours. Veuillez réessayer ou saisir manuellement.',
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de connexion à l\'IA. Vérifiez la configuration API.',
            ], 500);
        }
    }

    /**
     * Generate a complete formation (with chapters and sub-chapters) via AI.
     */
    public function generateFormation(Request $request)
    {
        $validated = $request->validate([
            'sujet' => 'required|string|max:500',
            'nombre_chapitres' => 'required|integer|min:1|max:20',
            'nombre_sous_chapitres' => 'required|integer|min:1|max:10',
            'niveau' => 'nullable|string|max:100',
        ]);

        $nbChapitres = $validated['nombre_chapitres'];
        $nbSousChapitres = $validated['nombre_sous_chapitres'];
        $niveau = $validated['niveau'] ?? 'Intermédiaire';

        $prompt = "Tu es un expert en ingénierie pédagogique. Génère une formation complète sur le sujet : \"{$validated['sujet']}\".
Niveau : {$niveau}.
La formation doit contenir exactement {$nbChapitres} chapitres.
Chaque chapitre doit contenir exactement {$nbSousChapitres} sous-chapitres.
Chaque sous-chapitre doit avoir un contenu pédagogique structuré en HTML (h3, h4, paragraphes, listes) d'environ 300-500 mots.

Réponds UNIQUEMENT avec un JSON valide sans texte supplémentaire, dans ce format exact :
{
  \"nom\": \"Nom de la formation\",
  \"description\": \"Description de la formation en 2-3 phrases.\",
  \"duree\": \"Durée estimée (ex: 4 semaines)\",
  \"chapitres\": [
    {
      \"titre\": \"Titre du chapitre\",
      \"description\": \"Description du chapitre.\",
      \"sous_chapitres\": [
        {
          \"titre\": \"Titre du sous-chapitre\",
          \"contenu\": \"<h3>Titre</h3><p>Contenu HTML pédagogique structuré...</p>\"
        }
      ]
    }
  ]
}";

        try {
            $content = $this->callGroq($prompt, 0.7);

            if (!$content) {
                return response()->json([
                    'success' => false,
                    'message' => 'L\'IA n\'a pas pu générer la formation. Veuillez réessayer.',
                ], 422);
            }

            $data = json_decode($content, true);
            if (!$data || !isset($data['nom']) || !isset($data['chapitres'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Réponse IA invalide. Veuillez réessayer.',
                ], 422);
            }

            // Create the formation in DB
            $formation = Formation::create([
                'nom' => $data['nom'],
                'description' => $data['description'] ?? '',
                'niveau' => $niveau,
                'duree' => $data['duree'] ?? '',
            ]);

            foreach ($data['chapitres'] as $chapitreData) {
                $chapitre = Chapitre::create([
                    'titre' => $chapitreData['titre'],
                    'description' => $chapitreData['description'] ?? '',
                    'formation_id' => $formation->id,
                ]);

                if (isset($chapitreData['sous_chapitres'])) {
                    foreach ($chapitreData['sous_chapitres'] as $scData) {
                        SousChapitre::create([
                            'titre' => $scData['titre'],
                            'contenu' => $scData['contenu'] ?? '',
                            'chapitre_id' => $chapitre->id,
                        ]);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'formation_id' => $formation->id,
                    'nom' => $formation->nom,
                    'nb_chapitres' => count($data['chapitres']),
                    'redirect_url' => route('admin.formations.show', $formation),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de connexion à l\'IA. Vérifiez la configuration API.',
            ], 500);
        }
    }
}
