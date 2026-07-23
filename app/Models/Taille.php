<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Taille extends Model
{
        protected $fillable = [
        'taille',
        'description',
        'user_id',
    ];
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
    public function produit()
    {
        return $this->HasMany(Produit::class);
    }

}
