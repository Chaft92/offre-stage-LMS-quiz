# LMS Quiz - Mini LMS Pedagogique

Plateforme d'apprentissage en ligne avec gestion de formations, quiz interactifs et notation automatique : Projet test fait et rendu par moi-même, Julien YILDIZ, dans le cadre d'un entretien de Stage. Le tout a été fait par mes soins et uniquement dans le cadre de cet entretien. 
L'application actuel est uniquement un schéma général et non un projet abouti, il peut être avancé et complété par si besoin.

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

- **Backend** : Laravel 12 + PHP 8.5
- **Frontend** : Blade + Tailwind CSS + Alpine.js
- **Auth** : Laravel Breeze
- **Base de donnees** : SQLite
- **IA** : Groq API (LLaMA 3.3 70B, TOTALEMENT GRATUIT)

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

## Les 3 Comptes de demonstration

| Role | Email | Mot de passe |
|------|-------|-------------|
| Professeur | admin@lms.fr | password |
| Etudiant | alice@lms.fr | password |
| Etudiant | bob@lms.fr | password |

## Configuration IA (TOTALEMENT OPTIONNEL)

Pour utiliser la generation de questions par IA, ajouter une cle API Groq dans `.env` :

```
GROQ_API_KEY = CLE_API
```

Obtenir une cle gratuite sur [console.groq.com](https://console.groq.com/).

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
