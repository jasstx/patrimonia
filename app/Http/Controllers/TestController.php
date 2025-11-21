<?php

namespace App\Http\Controllers;

use App\Models\Detenteur;
use Illuminate\Support\Facades\Storage;

class TestController extends Controller
{
    public function testPhotos()
    {
        $detenteurs = Detenteur::all();
        $files = Storage::files('public/photos/demandes');
        
        return view('test.photos', [
            'detenteurs' => $detenteurs,
            'files' => $files
        ]);
    }
}
