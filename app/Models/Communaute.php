<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Communaute extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_communaute';
    public $incrementing = true;

    protected $fillable = [
        'nom_communaute',
        'type_structure',
        'contact_principal',
        'siege_social',
        'coordonne_gec',
        'telephone',
        'email',
        'description',
        'nombre_membres',
        'region',
        'ville',
        'est_actif',
    ];

    protected $casts = [
        'est_actif' => 'boolean',
        'nombre_membres' => 'integer',
    ];

    // RELATIONS

    public function detenteurs()
    {
        return $this->hasMany(Detenteur::class, 'communaute_id');
    }

    // MÉTHODES UTILES

    public function getEstActiveAttribute()
    {
        return $this->est_actif;
    }

    public function getNombreDetenteursAttribute()
    {
        return $this->detenteurs()->count();
    }

    // SCOPES

    public function scopeActives($query)
    {
        return $query->where('est_actif', true);
    }

    public function scopeParRegion($query, $region)
    {
        return $query->where('region', $region);
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type_structure', $type);
    }
}
