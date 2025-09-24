<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_categorie';
    public $incrementing = true;

    protected $fillable = [
        'initiale',
        'nom_complet',
        'description',
        'couleur',
        'est_actif',
    ];

    protected $casts = [
        'est_actif' => 'boolean',
    ];

    // RELATIONS

    public function patrimoines()
    {
        return $this->hasMany(Patrimoine::class, 'id_categorie');
    }

    // MÉTHODES UTILES

    public function getNombrePatrimoinesAttribute()
    {
        return $this->patrimoines()->count();
    }

    // SCOPES

    public function scopeActives($query)
    {
        return $query->where('est_actif', true);
    }

    public function scopeAvecPatrimoines($query)
    {
        return $query->whereHas('patrimoines');
    }
}
