<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity as BaseActivity;

class AuditActivity extends BaseActivity
{
    protected $table = 'activity_log';
    
    // Nom lisible du CAUSER (qui a effectué l'action)
    public function getCauserNameAttribute(): string
    {
        if (!$this->causer) {
            return 'Système';
        }
        return match ($this->causer_type) {
            'App\Models\Livreur'     =>  ($this->causer->nom ?? '—'),
            'App\Models\Fournisseur' =>  ($this->causer->nom ?? '—'),
            'App\Models\Client'      =>  ($this->causer->nom ?? '—'),
            'App\Models\User'        =>  ($this->causer->name ?? '—'),
            default                  => class_basename($this->causer_type) . " #{$this->causer_id}",
        };
    }
    // Nom lisible du SUBJECT (l'élément qui a été modifié)
    public function getSubjectNameAttribute(): string
    {
        if (!$this->subject) {
            return '—';
        }
        return match ($this->subject_type) {
            'App\Models\Livreur'        => 'Livreur : ' . ($this->subject->nom ?? '—'),
            'App\Models\Fournisseur'    => 'Fournisseur : ' . ($this->subject->nom ?? '—'),
            'App\Models\Client'         => 'Client : ' . ($this->subject->nom ?? '—'),
            'App\Models\User'           => '' . ($this->subject->name ?? '—'),
            default                     => class_basename($this->subject_type) . " #{$this->subject_id}",
        };
    }

}
