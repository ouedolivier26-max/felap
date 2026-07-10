<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Utilisateur extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'utilisateurs'; 

    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'role',
        'photo',
        'phone',
        'ville',
        'adresse',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relations
    public function colisSauvegarde()
    {
        return $this->hasMany(ColisSauvegarde::class);
    }
 
    public function client()
    {
        return $this->hasOne(Client::class, 'id');
    }

    public function livreur()
    {
        return $this->hasOne(Livreur::class, 'id');
    }

    public function administrateur()
    {
        return $this->hasOne(Administrateur::class, 'id');
    }
}
