<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Service extends Model
{
    protected $fillable = [
        'designation',
        'prix',
        'Detail',
        'user_id',
        'quartier_id',
    ];

   
       public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    } 
       public function quartier()
    {
        return $this->belongsTo(\App\Models\Quartier::class);
    } 
       public function entreprise()
    {
        return $this->belongsTo(\App\Models\Entreprise::class);
    } 
     

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->user_id = Auth::id();
        });
    }
}
