<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reponse extends Model
{
    protected $fillable = ['texte', 'est_correcte', 'question_id'];

    protected function casts(): array
    {
        return [
            'est_correcte' => 'boolean',
        ];
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
