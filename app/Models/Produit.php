<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
        protected $fillable = [
        'nom',
        'code_barre',
        'description',
        'archived',
        'type',
        'image',
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

    protected static function booted()
    {
       static::created(function ($model) {

            $now = \Carbon\Carbon::now();
            $prefix = $now->format('ym');

            do {
                $suffix = str_pad(random_int(1, 99999), 3, '0', STR_PAD_LEFT);
                $numero = "P{$prefix}{$suffix}";
            } while (self::where('code_barre', $numero)->exists());

            $model->update(['code_barre' => $numero,]);
        });

    }

}
