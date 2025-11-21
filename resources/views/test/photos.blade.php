<!DOCTYPE html>
<html>
<head>
    <title>Test Photos</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .detenteur { 
            border: 1px solid #ddd; 
            padding: 15px; 
            margin-bottom: 20px; 
            border-radius: 5px;
        }
        .photo { 
            max-width: 200px; 
            max-height: 200px; 
            margin: 10px 0;
            border: 1px solid #ccc;
        }
        .file-list { 
            margin-top: 30px; 
            padding-top: 20px; 
            border-top: 2px solid #eee;
        }
        .file-item { 
            padding: 5px; 
            margin: 5px 0; 
            background: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Test des photos des détenteurs</h1>
        
        <h2>Détenteurs</h2>
        @foreach($detenteurs as $detenteur)
            <div class="detenteur">
                <h3>Détenteur #{{ $detenteur->id_detenteur }}</h3>
                <p><strong>Type:</strong> {{ $detenteur->type_detenteur }}</p>
                <p><strong>Photo path:</strong> {{ $detenteur->photo ?? 'Aucune photo' }}</p>
                <p><strong>Photo URL:</strong> {{ $detenteur->photo_url }}</p>
                
                @if($detenteur->photo)
                    <div>
                        <h4>Image chargée :</h4>
                        <img src="{{ $detenteur->photo_url }}" class="photo" 
                             onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.png') }}'"
                             alt="Photo du détenteur">
                    </div>
                @endif
            </div>
        @endforeach

        <div class="file-list">
            <h2>Fichiers dans le dossier de stockage :</h2>
            @if(count($files) > 0)
                @foreach($files as $file)
                    <div class="file-item">
                        {{ $file }}
                        <br>
                        <img src="{{ asset(str_replace('public/', 'storage/', $file)) }}" 
                             style="max-width: 100px; max-height: 100px;"
                             onerror="this.style.display='none'"
                             alt="Aperçu">
                    </div>
                @endforeach
            @else
                <p>Aucun fichier trouvé dans le dossier de stockage.</p>
            @endif
        </div>
    </div>
</body>
</html>
