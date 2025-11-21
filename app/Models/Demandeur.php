<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demandeur extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_demandeur';
    public $incrementing = true;

    protected $fillable = [
        'user_id',
        'nom',
        'prenom',
        'date_naiss',
        'lieu_naissance',
        'telephone',
        'sexe',
        'groupe_etheroculturel',
        'deca_element',
        'coordonnees_geographiques',
        'coordonne_gec',
        'email',
        'adresse',
        'profession',
        'province',
        'commune',
        'type_detenteur',
        'autre_type_detenteur',
        'nom_structure',
        'type_structure',
        'siege_social',
        'personne_contact',
    ];

    protected $casts = [
        'date_naiss' => 'date',
    ];

    // RELATIONS
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function demandes()
    {
        return $this->hasMany(Demande::class, 'id_demandeur');
    }

    public function detenteur()
    {
        return $this->hasOne(Detenteur::class, 'demandeur_id');
    }

    // MÉTHODES UTILES
    public function getNomCompletAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }

    public function getAgeAttribute()
    {
        return $this->date_naiss ? $this->date_naiss->age : null;
    }

    public function getEstIndividuAttribute()
    {
        return $this->type_detenteur === 'individu';
    }

    public function getEstFamilleAttribute()
    {
        return $this->type_detenteur === 'famille';
    }

    public function getEstCommunauteAttribute()
    {
        return $this->type_detenteur === 'communaute';
    }

    public function getTypeDetenteurFormateAttribute()
    {
        $types = [
            'individu' => 'Individu',
            'famille' => 'Famille',
            'communaute' => 'Communauté',
            'autre' => $this->autre_type_detenteur ?: 'Autre'
        ];

        return $types[$this->type_detenteur] ?? 'Non spécifié';
    }

    // SCOPES
    public function scopeIndividus($query)
    {
        return $query->where('type_detenteur', 'individu');
    }

    public function scopeFamilles($query)
    {
        return $query->where('type_detenteur', 'famille');
    }

    public function scopeCommunautes($query)
    {
        return $query->where('type_detenteur', 'communaute');
    }

    public function scopeRecents($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeParRegion($query, $region)
    {
        return $query->where('localite_exercice', 'LIKE', "%{$region}%");
    }
}
