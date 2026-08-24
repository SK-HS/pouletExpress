<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Client extends Authenticatable
{
     use Notifiable;

    protected $table = 'clients';
    
    protected $fillable = [
        'code_client',
        'nom',
        'type',
        'telephone',
        'contact',
        'email',
        'adresse',
        'quartier_id',
        'user_id',
        'image',
        'password',
        'etat',
        'statut',
    ];

     protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function commandeClients()
    {
        return $this->hasMany(CommandeClient::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function quartier()
    {
        return $this->belongsTo(Quartier::class);
    }

    protected static function booted()
    {

    static::creating(function ($model) {
        // Récupérer le prochain ID
        $nextId = (static::max('id') ?? 0) + 1;

        // Générer le code avec padding
        $model->code_client = "C".str_pad($nextId, 4, '0', STR_PAD_LEFT);
    });
    }
    
}
