<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entreprise extends Model
{
     protected $fillable = [
        'nom',
        'adresse',
        'telephone',
        'contact',
        'email',
        'site_web',
        'logo',
        'pied_page',
        'status',
        'user_id',
        'ville',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
     }

     
}
