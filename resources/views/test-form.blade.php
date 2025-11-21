<!DOCTYPE html>
<html>
<head>
    <title>Test Formulaire</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Test Formulaire Simple</h1>

    <form action="{{ route('demande.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <h2>Informations de base</h2>
        <p>
            <label>Type détenteur:</label><br>
            <input type="radio" name="type_detenteur" value="individu" checked> Individu<br>
            <input type="radio" name="type_detenteur" value="famille"> Famille<br>
            <input type="radio" name="type_detenteur" value="communaute"> Communauté<br>
            <input type="radio" name="type_detenteur" value="autre"> Autre<br>
        </p>

        <p>
            <label>Nom:</label><br>
            <input type="text" name="nom" value="Test" required>
        </p>

        <p>
            <label>Prénom:</label><br>
            <input type="text" name="prenom" value="Formulaire" required>
        </p>

        <p>
            <label>Téléphone:</label><br>
            <input type="text" name="telephone" value="123456789" required>
        </p>

        <p>
            <label>Sexe:</label><br>
            <select name="sexe" required>
                <option value="">Sélectionnez</option>
                <option value="M" selected>Masculin</option>
                <option value="F">Féminin</option>
            </select>
        </p>

        <p>
            <label>Province:</label><br>
            <select name="province" required>
                <option value="">Sélectionnez</option>
                <option value="Houet" selected>Houet</option>
            </select>
        </p>

        <p>
            <label>Commune:</label><br>
            <select name="commune" required>
                <option value="">Sélectionnez</option>
                <option value="Bobo-Dioulasso" selected>Bobo-Dioulasso</option>
            </select>
        </p>

        <h2>Éléments patrimoniaux</h2>
        <p>
            <input type="checkbox" name="elements_patrimoine[]" value="1" checked> Test Patrimoine 1<br>
            <input type="checkbox" name="elements_patrimoine[]" value="2"> Test Patrimoine 2<br>
        </p>

        <h2>Déclaration</h2>
        <p>
            <input type="checkbox" name="declaration" value="1" checked required>
            J'accepte la déclaration sur l'honneur
        </p>

        <p>
            <label>Signature:</label><br>
            <input type="text" name="signature" value="Test Signature" required>
        </p>

        <p>
            <button type="submit">Soumettre le test</button>
        </p>
    </form>

    <hr>
    <h2>Debug Info</h2>
    <p><strong>Utilisateur connecté:</strong> {{ Auth::check() ? Auth::user()->name : 'Non connecté' }}</p>
    <p><strong>Route:</strong> {{ route('demande.store') }}</p>
    <p><strong>CSRF Token:</strong> {{ csrf_token() }}</p>
</body>
</html>
