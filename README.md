# LMS Quiz - Mini LMS Pedagogique

Plateforme d'apprentissage en ligne avec gestion de formations, quiz interactifs et notation automatique.

## Fonctionnalites

### Professeur (Admin)
- Creer/modifier/supprimer des formations (chapitres + sous-chapitres)
- Creer des quiz avec questions a choix multiples (QCM)
- Aide IA pour generer des questions (via OpenRouter API)
- Publier/depublier des quiz
- Consulter les notes de tous les etudiants

### Etudiant (Apprenant)
- Acceder aux formations et contenus pedagogiques
- Passer les quiz publies
- Voir ses resultats et notes (score sur 20)

## Stack technique

- **Backend** : Laravel 12 + PHP 8.x
- **Frontend** : Blade + Tailwind CSS + Alpine.js
- **Auth** : Laravel Breeze
- **Base de donnees** : SQLite
- **IA** : OpenRouter API (Mistral 7B gratuit)

## Installation

```bash
# Cloner le projet
git clone <repo-url>
cd lms-quiz

# Installer les dependances PHP
composer install

# Installer les dependances JS
npm install

# Copier le fichier d'environnement
cp .env.example .env
php artisan key:generate

# Configurer la base de donnees (SQLite par defaut)
touch database/database.sqlite
php artisan migrate

# Charger les donnees de demo
php artisan db:seed

# Compiler les assets
npm run build

# Lancer le serveur
php artisan serve
```

## Comptes de demonstration

| Role | Email | Mot de passe |
|------|-------|-------------|
| Professeur | admin@lms.fr | password |
| Etudiant | alice@lms.fr | password |
| Etudiant | bob@lms.fr | password |

## Configuration IA (optionnel)

Pour utiliser la generation de questions par IA, ajouter une cle API OpenRouter dans `.env` :

```
OPENROUTER_API_KEY=votre_cle_api
```

Obtenir une cle gratuite sur [openrouter.ai](https://openrouter.ai/).

## Structure du projet

```
formations/
  +-- chapitres/
        +-- sous-chapitres/
              +-- quiz/
                    +-- questions/
                          +-- reponses (QCM)
```

## Bareme de notation

- 1 point par bonne reponse
- Score normalise sur 20 : `(score / total_questions) x 20`
