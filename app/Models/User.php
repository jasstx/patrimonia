<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use  HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'telephone',
        'type_utilisateur',
        'is_active',
        'cree_par',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    // RELATIONS

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    public function demandesValidees()
    {
        return $this->hasMany(Demande::class, 'valider_par');
    }

    public function demandesRejetees()
    {
        return $this->hasMany(Demande::class, 'rejeter_par');
    }

    public function detenteur()
    {
        return $this->hasOne(Detenteur::class, 'user_id');
    }

    public function detenteursVerifies()
    {
        return $this->hasMany(Detenteur::class, 'verifie_par');
    }

    // SCOPES

    public function scopeAdministrateurs($query)
    {
        return $query->where('type_utilisateur', 'admin');
    }

    public function scopeGestionnaires($query)
    {
        return $query->where('type_utilisateur', 'gestionnaire');
    }

    public function scopeActifs($query)
    {
        return $query->where('is_active', true);
    }

    // MÉTHODES UTILES

    public function hasRole($roleName)
    {
        return $this->roles()->where('nom_role', $roleName)->exists();
    }

    public function hasAnyRole($roleNames)
    {
        return $this->roles()->whereIn('nom_role', $roleNames)->exists();
    }

    public function assignRole($roleName)
    {
        $role = Role::where('nom_role', $roleName)->first();
        if ($role) {
            $this->roles()->syncWithoutDetaching([$role->id_role]);
        }
    }

    public function isAdministrateur()
    {
        return $this->type_utilisateur === 'admin';
    }

    public function isGestionnaire()
    {
        return $this->type_utilisateur === 'gestionnaire';
    }

    public function isVisiteur()
    {
        return $this->type_utilisateur === 'visiteur';
    }
}
