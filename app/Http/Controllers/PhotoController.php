<?php

namespace App\Http\Controllers;

use App\Models\Detenteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PhotoController extends Controller
{
    /**
     * Télécharge une photo pour un détenteur
     */
    public function upload(Request $request, $id_detenteur)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $detenteur = Detenteur::findOrFail($id_detenteur);
        
        // Supprimer l'ancienne photo si elle existe
        if ($detenteur->photo) {
            Storage::disk('public')->delete($detenteur->photo);
        }

        // Enregistrer la nouvelle photo
        $path = $request->file('photo')->store('photos', 'public');
        
        // Mettre à jour le chemin de la photo dans la base de données
        $detenteur->update(['photo' => $path]);

        return response()->json([
            'success' => true,
            'message' => 'Photo téléchargée avec succès',
            'photo_url' => Storage::url($path)
        ]);
    }

    /**
     * Affiche la photo d'un détenteur
     */
    public function show($filename)
    {
        $path = 'photos/' . $filename;
        
        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }

        $file = Storage::disk('public')->get($path);
        $mime = mime_content_type(storage_path('app/public/' . $path));

        return response($file, 200)->header('Content-Type', $mime);
    }
}
