<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_demande';
    public $incrementing = true;

    // Constantes pour les statuts
    const STATUT_EN_ATTENTE = 'en_attente';
    const STATUT_EN_COURS = 'en_cours';
    const STATUT_VALIDEE = 'validee';
    const STATUT_REJETEE = 'rejetee';

    protected $fillable = [
        'type_demande',
        'date_creation',
        'status',
        'validee_le',
        'rejetee_le',
        'valider_par',
        'rejeter_par',
        'id_demandeur',
        'motif_rejet',
        'declaration_honneur',
        'date_declaration',
        'signature',
        'photo_path',
    ];

    protected $casts = [
        'date_creation' => 'date',
        'validee_le' => 'datetime',
        'rejetee_le' => 'datetime',
        'date_declaration' => 'datetime',
        'declaration_honneur' => 'boolean',
    ];

    // Validation du statut
    public function setStatusAttribute($value)
    {
        $statutsValides = [
            self::STATUT_EN_ATTENTE,
            self::STATUT_EN_COURS,
            self::STATUT_VALIDEE,
            self::STATUT_REJETEE
        ];

        if (!in_array($value, $statutsValides)) {
            throw new \InvalidArgumentException("Statut invalide: {$value}");
        }

        $this->attributes['status'] = $value;
    }

    // RELATIONS
    public function demandeur()
    {
        return $this->belongsTo(Demandeur::class, 'id_demandeur');
    }

    public function validateur()
    {
        return $this->belongsTo(User::class, 'valider_par');
    }

    public function rejeteur()
    {
        return $this->belongsTo(User::class, 'rejeter_par');
    }

    public function piecesJointes()
    {
        return $this->hasMany(PieceJointe::class, 'id_demande');
    }

    public function patrimoines()
    {
        return $this->belongsToMany(Patrimoine::class, 'demande_patrimoine', 'id_demande', 'id_patrimoine')
                    ->withPivot('relation_detenteur', 'anciennete', 'preuves_detention')
                    ->withTimestamps();
    }

    // MÉTHODES UTILES
    public function estValidee()
    {
        return $this->status === self::STATUT_VALIDEE;
    }

    public function estRejetee()
    {
        return $this->status === self::STATUT_REJETEE;
    }

    public function estEnAttente()
    {
        return $this->status === self::STATUT_EN_ATTENTE;
    }

    public function estEnCours()
    {
        return $this->status === self::STATUT_EN_COURS;
    }

    public function getStatutCouleurAttribute()
    {
        $couleurs = [
            self::STATUT_EN_ATTENTE => 'warning',
            self::STATUT_EN_COURS => 'info',
            self::STATUT_VALIDEE => 'success',
            self::STATUT_REJETEE => 'danger'
        ];

        return $couleurs[$this->status] ?? 'secondary';
    }

    public function getStatutLibelleAttribute()
    {
        $libelles = [
            self::STATUT_EN_ATTENTE => 'En attente',
            self::STATUT_EN_COURS => 'En cours de traitement',
            self::STATUT_VALIDEE => 'Validée',
            self::STATUT_REJETEE => 'Rejetée'
        ];

        return $libelles[$this->status] ?? 'Inconnu';
    }

    public function getPhotoUrlAttribute()
    {
        if ($this->photo_path) {
            return asset('storage/' . $this->photo_path);
        }
        return asset('images/default-avatar.png');
    }

    public function valider(?User $validateur = null, $motif = null)
    {
        $this->update([
            'status' => self::STATUT_VALIDEE,
            'validee_le' => now(),
            'valider_par' => $validateur?->id,
            'motif_rejet' => null,
        ]);

        // Créer automatiquement le détenteur si la demande est validée
        $this->creerDetenteur();
    }

    public function rejeter(?User $rejeteur = null, $motif)
    {
        $this->update([
            'status' => self::STATUT_REJETEE,
            'rejetee_le' => now(),
            'rejeter_par' => $rejeteur?->id,
            'motif_rejet' => $motif,
        ]);
    }

    public function mettreEnCours()
    {
        $this->update([
            'status' => self::STATUT_EN_COURS,
        ]);
    }

    public function getElementsPatrimoineListeAttribute()
    {
        return $this->patrimoines->pluck('nom')->implode(', ');
    }

    private function creerDetenteur()
    {
        $demandeur = $this->demandeur;

        if ($demandeur && !$demandeur->detenteur) {
            // Harmoniser le type pour respecter l'ENUM de la table detenteurs
            $typeSource = $demandeur->type_detenteur;
            $typeDetenteur = match ($typeSource) {
                'individu' => 'individuel',
                'communaute', 'famille' => 'communautaire',
                default => 'individuel',
            };

            $detenteur = Detenteur::create([
                'type_detenteur' => $typeDetenteur,
                'photo' => $this->photo_path,
                'biographie' => 'Détenteur validé via demande #' . $this->id_demande,
                'localisation' => $demandeur->localite_exercice,
                'contact' => $demandeur->telephone,
                'est_verifie' => true,
                'date_verification' => now(),
                'verifie_par' => $this->valider_par,
                'demandeur_id' => $demandeur->id_demandeur,
                'user_id' => null,
                'communaute_id' => null,
            ]);

            // Lier les patrimoines de la demande au détenteur
            foreach ($this->patrimoines as $patrimoine) {
                $detenteur->patrimoines()->attach($patrimoine->id_element, [
                    'date_debut_detention' => now(),
                    'type_detention' => 'proprietaire',
                    'est_actif' => true
                ]);
            }
        }
    }

    // SCOPES
    public function scopeEnAttente($query)
    {
        return $query->where('status', self::STATUT_EN_ATTENTE);
    }

    public function scopeEnCours($query)
    {
        return $query->where('status', self::STATUT_EN_COURS);
    }

    public function scopeValidees($query)
    {
        return $query->where('status', self::STATUT_VALIDEE);
    }

    public function scopeRejetees($query)
    {
        return $query->where('status', self::STATUT_REJETEE);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeAvecDeclaration($query)
    {
        return $query->where('declaration_honneur', true);
    }

    public function scopeSansSignature($query)
    {
        return $query->whereNull('signature');
    }

    public function scopePourGestionnaire($query, $user)
    {
        return $query->whereIn('status', [self::STATUT_EN_ATTENTE, self::STATUT_EN_COURS]);
    }

    public function scopePourAdmin($query)
    {
        return $query; // Toutes les demandes pour l'admin
    }
}
