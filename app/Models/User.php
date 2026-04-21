<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $fillable = [
        'nom',
        'email',
        'password',
        'role',
        'photo_profil',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // Utilise l'email comme identifiant JWT pour compatibilite avec SpringBoot
    public function getJWTIdentifier()
    {
        return $this->email;
    }

    // Indique a tymon/jwt-auth de chercher l'utilisateur par email lors de la validation du token
    public function getAuthIdentifierName()
    {
        return 'email';
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function formations()
    {
        return $this->hasMany(Formation::class, 'formateur_id');
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class, 'utilisateur_id');
    }

    public function modulesTermines()
    {
        return $this->belongsToMany(Module::class, 'module_user', 'utilisateur_id', 'module_id')
            ->withPivot('termine')
            ->withTimestamps();
    }
    /**
     * Relation : messages envoyés par l'utilisateur.
     */
    public function messagesEnvoyes()
    {
        return $this->hasMany(Message::class, 'expediteur_id');
    }

    /**
     * Relation : messages reçus par l'utilisateur.
     */
    public function messagesRecus()
    {
        return $this->hasMany(Message::class, 'destinataire_id');
    }
}