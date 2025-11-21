<?php

namespace App\Http\Controllers;

use App\Models\PieceJointe;
use Illuminate\Support\Facades\Storage;

class PieceJointeController extends Controller
{
    public function download(PieceJointe $piece)
    {
        if (!$piece->chemin || !Storage::disk('public')->exists($piece->chemin)) {
            abort(404, 'Fichier introuvable');
        }

        return Storage::disk('public')->download($piece->chemin, $piece->nom_fichier);
    }
}













