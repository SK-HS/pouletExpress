<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
      protected $fillable = [
        'nom_commune',
        'ville_id',
        'user_id',
    ];

        public function user()
    {
        return $this->belongsTo(User::class);
    }
        public function ville()
    {
        return $this->belongsTo(Ville::class);
    }
     
}
