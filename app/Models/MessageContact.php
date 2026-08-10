<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageContact extends Model
{
      protected $fillable = [
        'nom',
        'telephone',
        'email',
        'sujet',
        'message',
        'statut',
        'user_id',
    ];
    
     public function user()
    {
        return $this->belongsTo(User::class);
    }
}
