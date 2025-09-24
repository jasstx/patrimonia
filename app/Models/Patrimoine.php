<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patrimoine extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_element';
    public $incrementing = true;

    protected $fillable = [
        'nom',
        'initiale',
        'description',
        'status',
        'date_inscription',
        'localisation',
        'region',
        'id_categorie',
        'historique',
        'caracteristiques',
        'est_urgent',
        'domaine', 
        'numero_element',
    ];

    protected $casts = [
        'date_inscription' => 'date',
        'est_urgent' => 'boolean',
    ];

    // RELATIONS
    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'id_categorie');
    }

    public function demandes()
    {
        return $this->belongsToMany(Demande::class, 'demande_patrimoine', 'id_patrimoine', 'id_demande')
                    ->withPivot('relation_detenteur', 'anciennete', 'preuves_detention')
                    ->withTimestamps();
    }

    public function detenteurs()
    {
        return $this->belongsToMany(Detenteur::class, 'detenteur_patrimoine', 'id_patrimoine', 'id_detenteur')
                    ->withPivot('date_debut_detention', 'type_detention', 'preuves', 'est_actif')
                    ->withTimestamps();
    }

    // MÉTHODES UTILES
    public function estInscrit()
    {
        return $this->status === 'inscrit';
    }

    public function estClasse()
    {
        return $this->status === 'classe';
    }

    public function estPropose()
    {
        return $this->status === 'propose';
    }

    public function getDetenteursActifsAttribute()
    {
        return $this->detenteurs()->wherePivot('est_actif', true)->get();
    }

    public function getDomaineCompletAttribute()
    {
        $domaines = [
            'CPNU' => 'Connaissances et Pratiques liées à la nature et à l\'Univers',
            'PSREF' => 'Pratiques Sociales, Rites et Événements Festifs',
            'ADS' => 'Arts du Spectacle',
            'SFAT' => 'Savoir-faire liés à l\'Artisanat Traditionnel',
            'TEO' => 'Traditions et expressions orales',
        ];

        return $domaines[$this->domaine] ?? $this->domaine;
    }

    public static function getElementsParDomaine($domaine)
    {
        return static::where('domaine', $domaine)->orderBy('numero_element')->get();
    }

    public static function getListeComplete()
    {
        return static::orderBy('domaine')->orderBy('numero_element')->get()->groupBy('domaine');
    }

    // SCOPES
    public function scopeInscrits($query)
    {
        return $query->where('status', 'inscrit');
    }

    public function scopeClasses($query)
    {
        return $query->where('status', 'classe');
    }

    public function scopeProposes($query)
    {
        return $query->where('status', 'propose');
    }

    public function scopeUrgents($query)
    {
        return $query->where('est_urgent', true);
    }

    public function scopeParDomaine($query, $domaine)
    {
        return $query->where('domaine', $domaine);
    }

    public function scopeParRegion($query, $region)
    {
        return $query->where('region', $region);
    }

    public function scopeParCategorie($query, $categorieId)
    {
        return $query->where('id_categorie', $categorieId);
    }

    public function scopeRechercher($query, $term)
    {
        return $query->where('nom', 'LIKE', "%{$term}%")
                    ->orWhere('description', 'LIKE', "%{$term}%")
                    ->orWhere('initiale', 'LIKE', "%{$term}%");
    }
}
