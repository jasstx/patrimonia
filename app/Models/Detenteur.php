<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detenteur extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_detenteur';
    public $incrementing = true;

    protected $fillable = [
        'type_detenteur',
        'photo',
        'biographie',
        'specialite',
        'annees_experience',
        'est_verifie',
        'date_verification',
        'verifie_par',
        'user_id',
        'communaute_id',
        'demandeur_id',
    ];

    protected $casts = [
        'est_verifie' => 'boolean',
        'date_verification' => 'datetime',
        'annees_experience' => 'integer',
    ];

    // RELATIONS

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function communaute()
    {
        return $this->belongsTo(Communaute::class, 'communaute_id');
    }

    public function demandeur()
    {
        return $this->belongsTo(Demandeur::class, 'demandeur_id');
    }

    public function verificateur()
    {
        return $this->belongsTo(User::class, 'verifie_par');
    }

    public function patrimoines()
    {
        return $this->belongsToMany(Patrimoine::class, 'detenteur_patrimoine', 'id_detenteur', 'id_patrimoine')
                    ->withPivot('date_debut_detention', 'type_detention', 'preuves', 'est_actif')
                    ->withTimestamps();
    }

    // MÉTHODES UTILES

    public function getNomCompletAttribute()
    {
        if ($this->type_detenteur === 'individuel' && $this->demandeur) {
            return $this->demandeur->nom_complet;
        } elseif ($this->type_detenteur === 'communautaire' && $this->communaute) {
            return $this->communaute->nom_communaute;
        }
        return 'Détenteur inconnu';
    }

    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        return asset('images/default-avatar.png');
    }

    public function getEstIndividuelAttribute()
    {
        return $this->type_detenteur === 'individuel';
    }

    public function getEstCommunautaireAttribute()
    {
        return $this->type_detenteur === 'communautaire';
    }

    public function getPatrimoinesActifsAttribute()
    {
        return $this->patrimoines()->wherePivot('est_actif', true)->get();
    }

    public function verifier(User $verificateur)
    {
        $this->update([
            'est_verifie' => true,
            'date_verification' => now(),
            'verifie_par' => $verificateur->id,
        ]);
    }

    // SCOPES

    public function scopeVerifies($query)
    {
        return $query->where('est_verifie', true);
    }

    public function scopeIndividuels($query)
    {
        return $query->where('type_detenteur', 'individuel');
    }

    public function scopeCommunautaires($query)
    {
        return $query->where('type_detenteur', 'communautaire');
    }

    public function scopeAvecPatrimoines($query)
    {
        return $query->whereHas('patrimoines');
    }

    public function scopeParRegion($query, $region)
    {
        return $query->whereHas('demandeur', function($q) use ($region) {
            $q->where('coordonne_gec', 'LIKE', "%{$region}%");
        })->orWhereHas('communaute', function($q) use ($region) {
            $q->where('region', $region);
        });
    }
}
