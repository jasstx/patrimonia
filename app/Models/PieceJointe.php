<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PieceJointe extends Model
{
    use HasFactory;

    // Correction du nom de table (migration: pieces_jointes)
    protected $table = 'pieces_jointes';

    protected $primaryKey = 'id_piece';
    public $incrementing = true;

    protected $fillable = [
        'type_piece', 
        'nom_fichier',
        'chemin',
        'taille',
        'mime_type',
        'date_ajout',
        'id_demande',
        'description',
    ];

    protected $casts = [
        'date_ajout' => 'date',
        'taille' => 'integer',
    ];

    // RELATIONS
    public function demande()
    {
        return $this->belongsTo(Demande::class, 'id_demande');
    }

    // MÉTHODES UTILES
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->chemin);
    }

    public function getLienTelechargementAttribute()
    {
        return route('pieces-jointes.download', $this->id_piece);
    }

    public function getEstImageAttribute()
    {
        return strpos($this->mime_type, 'image/') === 0;
    }

    public function getEstDocumentAttribute()
    {
        return in_array($this->mime_type, [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'text/plain',
        ]);
    }

    public function getEstVideoAttribute()
    {
        return strpos($this->mime_type, 'video/') === 0;
    }

    public function getIconeTypeAttribute()
    {
        if ($this->est_image) return 'fa-image';
        if ($this->est_document) return 'fa-file-pdf';
        if ($this->est_video) return 'fa-video';
        return 'fa-file';
    }

    public function getTailleFormateeAttribute()
    {
        if ($this->taille < 1024) return $this->taille . ' octets';
        if ($this->taille < 1048576) return round($this->taille / 1024, 2) . ' KB';
        return round($this->taille / 1048576, 2) . ' MB';
    }

    // SCOPES
    public function scopeImages($query)
    {
        return $query->where('mime_type', 'LIKE', 'image/%');
    }

    public function scopeDocuments($query)
    {
        return $query->where('mime_type', 'LIKE', 'application/%')
                    ->orWhere('mime_type', 'LIKE', 'text/%');
    }

    public function scopeVideos($query)
    {
        return $query->where('mime_type', 'LIKE', 'video/%');
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type_piece', $type);
    }
}
