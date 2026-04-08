<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Formation;
use App\Models\Chapitre;
use App\Models\SousChapitre;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Reponse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Professeur Martin',
            'email' => 'admin@lms.fr',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $student1 = User::create([
            'name' => 'Alice Dupont',
            'email' => 'alice@lms.fr',
            'password' => Hash::make('password'),
            'role' => 'apprenant',
        ]);

        $student2 = User::create([
            'name' => 'Bob Martin',
            'email' => 'bob@lms.fr',
            'password' => Hash::make('password'),
            'role' => 'apprenant',
        ]);

        $formation = Formation::create([
            'nom' => 'Anglais - Les Verbes Irréguliers',
            'description' => 'Formation complète sur les verbes irréguliers en anglais.',
            'niveau' => 'Débutant',
            'duree' => '2 semaines',
        ]);

        $formation->users()->attach([$student1->id, $student2->id]);

        $chap1 = Chapitre::create([
            'titre' => 'Introduction aux verbes irréguliers',
            'description' => 'Comprendre ce que sont les verbes irréguliers.',
            'formation_id' => $formation->id,
        ]);

        $chap2 = Chapitre::create([
            'titre' => 'Les verbes irréguliers les plus courants',
            'description' => 'Liste et usage des verbes irréguliers fréquents.',
            'formation_id' => $formation->id,
        ]);

        $chap3 = Chapitre::create([
            'titre' => 'Méthodes de mémorisation',
            'description' => 'Techniques pour retenir les verbes irréguliers.',
            'formation_id' => $formation->id,
        ]);

        $sc1 = SousChapitre::create([
            'titre' => 'Définition et règles de base',
            'contenu' => '<h3>Qu\'est-ce qu\'un verbe irrégulier ?</h3><p>En anglais, les verbes irréguliers ne suivent pas la règle standard de conjugaison (-ed). Exemple : go → went → gone.</p><p>Il existe environ 200 verbes irréguliers, mais 50 à 70 sont vraiment courants.</p>',
            'chapitre_id' => $chap1->id,
        ]);

        $sc2 = SousChapitre::create([
            'titre' => '10 verbes indispensables à connaître',
            'contenu' => '<h3>Les 10 verbes irréguliers essentiels</h3><table border="1" cellpadding="8"><tr><th>Base</th><th>Prétérit</th><th>Participe passé</th></tr><tr><td>be</td><td>was/were</td><td>been</td></tr><tr><td>go</td><td>went</td><td>gone</td></tr><tr><td>have</td><td>had</td><td>had</td></tr><tr><td>do</td><td>did</td><td>done</td></tr><tr><td>say</td><td>said</td><td>said</td></tr><tr><td>get</td><td>got</td><td>got/gotten</td></tr><tr><td>make</td><td>made</td><td>made</td></tr><tr><td>take</td><td>took</td><td>taken</td></tr><tr><td>come</td><td>came</td><td>come</td></tr><tr><td>see</td><td>saw</td><td>seen</td></tr></table>',
            'chapitre_id' => $chap2->id,
        ]);

        $sc3 = SousChapitre::create([
            'titre' => 'Techniques de mémorisation efficaces',
            'contenu' => '<h3>Comment retenir les verbes irréguliers ?</h3><ol><li><strong>Regrouper par similarité</strong> (think/thought, buy/bought)</li><li><strong>Flashcards</strong></li><li><strong>Répétition espacée</strong></li><li><strong>Utiliser en contexte</strong></li></ol>',
            'chapitre_id' => $chap3->id,
        ]);

        $quiz = Quiz::create([
            'titre' => 'Quiz : Les 10 verbes irréguliers essentiels',
            'sous_chapitre_id' => $sc2->id,
            'published' => true,
        ]);

        $questionsData = [
            ['texte' => 'Quel est le prétérit de "go" ?', 'reponses' => [
                ['texte' => 'goed', 'est_correcte' => false],
                ['texte' => 'went', 'est_correcte' => true],
                ['texte' => 'gone', 'est_correcte' => false],
                ['texte' => 'going', 'est_correcte' => false],
            ]],
            ['texte' => 'Quel est le participe passé de "see" ?', 'reponses' => [
                ['texte' => 'saw', 'est_correcte' => false],
                ['texte' => 'seed', 'est_correcte' => false],
                ['texte' => 'seen', 'est_correcte' => true],
                ['texte' => 'seeing', 'est_correcte' => false],
            ]],
            ['texte' => 'Quel est le prétérit de "have" ?', 'reponses' => [
                ['texte' => 'haved', 'est_correcte' => false],
                ['texte' => 'has', 'est_correcte' => false],
                ['texte' => 'had', 'est_correcte' => true],
                ['texte' => 'having', 'est_correcte' => false],
            ]],
            ['texte' => 'Quel est le participe passé de "do" ?', 'reponses' => [
                ['texte' => 'did', 'est_correcte' => false],
                ['texte' => 'done', 'est_correcte' => true],
                ['texte' => 'doed', 'est_correcte' => false],
                ['texte' => 'doing', 'est_correcte' => false],
            ]],
            ['texte' => 'Quel est le prétérit de "take" ?', 'reponses' => [
                ['texte' => 'taked', 'est_correcte' => false],
                ['texte' => 'taken', 'est_correcte' => false],
                ['texte' => 'took', 'est_correcte' => true],
                ['texte' => 'taking', 'est_correcte' => false],
            ]],
            ['texte' => 'Quelle est la traduction de "make" ?', 'reponses' => [
                ['texte' => 'prendre', 'est_correcte' => false],
                ['texte' => 'faire / fabriquer', 'est_correcte' => true],
                ['texte' => 'aller', 'est_correcte' => false],
                ['texte' => 'voir', 'est_correcte' => false],
            ]],
            ['texte' => 'Quel est le prétérit de "come" ?', 'reponses' => [
                ['texte' => 'comed', 'est_correcte' => false],
                ['texte' => 'come', 'est_correcte' => false],
                ['texte' => 'came', 'est_correcte' => true],
                ['texte' => 'coming', 'est_correcte' => false],
            ]],
            ['texte' => 'Quel est le participe passé de "be" ?', 'reponses' => [
                ['texte' => 'was', 'est_correcte' => false],
                ['texte' => 'were', 'est_correcte' => false],
                ['texte' => 'been', 'est_correcte' => true],
                ['texte' => 'being', 'est_correcte' => false],
            ]],
        ];

        foreach ($questionsData as $qData) {
            $question = Question::create([
                'texte' => $qData['texte'],
                'quiz_id' => $quiz->id,
            ]);
            foreach ($qData['reponses'] as $rData) {
                Reponse::create(array_merge($rData, ['question_id' => $question->id]));
            }
        }
    }
}
