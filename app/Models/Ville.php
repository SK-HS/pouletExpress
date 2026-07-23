<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ville extends Model
{
     protected $fillable = [
        'nom_ville',
        'user_id',
    ];

        public function user()
    {
        return $this->belongsTo(User::class);
    }
}
