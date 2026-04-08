<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SousChapitre extends Model
{
    protected $table = 'sous_chapitres';
    protected $fillable = ['titre', 'contenu', 'chapitre_id'];

    public function chapitre()
    {
        return $this->belongsTo(Chapitre::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'sous_chapitre_id');
    }
}
