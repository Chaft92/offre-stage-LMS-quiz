<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    protected $fillable = ['nom', 'description', 'niveau', 'duree'];

    public function chapitres()
    {
        return $this->hasMany(Chapitre::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
