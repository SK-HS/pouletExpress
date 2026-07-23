<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quartier extends Model
{
      protected $fillable = [
        'nom_quartier',
        'commune_id',
        'user_id',
    ];

        public function user()
    {
        return $this->belongsTo(User::class);
    }
        public function commune()
    {
        return $this->belongsTo(Commune::class);
    }
       public function service()
    {
        return $this->hasOne(Service::class, 'quartier_id');
        // ou hasMany si plusieurs services possibles par quartier
    }
    
}
