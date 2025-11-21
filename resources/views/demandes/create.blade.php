@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    :root { --primary:#FF0000; --secondary:#00AA00; --success:#10b981; --warning:#f59e0b; --dark:#0f172a; --light:#f8fafc; --gray:#64748b; }
    .page-bg { background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); min-height:100vh; padding:2rem 1rem; }
    .container-max { max-width:1000px; margin:0 auto; }
    .form-header { background: linear-gradient(135deg, var(--primary), var(--secondary)); color:#fff; padding:3rem 2rem; border-radius:20px 20px 0 0; text-align:center; box-shadow:0 4px 20px rgba(59,130,246,.3); }
    .form-header h1 { font-size:2rem; font-weight:800; margin-bottom:.5rem; }
    .form-header p { font-size:1.1rem; opacity:.95; }
    .progress-container { background:#fff; padding:2rem; display:flex; justify-content:space-between; align-items:center; position:relative; }
    .progress-step { flex:1; text-align:center; position:relative; }
    .progress-step::before { content:''; position:absolute; top:20px; left:50%; width:100%; height:3px; background:#e2e8f0; z-index:0; }
    .progress-step:first-child::before { left:50%; width:50%; }
    .progress-step:last-child::before { width:50%; }
    .step-circle { width:40px; height:40px; border-radius:50%; background:#fff; border:3px solid #e2e8f0; display:flex; align-items:center; justify-content:center; margin:0 auto .5rem; position:relative; z-index:1; font-weight:700; color:var(--gray); transition:all .3s ease; }
    .progress-step.active .step-circle { background:var(--primary); border-color:var(--primary); color:#fff; transform:scale(1.1); }
    .progress-step.completed .step-circle { background:var(--success); border-color:var(--success); color:#fff; }
    .step-label { font-size:.875rem; color:var(--gray); font-weight:500; }
    .progress-step.active .step-label { color:var(--primary); font-weight:700; }
    .form-body { background:#fff; padding:3rem 2rem; box-shadow:0 4px 20px rgba(0,0,0,.05); }
    .form-section { margin-bottom:3rem; padding-bottom:2rem; border-bottom:2px solid #f1f5f9; }
    .form-section:last-child { border-bottom:none; margin-bottom:0; }
    .section-header { display:flex; align-items:center; gap:1rem; margin-bottom:2rem; }
    .section-icon { width:50px; height:50px; background:linear-gradient(135deg, var(--primary), var(--secondary)); border-radius:12px; display:flex; align-items:center; justify-content:center; color:#fff; font-size:1.5rem; }
    .section-header h3 { font-size:1.5rem; font-weight:700; color:var(--dark); }
    .radio-cards { display:grid; grid-template-columns:repeat(auto-fit, minmax(200px,1fr)); gap:1rem; margin-bottom:2rem; }
    .radio-card { position:relative; }
    .radio-card input[type="radio"] { display:none; }
    .radio-card label { display:flex; align-items:center; gap:.75rem; padding:1.25rem; border:2px solid #e2e8f0; border-radius:12px; cursor:pointer; transition:all .3s ease; background:#fff; }
    .radio-card label:hover { border-color:var(--primary); background:#f8fafc; }
    .radio-card input[type="radio"]:checked + label { border-color:var(--primary); background:linear-gradient(135deg, rgba(59,130,246,.1), rgba(249,115,22,.05)); box-shadow:0 4px 15px rgba(59,130,246,.2); }
    .radio-icon { width:30px; height:30px; border-radius:50%; border:2px solid #cbd5e0; position:relative; flex-shrink:0; }
    .radio-card input[type="radio"]:checked + label .radio-icon { border-color:var(--primary); background:var(--primary); }
    .radio-card input[type="radio"]:checked + label .radio-icon::after { content:'✓'; position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); color:#fff; font-weight:700; font-size:.875rem; }
    .radio-label { font-weight:600; color:var(--dark); }
    .form-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(250px,1fr)); gap:1.5rem; }
    .form-group { margin-bottom:1.5rem; }
    .form-group label { display:block; font-weight:600; color:var(--dark); margin-bottom:.5rem; font-size:.95rem; }
    .form-group label .required { color:var(--secondary); margin-left:.25rem; }
    .form-control { width:100%; padding:.875rem 1rem; border:2px solid #e2e8f0; border-radius:10px; font-size:.95rem; transition:all .3s ease; }
    .form-control:focus { outline:none; border-color:var(--primary); box-shadow:0 0 0 3px rgba(59,130,246,.1); }
    textarea.form-control { resize:vertical; min-height:100px; }
    .info-box { background:linear-gradient(135deg, rgba(59,130,246,.05), rgba(249,115,22,.05)); border-left:4px solid var(--primary); padding:1.25rem; border-radius:8px; margin-bottom:1.5rem; }
    .info-box p { color:var(--gray); font-size:.9rem; line-height:1.6; }
    .file-upload { border:2px dashed #cbd5e0; border-radius:12px; padding:2rem; text-align:center; background:#f8fafc; transition:all .3s ease; cursor:pointer; }
    .file-upload:hover { border-color:var(--primary); background:rgba(59,130,246,.05); }
    .file-upload input[type="file"] { display:none; }
    .file-upload-icon { font-size:3rem; color:var(--primary); margin-bottom:1rem; }
    .file-upload-text { color:var(--gray); font-size:.9rem; }
    .multi-select { border:2px solid #e2e8f0; border-radius:12px; padding:1rem; max-height:400px; overflow-y:auto; }
    .multi-select-group { margin-bottom:1.5rem; }
    .multi-select-group-title { font-weight:700; color:var(--dark); padding:.5rem .75rem; background:linear-gradient(135deg, #f8fafc, #e2e8f0); border-radius:8px; margin-bottom:.75rem; font-size:.95rem; }
    .multi-select-item { display:flex; align-items:center; padding:.75rem; border-radius:8px; cursor:pointer; transition:all .2s ease; margin-bottom:.5rem; }
    .multi-select-item:hover { background:#f8fafc; }
    .multi-select-item input[type="checkbox"] { width:20px; height:20px; margin-right:.75rem; cursor:pointer; accent-color:var(--primary); }
    .multi-select-item label { cursor:pointer; font-size:.95rem; color:var(--gray); margin:0; }
    .multi-select-item input[type="checkbox"]:checked + label { color:var(--dark); font-weight:600; }
    .declaration-box { background:linear-gradient(135deg, rgba(245,158,11,.05), rgba(249,115,22,.05)); border:2px solid var(--warning); border-radius:12px; padding:2rem; }
    .declaration-checkbox { display:flex; gap:1rem; align-items:start; margin-bottom:1.5rem; }
    .declaration-checkbox input[type="checkbox"] { width:24px; height:24px; margin-top:.25rem; cursor:pointer; accent-color:var(--primary); flex-shrink:0; }
    .declaration-text { flex:1; }
    .declaration-text strong { display:block; color:var(--dark); margin-bottom:.5rem; font-size:1.05rem; }
    .declaration-text p { color:var(--gray); font-size:.9rem; line-height:1.6; }
    .form-actions { display:flex; gap:1rem; justify-content:flex-end; padding-top:2rem; border-top:2px solid #f1f5f9; }
    .btn { padding:1rem 2.5rem; border-radius:12px; font-weight:700; font-size:1rem; cursor:pointer; transition:all .3s ease; border:none; display:inline-flex; align-items:center; gap:.5rem; }
    .btn-primary { background:linear-gradient(135deg, var(--primary), var(--secondary)); color:#fff; box-shadow:0 4px 15px rgba(59,130,246,.3); }
    .btn-primary:hover { transform:translateY(-2px); box-shadow:0 6px 25px rgba(59,130,246,.4); }
    .btn-secondary { background:#fff; color:var(--gray); border:2px solid #e2e8f0; }
    .btn-secondary:hover { background:#f8fafc; border-color:var(--gray); }
    .hidden { display:none !important; }
    .step-content:not(.hidden) { display:block !important; }
    .error-message { color:#dc2626; font-size:.85rem; margin-top:.25rem; }
    .form-control.error { border-color:#dc2626; }
    .step-content { transition: all .3s ease; }
    .form-type { border: 2px solid #e2e8f0; border-radius: 8px; padding: 20px; margin: 15px 0; background-color: #f7fafc; }
    .documents-type { margin-top: 1.5rem; }
    .help-text { display: block; margin-top: 0.5rem; color: var(--gray); font-size: 0.85rem; line-height: 1.5; font-style: italic; }
    .file-upload.has-file { border-color: var(--success); background: rgba(16,185,129,.05); }
    .file-upload.has-file .file-upload-text strong { color: var(--success); }
    .file-name-display { margin-top: 0.5rem; padding: 0.5rem; background: #f8fafc; border-radius: 6px; font-size: 0.875rem; color: var(--dark); }
    @media (max-width:768px){ .page-bg{ padding:1rem .5rem; } .form-header{ padding:2rem 1.5rem; } .form-header h1{ font-size:1.5rem; } .form-body{ padding:2rem 1.5rem; } .radio-cards{ grid-template-columns:1fr; } .form-grid{ grid-template-columns:1fr; } .form-actions{ flex-direction:column; } .btn{ width:100%; justify-content:center; } .progress-container{ padding:1.5rem 1rem; } .step-label{ font-size:.75rem; } .step-circle{ width:35px; height:35px; font-size:.875rem; } }
</style>
@endpush

@section('content')
<div class="page-bg">
    <div class="container-max">
        <div class="form-header">
            <h1><i class="fas fa-file-alt"></i> Formulaire de Demande d'Inscription</h1>
            <p>Répertoire des Détenteurs du Patrimoine Culturel du Burkina Faso</p>
        </div>

        <div class="progress-container">
            <div class="progress-step active" data-step="1"><div class="step-circle">1</div><div class="step-label">Identification</div></div>
            <div class="progress-step" data-step="2"><div class="step-circle">2</div><div class="step-label">Patrimoine</div></div>
            <div class="progress-step" data-step="3"><div class="step-circle">3</div><div class="step-label">Déclaration</div></div>
            <div class="progress-step" data-step="4"><div class="step-circle">4</div><div class="step-label">Documents</div></div>
        </div>

        <form class="form-body" id="mainForm" action="{{ route('demande.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf

            <!-- ÉTAPE 1 - IDENTIFICATION -->
            <div class="form-section step-content" data-step="1">
                <div class="section-header">
                    <div class="section-icon"><i class="fas fa-user"></i></div>
                    <h3>1. Identification du Détenteur</h3>
                </div>

                <div class="form-group">
                    <label>Type de détenteur <span class="required">*</span></label>
                    <div class="radio-cards" id="type-detenteur-group">
                        <div class="radio-card">
                            <input type="radio" name="type_detenteur" id="type_individu" value="individu" {{ old('type_detenteur', 'individu') == 'individu' ? 'checked' : '' }}>
                            <label for="type_individu"><div class="radio-icon"></div><span class="radio-label"><i class="fas fa-user"></i> Individu</span></label>
                        </div>
                        <div class="radio-card">
                            <input type="radio" name="type_detenteur" id="type_famille" value="famille" {{ old('type_detenteur') == 'famille' ? 'checked' : '' }}>
                            <label for="type_famille"><div class="radio-icon"></div><span class="radio-label"><i class="fas fa-users"></i> Famille</span></label>
                        </div>
                        <div class="radio-card">
                            <input type="radio" name="type_detenteur" id="type_communaute" value="communaute" {{ old('type_detenteur') == 'communaute' ? 'checked' : '' }}>
                            <label for="type_communaute"><div class="radio-icon"></div><span class="radio-label"><i class="fas fa-people-group"></i> Communauté</span></label>
                        </div>
                        <div class="radio-card">
                            <input type="radio" name="type_detenteur" id="type_autre" value="autre" {{ old('type_detenteur') == 'autre' ? 'checked' : '' }}>
                            <label for="type_autre"><div class="radio-icon"></div><span class="radio-label"><i class="fas fa-ellipsis"></i> Autre</span></label>
                        </div>
                    </div>
                </div>

                <div id="autre-field" class="hidden">
                    <div class="form-group">
                        <label>Préciser le type de détenteur</label>
                        <input type="text" name="autre_type_detenteur" class="form-control">
                    </div>
                </div>

                <!-- Formulaire Individu -->
                <div id="form-individu" class="form-type hidden">
                    <div class="info-box">
                        <p><i class="fas fa-info-circle"></i> Veuillez remplir vos informations personnelles ci-dessous.</p>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Nom <span class="required">*</span></label>
                            <input type="text" name="nom" class="form-control" value="{{ old('nom') }}">
                            @error('nom')<div class="error-message">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label>Prénom <span class="required">*</span></label>
                            <input type="text" name="prenom" class="form-control" value="{{ old('prenom') }}">
                            @error('prenom')<div class="error-message">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label>Date de naissance</label>
                            <input type="date" name="date_naiss" class="form-control" value="{{ old('date_naiss') }}">
                        </div>
                        <div class="form-group">
                            <label>Lieu de naissance</label>
                            <input type="text" name="lieu_naissance" class="form-control" value="{{ old('lieu_naissance') }}">
                        </div>
                        <div class="form-group">
                            <label>Sexe <span class="required">*</span></label>
                            <select name="sexe" class="form-control">
                                <option value="">Sélectionnez</option>
                                <option value="M" {{ old('sexe') == 'M' ? 'selected' : '' }}>Masculin</option>
                                <option value="F" {{ old('sexe') == 'F' ? 'selected' : '' }}>Féminin</option>
                            </select>
                            @error('sexe')<div class="error-message">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label>Groupe ethnoculturel</label>
                            <input type="text" name="groupe_etheroculturel" class="form-control" value="{{ old('groupe_etheroculturel') }}">
                        </div>
                    </div>
                </div>

                <!-- Formulaire Famille -->
                <div id="form-famille" class="form-type hidden">
                    <div class="info-box">
                        <p><i class="fas fa-info-circle"></i> Informations sur la famille détentrice du patrimoine.</p>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Nom de la famille <span class="required">*</span></label>
                            <input type="text" name="nom_structure" class="form-control" data-type="famille" value="{{ old('nom_structure') }}">
                            @error('nom_structure')<div class="error-message">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label>Type de famille <span class="required">*</span></label>
                            <select name="type_structure" class="form-control" data-type="famille">
                                <option value="">Sélectionnez</option>
                                <option value="famille_nucleaire" {{ old('type_structure') == 'famille_nucleaire' ? 'selected' : '' }}>Famille nucléaire</option>
                                <option value="famille_etendue" {{ old('type_structure') == 'famille_etendue' ? 'selected' : '' }}>Famille étendue</option>
                                <option value="lignage" {{ old('type_structure') == 'lignage' ? 'selected' : '' }}>Lignage</option>
                            </select>
                            @error('type_structure')<div class="error-message">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label>Siège social</label>
                            <input type="text" name="siege_social" class="form-control" value="{{ old('siege_social') }}">
                        </div>
                        <div class="form-group">
                            <label>Personne de contact</label>
                            <input type="text" name="personne_contact" class="form-control" value="{{ old('personne_contact') }}">
                        </div>
                    </div>
                </div>

                <!-- Formulaire Communauté -->
                <div id="form-communaute" class="form-type hidden">
                    <div class="info-box">
                        <p><i class="fas fa-info-circle"></i> Informations sur la communauté ou l'organisation.</p>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Nom de la communauté <span class="required">*</span></label>
                            <input type="text" name="nom_structure" class="form-control" data-type="communaute" value="{{ old('nom_structure') }}">
                            @error('nom_structure')<div class="error-message">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label>Type de communauté <span class="required">*</span></label>
                            <select name="type_structure" class="form-control" data-type="communaute">
                                <option value="">Sélectionnez</option>
                                <option value="association" {{ old('type_structure') == 'association' ? 'selected' : '' }}>Association</option>
                                <option value="organisation" {{ old('type_structure') == 'organisation' ? 'selected' : '' }}>Organisation</option>
                                <option value="groupe_communautaire" {{ old('type_structure') == 'groupe_communautaire' ? 'selected' : '' }}>Groupe communautaire</option>
                                <option value="cooperative" {{ old('type_structure') == 'cooperative' ? 'selected' : '' }}>Coopérative</option>
                            </select>
                            @error('type_structure')<div class="error-message">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label>Siège social</label>
                            <input type="text" name="siege_social" class="form-control" value="{{ old('siege_social') }}">
                        </div>
                        <div class="form-group">
                            <label>Personne de contact</label>
                            <input type="text" name="personne_contact" class="form-control" value="{{ old('personne_contact') }}">
                        </div>
                    </div>
                </div>

                <!-- Informations de Contact -->
                <div style="margin-top: 2rem;">
                    <h4 style="font-size: 1.2rem; font-weight: 700; color: var(--dark); margin-bottom: 1.5rem;">
                        <i class="fas fa-address-card"></i> Informations de Contact
                    </h4>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Téléphone <span class="required">*</span></label>
                            <input type="text" name="telephone" class="form-control" required value="{{ old('telephone') }}">
                            @error('telephone')<div class="error-message">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                            @error('email')<div class="error-message">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label>Province <span class="required">*</span></label>
                            <select name="province" id="province" class="form-control" required>
                                <option value="">Sélectionnez une province</option>
                                <option value="Koosin (Kossi)" {{ old('province') == 'Koosin (Kossi)' ? 'selected' : '' }}>Koosin (Kossi)</option>
                                <option value="Nayala" {{ old('province') == 'Nayala' ? 'selected' : '' }}>Nayala</option>
                                <option value="Sourou" {{ old('province') == 'Sourou' ? 'selected' : '' }}>Sourou</option>
                                <option value="Mouhoun" {{ old('province') == 'Mouhoun' ? 'selected' : '' }}>Mouhoun</option>
                                <option value="Balé" {{ old('province') == 'Balé' ? 'selected' : '' }}>Balé</option>
                                <option value="Barnwa" {{ old('province') == 'Barnwa' ? 'selected' : '' }}>Barnwa</option>
                                <option value="Houet" {{ old('province') == 'Houet' ? 'selected' : '' }}>Houet</option>
                                <option value="Kénédougou" {{ old('province') == 'Kénédougou' ? 'selected' : '' }}>Kénédougou</option>
                                <option value="Tuy" {{ old('province') == 'Tuy' ? 'selected' : '' }}>Tuy</option>
                                <option value="Comoé" {{ old('province') == 'Comoé' ? 'selected' : '' }}>Comoé</option>
                                <option value="Léraba" {{ old('province') == 'Léraba' ? 'selected' : '' }}>Léraba</option>
                                <option value="Bougouriba" {{ old('province') == 'Bougouriba' ? 'selected' : '' }}>Bougouriba</option>
                                <option value="Ioba" {{ old('province') == 'Ioba' ? 'selected' : '' }}>Ioba</option>
                                <option value="Noumbiel" {{ old('province') == 'Noumbiel' ? 'selected' : '' }}>Noumbiel</option>
                                <option value="Poni" {{ old('province') == 'Poni' ? 'selected' : '' }}>Poni</option>
                                <option value="Loroum" {{ old('province') == 'Loroum' ? 'selected' : '' }}>Loroum</option>
                                <option value="Passoré" {{ old('province') == 'Passoré' ? 'selected' : '' }}>Passoré</option>
                                <option value="Yatenga" {{ old('province') == 'Yatenga' ? 'selected' : '' }}>Yatenga</option>
                                <option value="Zondoma" {{ old('province') == 'Zondoma' ? 'selected' : '' }}>Zondoma</option>
                                <option value="Boukklemde" {{ old('province') == 'Boukklemde' ? 'selected' : '' }}>Boukklemde</option>
                                <option value="Sanguié" {{ old('province') == 'Sanguié' ? 'selected' : '' }}>Sanguié</option>
                                <option value="Sissili" {{ old('province') == 'Sissili' ? 'selected' : '' }}>Sissili</option>
                                <option value="Ziro" {{ old('province') == 'Ziro' ? 'selected' : '' }}>Ziro</option>
                                <option value="Djelgodji" {{ old('province') == 'Djelgodji' ? 'selected' : '' }}>Djelgodji</option>
                                <option value="Karo-Peli" {{ old('province') == 'Karo-Peli' ? 'selected' : '' }}>Karo-Peli</option>
                                <option value="Bam" {{ old('province') == 'Bam' ? 'selected' : '' }}>Bam</option>
                                <option value="Namentenga" {{ old('province') == 'Namentenga' ? 'selected' : '' }}>Namentenga</option>
                                <option value="Sandbondtenga" {{ old('province') == 'Sandbondtenga' ? 'selected' : '' }}>Sandbondtenga</option>
                                <option value="Bassitenga" {{ old('province') == 'Bassitenga' ? 'selected' : '' }}>Bassitenga</option>
                                <option value="Ganzourgou" {{ old('province') == 'Ganzourgou' ? 'selected' : '' }}>Ganzourgou</option>
                                <option value="Kourwéogo" {{ old('province') == 'Kourwéogo' ? 'selected' : '' }}>Kourwéogo</option>
                                <option value="Kadlogo" {{ old('province') == 'Kadlogo' ? 'selected' : '' }}>Kadlogo</option>
                                <option value="Bazèga" {{ old('province') == 'Bazèga' ? 'selected' : '' }}>Bazèga</option>
                                <option value="Nahouri" {{ old('province') == 'Nahouri' ? 'selected' : '' }}>Nahouri</option>
                                <option value="Zoundwéogo" {{ old('province') == 'Zoundwéogo' ? 'selected' : '' }}>Zoundwéogo</option>
                                <option value="Boulgou" {{ old('province') == 'Boulgou' ? 'selected' : '' }}>Boulgou</option>
                                <option value="Koulpelogo" {{ old('province') == 'Koulpelogo' ? 'selected' : '' }}>Koulpelogo</option>
                                <option value="Kourittenga" {{ old('province') == 'Kourittenga' ? 'selected' : '' }}>Kourittenga</option>
                                <option value="Oudalan" {{ old('province') == 'Oudalan' ? 'selected' : '' }}>Oudalan</option>
                                <option value="Séno" {{ old('province') == 'Séno' ? 'selected' : '' }}>Séno</option>
                                <option value="Yagha" {{ old('province') == 'Yagha' ? 'selected' : '' }}>Yagha</option>
                                <option value="Gourma" {{ old('province') == 'Gourma' ? 'selected' : '' }}>Gourma</option>
                                <option value="Kompienga" {{ old('province') == 'Kompienga' ? 'selected' : '' }}>Kompienga</option>
                                <option value="Dyamongou" {{ old('province') == 'Dyamongou' ? 'selected' : '' }}>Dyamongou</option>
                                <option value="Gobnangou" {{ old('province') == 'Gobnangou' ? 'selected' : '' }}>Gobnangou</option>
                                <option value="Gnagna" {{ old('province') == 'Gnagna' ? 'selected' : '' }}>Gnagna</option>
                                <option value="Komondjari" {{ old('province') == 'Komondjari' ? 'selected' : '' }}>Komondjari</option>
                            </select>
                            @error('province')<div class="error-message">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label>Chefs-lieux de la province <span class="required">*</span></label>
                            <select name="commune" id="commune" class="form-control" required disabled>
                                <option value="">Sélectionnez d'abord une province</option>
                            </select>
                            @error('commune')<div class="error-message">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label>Profession</label>
                            <input type="text" name="profession" class="form-control" value="{{ old('profession') }}">
                        </div>
                        <div class="form-group">
                            <label>Coordonnées géographiques</label>
                            <input type="text" name="coordonnees_geographiques" placeholder="ex: 12.3456, -1.2345" class="form-control" value="{{ old('coordonnees_geographiques') }}">
                        </div>
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label>Adresse</label>
                            <textarea name="adresse" class="form-control" rows="2">{{ old('adresse') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Photo -->
                <div class="form-group" style="margin-top: 2rem;">
                    <label>Photo du détenteur</label>
                    <label for="photo-upload" class="file-upload">
                        <div class="file-upload-icon"><i class="fas fa-camera"></i></div>
                        <div class="file-upload-text">
                            <strong>Cliquez pour télécharger une photo</strong><br>
                            <span>JPG, PNG (max: 2MB)</span>
                        </div>
                        <input type="file" id="photo-upload" name="photo" accept="image/*">
                    </label>
                </div>
            </div>

            <!-- ÉTAPE 2 - PATRIMOINE -->
            <div class="form-section step-content hidden" data-step="2">
                <div class="section-header">
                    <div class="section-icon"><i class="fas fa-landmark"></i></div>
                    <h3>2. Éléments du Patrimoine</h3>
                </div>
                <div class="info-box">
                    <p><i class="fas fa-info-circle"></i> Sélectionnez un ou plusieurs éléments patrimoniaux dont vous êtes détenteur. Cochez toutes les cases correspondantes.</p>
                </div>

                <div class="multi-select">
                    @foreach($domaines as $titre => $patrimoines)
                        <div class="multi-select-group">
                            <div class="multi-select-group-title">{{ $titre }}</div>
                            @foreach($patrimoines as $p)
                                <div class="multi-select-item">
                                    <input type="checkbox" id="pat_{{ $p->id_element }}" name="elements_patrimoine[]" value="{{ $p->id_element }}" {{ in_array($p->id_element, old('elements_patrimoine', [])) ? 'checked' : '' }}>
                                    <label for="pat_{{ $p->id_element }}">{{ $p->domaine }}-{{ $p->numero_element }} - {{ $p->nom }}</label>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
                @error('elements_patrimoine')
                    <div class="mt-2" style="color:#dc2626; font-size:.9rem;">{{ $message }}</div>
                @enderror
            </div>

            <!-- ÉTAPE 3 - DÉCLARATION -->
            <div class="form-section step-content hidden" data-step="3">
                <div class="section-header">
                    <div class="section-icon"><i class="fas fa-clipboard-check"></i></div>
                    <h3>3. Déclaration sur l'honneur</h3>
                </div>
                <div class="declaration-box">
                    <div class="declaration-checkbox">
                        <input type="checkbox" name="declaration" value="1" id="decl" {{ old('declaration') ? 'checked' : '' }} required>
                        <div class="declaration-text">
                            <strong>Je déclare sur l'honneur que toutes les informations fournies sont exactes et complètes.</strong>
                            <p>Je m'engage à informer immédiatement les autorités compétentes de tout changement concernant les informations fournies dans le présent formulaire.</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Signature (nom complet) <span class="required">*</span></label>
                        <input type="text" name="signature" placeholder="Votre nom complet" class="form-control" required value="{{ old('signature') }}">
                        @error('signature')
                            <div class="mt-1" style="color:#dc2626; font-size:.9rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- ÉTAPE 4 - DOCUMENTS -->
            <div class="form-section step-content hidden" data-step="4">
                <div class="section-header">
                    <div class="section-icon"><i class="fas fa-paperclip"></i></div>
                    <h3>4. Documents à fournir</h3>
                </div>
                <div class="info-box">
                    <p><i class="fas fa-info-circle"></i> Les documents à fournir varient selon votre type de détenteur. Formats acceptés: PDF, JPG, PNG. Taille maximale par fichier: 5MB.</p>
                </div>

                <!-- Documents pour INDIVIDU -->
                <div id="documents-individu" class="documents-type hidden">
                    <h4 style="font-size: 1.2rem; font-weight: 700; color: var(--dark); margin: 2rem 0 1.5rem 0; padding-bottom: 0.5rem; border-bottom: 2px solid var(--primary);">
                        <i class="fas fa-user"></i> Documents requis pour un Individu
                    </h4>
                    
                    <div class="form-group">
                        <label>Copie CNIB / Passeport <span class="required">*</span></label>
                        <label for="cnib-individu" class="file-upload">
                            <div class="file-upload-icon"><i class="fas fa-id-card"></i></div>
                            <div class="file-upload-text">
                                <strong>Cliquez pour télécharger votre CNIB ou Passeport</strong><br>
                                <span>PDF, JPG, PNG (max: 5MB)</span>
                            </div>
                            <input type="file" id="cnib-individu" name="cnib_individu" accept=".pdf,.jpg,.jpeg,.png" data-required="individu">
                        </label>
                        <small class="help-text">Télécharger une copie claire de votre CNIB ou Passeport en cours de validité.</small>
                        <div class="error-message" id="error-cnib-individu" style="display:none;"></div>
                    </div>

                    <div class="form-group">
                        <label>Photo d'identité récente <span class="required">*</span></label>
                        <label for="photo-identite-individu" class="file-upload">
                            <div class="file-upload-icon"><i class="fas fa-camera"></i></div>
                            <div class="file-upload-text">
                                <strong>Cliquez pour télécharger votre photo d'identité</strong><br>
                                <span>JPG, PNG (max: 5MB)</span>
                            </div>
                            <input type="file" id="photo-identite-individu" name="photo_identite_individu" accept=".jpg,.jpeg,.png" data-required="individu">
                        </label>
                        <small class="help-text">Télécharger une photo d'identité récente (moins de 6 mois) sur fond clair.</small>
                        <div class="error-message" id="error-photo-identite-individu" style="display:none;"></div>
                    </div>

                    <div class="form-group">
                        <label>Description de l'élément culturel <span class="required">*</span></label>
                        <textarea name="description_element_individu" id="description-element-individu" class="form-control" rows="5" placeholder="Décrivez en détail l'élément culturel dont vous êtes détenteur..." data-required="individu">{{ old('description_element_individu') }}</textarea>
                        <small class="help-text">Fournissez une description détaillée de l'élément culturel dont vous êtes détenteur (origine, transmission, usage, etc.).</small>
                        <div class="error-message" id="error-description-individu" style="display:none;"></div>
                    </div>
                </div>

                <!-- Documents pour FAMILLE -->
                <div id="documents-famille" class="documents-type hidden">
                    <h4 style="font-size: 1.2rem; font-weight: 700; color: var(--dark); margin: 2rem 0 1.5rem 0; padding-bottom: 0.5rem; border-bottom: 2px solid var(--primary);">
                        <i class="fas fa-users"></i> Documents requis pour une Famille
                    </h4>
                    
                    <div class="form-group">
                        <label>CNIB du représentant de la famille <span class="required">*</span></label>
                        <label for="cnib-famille" class="file-upload">
                            <div class="file-upload-icon"><i class="fas fa-id-card"></i></div>
                            <div class="file-upload-text">
                                <strong>Cliquez pour télécharger la CNIB du représentant</strong><br>
                                <span>PDF, JPG, PNG (max: 5MB)</span>
                            </div>
                            <input type="file" id="cnib-famille" name="cnib_famille" accept=".pdf,.jpg,.jpeg,.png" data-required="famille">
                        </label>
                        <small class="help-text">Télécharger la CNIB du représentant légal de la famille (chef de famille ou aîné).</small>
                        <div class="error-message" id="error-cnib-famille" style="display:none;"></div>
                    </div>

                    <div class="form-group">
                        <label>Attestation coutumière / Chefferie <span class="required">*</span></label>
                        <label for="attestation-famille" class="file-upload">
                            <div class="file-upload-icon"><i class="fas fa-certificate"></i></div>
                            <div class="file-upload-text">
                                <strong>Cliquez pour télécharger l'attestation</strong><br>
                                <span>PDF, JPG, PNG (max: 5MB)</span>
                            </div>
                            <input type="file" id="attestation-famille" name="attestation_famille" accept=".pdf,.jpg,.jpeg,.png" data-required="famille">
                        </label>
                        <small class="help-text">Télécharger l'attestation délivrée par la chefferie ou l'autorité coutumière reconnaissant la famille comme détentrice du savoir.</small>
                        <div class="error-message" id="error-attestation-famille" style="display:none;"></div>
                    </div>

                    <div class="form-group">
                        <label>Description du savoir détenu <span class="required">*</span></label>
                        <textarea name="description_savoir_famille" id="description-savoir-famille" class="form-control" rows="5" placeholder="Décrivez en détail le savoir traditionnel détenu par votre famille..." data-required="famille">{{ old('description_savoir_famille') }}</textarea>
                        <small class="help-text">Fournissez une description détaillée du savoir traditionnel détenu par votre famille (transmission, usage, importance culturelle, etc.).</small>
                        <div class="error-message" id="error-description-famille" style="display:none;"></div>
                    </div>

                    <div class="form-group">
                        <label>Photo de groupe familial <span class="required">*</span></label>
                        <label for="photo-groupe-famille" class="file-upload">
                            <div class="file-upload-icon"><i class="fas fa-users"></i></div>
                            <div class="file-upload-text">
                                <strong>Cliquez pour télécharger la photo de groupe</strong><br>
                                <span>JPG, PNG (max: 5MB)</span>
                            </div>
                            <input type="file" id="photo-groupe-famille" name="photo_groupe_famille" accept=".jpg,.jpeg,.png" data-required="famille">
                        </label>
                        <small class="help-text">Télécharger une photo récente du groupe familial représentant les détenteurs du savoir.</small>
                        <div class="error-message" id="error-photo-groupe-famille" style="display:none;"></div>
                    </div>
                </div>

                <!-- Documents pour COMMUNAUTÉ -->
                <div id="documents-communaute" class="documents-type hidden">
                    <h4 style="font-size: 1.2rem; font-weight: 700; color: var(--dark); margin: 2rem 0 1.5rem 0; padding-bottom: 0.5rem; border-bottom: 2px solid var(--primary);">
                        <i class="fas fa-people-group"></i> Documents requis pour une Communauté / Association / Groupe
                    </h4>
                    
                    <div class="form-group">
                        <label>Récépissé ou Statuts de l'association <span class="required">*</span></label>
                        <label for="recepisse-communaute" class="file-upload">
                            <div class="file-upload-icon"><i class="fas fa-file-contract"></i></div>
                            <div class="file-upload-text">
                                <strong>Cliquez pour télécharger le récépissé ou les statuts</strong><br>
                                <span>PDF, JPG, PNG (max: 5MB)</span>
                            </div>
                            <input type="file" id="recepisse-communaute" name="recepisse_communaute" accept=".pdf,.jpg,.jpeg,.png" data-required="communaute">
                        </label>
                        <small class="help-text">Télécharger le récépissé d'enregistrement de l'association ou les statuts officiels du groupe/communauté.</small>
                        <div class="error-message" id="error-recepisse-communaute" style="display:none;"></div>
                    </div>

                    <div class="form-group">
                        <label>CNIB du président ou représentant légal <span class="required">*</span></label>
                        <label for="cnib-communaute" class="file-upload">
                            <div class="file-upload-icon"><i class="fas fa-id-card"></i></div>
                            <div class="file-upload-text">
                                <strong>Cliquez pour télécharger la CNIB du représentant</strong><br>
                                <span>PDF, JPG, PNG (max: 5MB)</span>
                            </div>
                            <input type="file" id="cnib-communaute" name="cnib_communaute" accept=".pdf,.jpg,.jpeg,.png" data-required="communaute">
                        </label>
                        <small class="help-text">Télécharger la CNIB du président ou du représentant légal de la communauté/association/groupe.</small>
                        <div class="error-message" id="error-cnib-communaute" style="display:none;"></div>
                    </div>

                    <div class="form-group">
                        <label>Description de l'élément culturel collectif <span class="required">*</span></label>
                        <textarea name="description_element_communaute" id="description-element-communaute" class="form-control" rows="5" placeholder="Décrivez en détail l'élément culturel collectif détenu par votre communauté..." data-required="communaute">{{ old('description_element_communaute') }}</textarea>
                        <small class="help-text">Fournissez une description détaillée de l'élément culturel collectif détenu par votre communauté (pratiques, savoir-faire, traditions, etc.).</small>
                        <div class="error-message" id="error-description-communaute" style="display:none;"></div>
                    </div>
                </div>

                <!-- Message si aucun type sélectionné -->
                <div id="documents-aucun" class="documents-type">
                    <div class="info-box" style="background: linear-gradient(135deg, rgba(245,158,11,.1), rgba(249,115,22,.05)); border-left:4px solid var(--warning);">
                        <p><i class="fas fa-exclamation-triangle"></i> Veuillez d'abord sélectionner un type de détenteur dans l'étape 1 pour voir les documents requis.</p>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('home') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Annuler</a>
                <button type="button" id="prevBtn" class="btn btn-secondary hidden"><i class="fas fa-chevron-left"></i> Précédent</button>
                <button type="button" id="nextBtn" class="btn btn-primary"><i class="fas fa-chevron-right"></i> Suivant</button>
                <button type="submit" id="submitBtn" class="btn btn-primary hidden"><i class="fas fa-paper-plane"></i> Soumettre la demande</button>
            </div>
        </form>

        <div class="form-footer" style="background: linear-gradient(135deg, #f8fafc, #e2e8f0); padding:2rem; text-align:center; border-radius:0 0 20px 20px;">
            <p style="color:var(--gray); font-size:.9rem;">Vos données sont protégées et ne seront utilisées qu'aux fins d'instruction de votre demande.</p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    let currentStep = 1;
    const totalSteps = 4;

    // Éléments DOM
    const radioButtons = document.querySelectorAll('input[name="type_detenteur"]');
    const autreField = document.getElementById('autre-field');
    const forms = {
        individu: document.getElementById('form-individu'),
        famille: document.getElementById('form-famille'),
        communaute: document.getElementById('form-communaute')
    };

    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    const submitBtn = document.getElementById('submitBtn');
    const progressSteps = document.querySelectorAll('.progress-step');
    const stepContents = document.querySelectorAll('.step-content');
    const formEl = document.getElementById('mainForm');

    // Gestion des documents conditionnels
    const documentSections = {
        individu: document.getElementById('documents-individu'),
        famille: document.getElementById('documents-famille'),
        communaute: document.getElementById('documents-communaute'),
        aucun: document.getElementById('documents-aucun')
    };

    function showDocumentsForType(type) {
        // Masquer toutes les sections de documents
        Object.values(documentSections).forEach(section => {
            if (section) {
                section.classList.add('hidden');
                section.style.display = 'none';
            }
        });

        // Afficher la section correspondante
        if (type && documentSections[type]) {
            documentSections[type].classList.remove('hidden');
            documentSections[type].style.display = 'block';
        } else {
            // Afficher le message "aucun type sélectionné"
            if (documentSections.aucun) {
                documentSections.aucun.classList.remove('hidden');
                documentSections.aucun.style.display = 'block';
            }
        }
    }

    // Gestion de l'affichage des noms de fichiers
    function setupFileInputs() {
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                const label = e.target.closest('.file-upload');
                if (file && label) {
                    label.classList.add('has-file');
                    // Afficher le nom du fichier
                    let fileNameDisplay = label.querySelector('.file-name-display');
                    if (!fileNameDisplay) {
                        fileNameDisplay = document.createElement('div');
                        fileNameDisplay.className = 'file-name-display';
                        label.appendChild(fileNameDisplay);
                    }
                    fileNameDisplay.textContent = `✓ ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                    
                    // Masquer l'erreur si le fichier est sélectionné
                    const errorId = 'error-' + e.target.id;
                    const errorDiv = document.getElementById(errorId);
                    if (errorDiv) {
                        errorDiv.style.display = 'none';
                        errorDiv.textContent = '';
                    }
                } else if (label) {
                    label.classList.remove('has-file');
                    const fileNameDisplay = label.querySelector('.file-name-display');
                    if (fileNameDisplay) {
                        fileNameDisplay.remove();
                    }
                }
            });
        });
    }

    // Gestion des formulaires conditionnels
    function setDisabledAndRequired(container, disabled) {
        if (!container) {
            console.log('Container non trouvé dans setDisabledAndRequired');
            return;
        }
        console.log('setDisabledAndRequired appelé:', disabled, 'sur:', container.id);
        container.querySelectorAll('input, select, textarea').forEach(el => {
            if (disabled) {
                el.setAttribute('disabled', 'disabled');
                el.removeAttribute('required');
                console.log('Champ désactivé:', el.name);
            } else {
                el.removeAttribute('disabled');
                console.log('Champ activé:', el.name);
            }
        });
    }

    function applyTypeRequirements(selectedValue) {
        console.log('=== applyTypeRequirements ===', selectedValue);

        // Reset all
        Object.values(forms).forEach(form => {
            console.log('Désactivation de:', form ? form.id : 'null');
            setDisabledAndRequired(form, true);
        });

        if (autreField) {
            autreField.querySelectorAll('input').forEach(el => {
                el.removeAttribute('required');
                el.setAttribute('disabled', 'disabled');
            });
        }

        if (selectedValue === 'individu') {
            console.log('Activation formulaire INDIVIDU');
            setDisabledAndRequired(forms.individu, false);
            const nom = forms.individu ? forms.individu.querySelector('input[name="nom"]') : null;
            const prenom = forms.individu ? forms.individu.querySelector('input[name="prenom"]') : null;
            const sexe = forms.individu ? forms.individu.querySelector('select[name="sexe"]') : null;
            if (nom) nom.setAttribute('required', 'required');
            if (prenom) prenom.setAttribute('required', 'required');
            if (sexe) sexe.setAttribute('required', 'required');
        } else if (selectedValue === 'famille') {
            console.log('Activation formulaire FAMILLE');
            const container = forms.famille;
            console.log('Container famille:', container);
            setDisabledAndRequired(container, false);
            const nomStructure = container ? container.querySelector('input[name="nom_structure"]') : null;
            const typeStructure = container ? container.querySelector('select[name="type_structure"]') : null;
            console.log('nom_structure trouvé:', nomStructure);
            console.log('type_structure trouvé:', typeStructure);
            if (nomStructure) {
                nomStructure.setAttribute('required', 'required');
                console.log('nom_structure rendu obligatoire');
            }
            if (typeStructure) {
                typeStructure.setAttribute('required', 'required');
                console.log('type_structure rendu obligatoire');
            }
        } else if (selectedValue === 'communaute') {
            console.log('Activation formulaire COMMUNAUTÉ');
            const container = forms.communaute;
            console.log('Container communaute:', container);
            setDisabledAndRequired(container, false);
            const nomStructure = container ? container.querySelector('input[name="nom_structure"]') : null;
            const typeStructure = container ? container.querySelector('select[name="type_structure"]') : null;
            console.log('nom_structure trouvé:', nomStructure);
            console.log('type_structure trouvé:', typeStructure);
            if (nomStructure) nomStructure.setAttribute('required', 'required');
            if (typeStructure) typeStructure.setAttribute('required', 'required');
        } else if (selectedValue === 'autre') {
            console.log('Activation champ AUTRE');
            autreField.querySelectorAll('input').forEach(el => { el.removeAttribute('disabled'); });
            const autreType = document.querySelector('input[name="autre_type_detenteur"]');
            if (autreType) autreType.setAttribute('required', 'required');
        }
    }

    function hideAllForms() {
        Object.values(forms).forEach(form => {
            if (form) {
                form.classList.add('hidden');
                form.style.display = 'none';
                form.style.visibility = 'hidden';
                form.style.opacity = '0';
            }
        });
        if (autreField) {
            autreField.classList.add('hidden');
            autreField.style.display = 'none';
            autreField.style.visibility = 'hidden';
            autreField.style.opacity = '0';
        }
    }

    function showSelectedForm() {
        hideAllForms();
        const selectedValue = document.querySelector('input[name="type_detenteur"]:checked')?.value;

        console.log('Type sélectionné:', selectedValue);
        console.log('Forms disponibles:', forms);

        if (!selectedValue) return;

        if (selectedValue === 'autre') {
            console.log('Affichage du champ "Autre"');
            if (autreField) {
                autreField.classList.remove('hidden');
                autreField.style.display = 'block';
                autreField.style.visibility = 'visible';
                autreField.style.opacity = '1';
                console.log('Champ "Autre" affiché:', autreField);
            } else {
                console.error('autreField non trouvé dans le DOM');
            }
        } else if (forms[selectedValue]) {
            console.log('Affichage du formulaire:', selectedValue);
            const formElement = forms[selectedValue];
            formElement.classList.remove('hidden');
            formElement.style.display = 'block';
            formElement.style.visibility = 'visible';
            formElement.style.opacity = '1';
            console.log('Formulaire affiché:', formElement);
        } else {
            console.error('Formulaire non trouvé pour:', selectedValue);
            console.error('Formulaires disponibles:', Object.keys(forms));
        }

        applyTypeRequirements(selectedValue);
        
        // Afficher les documents correspondants
        showDocumentsForType(selectedValue);
    }

    // Navigation par étapes
    function showStep(step) {
        // Masquer tous les contenus d'étapes
        stepContents.forEach(content => {
            content.classList.add('hidden');
            content.style.display = 'none';
        });

        // Afficher l'étape courante
        const currentStepContent = document.querySelector(`.step-content[data-step="${step}"]`);
        if (currentStepContent) {
            currentStepContent.classList.remove('hidden');
            currentStepContent.style.display = 'block';
        }

        // Si on arrive à l'étape 4, afficher les documents selon le type sélectionné
        if (step === 4) {
            const selectedType = document.querySelector('input[name="type_detenteur"]:checked')?.value;
            showDocumentsForType(selectedType || 'individu');
        }

        // Mettre à jour la barre de progression
        progressSteps.forEach((progressStep, index) => {
            progressStep.classList.remove('active', 'completed');
            if (index + 1 < step) {
                progressStep.classList.add('completed');
            } else if (index + 1 === step) {
                progressStep.classList.add('active');
            }
        });

        // Mettre à jour les boutons
        prevBtn.classList.toggle('hidden', step === 1);
        nextBtn.classList.toggle('hidden', step === totalSteps);
        submitBtn.classList.toggle('hidden', step !== totalSteps);

        currentStep = step;
    }

    // Validation des étapes
    function validateStep(step) {
        const currentStepContent = document.querySelector(`.step-content[data-step="${step}"]`);
        if (!currentStepContent) return true;

        let isValid = true;

        // Validation spécifique pour l'étape 1 (identification)
        if (step === 1) {
            const selectedType = document.querySelector('input[name="type_detenteur"]:checked')?.value;

            if (!selectedType) {
                alert('Veuillez sélectionner un type de détenteur');
                return false;
            }

            // Validation selon le type sélectionné
            if (selectedType === 'individu') {
                const nom = document.querySelector('input[name="nom"]');
                const prenom = document.querySelector('input[name="prenom"]');
                const sexe = document.querySelector('select[name="sexe"]');
                const telephone = document.querySelector('input[name="telephone"]');

                if (!nom.value.trim()) {
                    nom.classList.add('error');
                    isValid = false;
                    alert('Le nom est obligatoire pour un individu');
                }
                if (!prenom.value.trim()) {
                    prenom.classList.add('error');
                    isValid = false;
                    alert('Le prénom est obligatoire pour un individu');
                }
                if (!sexe.value) {
                    sexe.classList.add('error');
                    isValid = false;
                    alert('Le sexe est obligatoire pour un individu');
                }
                if (!telephone.value.trim()) {
                    telephone.classList.add('error');
                    isValid = false;
                    alert('Le téléphone est obligatoire');
                }

                // Validation province et commune (obligatoires pour tous)
                const province = document.querySelector('select[name="province"]');
                const commune = document.querySelector('select[name="commune"]');

                if (!province.value) {
                    province.classList.add('error');
                    isValid = false;
                    alert('La province est obligatoire');
                }
                if (!commune.value) {
                    commune.classList.add('error');
                    isValid = false;
                    alert('La commune est obligatoire');
                }
            } else if (selectedType === 'famille' || selectedType === 'communaute') {
                const container = selectedType === 'famille' ? forms.famille : forms.communaute;
                const nomStructure = container ? container.querySelector('input[name="nom_structure"]') : null;
                const typeStructure = container ? container.querySelector('select[name="type_structure"]') : null;
                const telephone = document.querySelector('input[name="telephone"]');

                if (!nomStructure.value.trim()) {
                    nomStructure.classList.add('error');
                    isValid = false;
                    alert('Le nom de la ' + (selectedType === 'famille' ? 'famille' : 'communauté') + ' est obligatoire');
                }
                if (!typeStructure.value) {
                    typeStructure.classList.add('error');
                    isValid = false;
                    alert('Le type de ' + (selectedType === 'famille' ? 'famille' : 'communauté') + ' est obligatoire');
                }
                if (!telephone.value.trim()) {
                    telephone.classList.add('error');
                    isValid = false;
                    alert('Le téléphone est obligatoire');
                }

                // Validation province et commune (obligatoires pour tous)
                const province = document.querySelector('select[name="province"]');
                const commune = document.querySelector('select[name="commune"]');

                if (!province.value) {
                    province.classList.add('error');
                    isValid = false;
                    alert('La province est obligatoire');
                }
                if (!commune.value) {
                    commune.classList.add('error');
                    isValid = false;
                    alert('La commune est obligatoire');
                }
            } else if (selectedType === 'autre') {
                const autreType = document.querySelector('input[name="autre_type_detenteur"]');
                const telephone = document.querySelector('input[name="telephone"]');

                if (!autreType.value.trim()) {
                    autreType.classList.add('error');
                    isValid = false;
                    alert('Veuillez préciser le type de détenteur');
                }
                if (!telephone.value.trim()) {
                    telephone.classList.add('error');
                    isValid = false;
                    alert('Le téléphone est obligatoire');
                }

                // Validation province et commune (obligatoires pour tous)
                const province = document.querySelector('select[name="province"]');
                const commune = document.querySelector('select[name="commune"]');

                if (!province.value) {
                    province.classList.add('error');
                    isValid = false;
                    alert('La province est obligatoire');
                }
                if (!commune.value) {
                    commune.classList.add('error');
                    isValid = false;
                    alert('La commune est obligatoire');
                }
            }
        }

        // Validation spéciale pour l'étape 2 (patrimoines)
        if (step === 2) {
            const checkedPatrimoines = currentStepContent.querySelectorAll('input[name="elements_patrimoine[]"]:checked');
            if (checkedPatrimoines.length === 0) {
                isValid = false;
                alert('Veuillez sélectionner au moins un élément patrimonial');
            }
        }

        // Validation spéciale pour l'étape 4 (documents)
        if (step === 4) {
            const selectedType = document.querySelector('input[name="type_detenteur"]:checked')?.value;
            
            if (!selectedType) {
                isValid = false;
                alert('Veuillez sélectionner un type de détenteur dans l\'étape 1');
                return false;
            }

            // Validation des documents selon le type
            if (selectedType === 'individu') {
                const cnib = document.getElementById('cnib-individu');
                const photoIdentite = document.getElementById('photo-identite-individu');
                const description = document.getElementById('description-element-individu');

                if (!cnib.files || cnib.files.length === 0) {
                    showFieldError('error-cnib-individu', 'La copie CNIB/Passeport est obligatoire');
                    cnib.closest('.file-upload')?.classList.add('error');
                    isValid = false;
                }

                if (!photoIdentite.files || photoIdentite.files.length === 0) {
                    showFieldError('error-photo-identite-individu', 'La photo d\'identité est obligatoire');
                    photoIdentite.closest('.file-upload')?.classList.add('error');
                    isValid = false;
                }

                if (!description.value.trim()) {
                    showFieldError('error-description-individu', 'La description de l\'élément culturel est obligatoire');
                    description.classList.add('error');
                    isValid = false;
                }
            } else if (selectedType === 'famille') {
                const cnib = document.getElementById('cnib-famille');
                const attestation = document.getElementById('attestation-famille');
                const description = document.getElementById('description-savoir-famille');
                const photoGroupe = document.getElementById('photo-groupe-famille');

                if (!cnib.files || cnib.files.length === 0) {
                    showFieldError('error-cnib-famille', 'La CNIB du représentant est obligatoire');
                    cnib.closest('.file-upload')?.classList.add('error');
                    isValid = false;
                }

                if (!attestation.files || attestation.files.length === 0) {
                    showFieldError('error-attestation-famille', 'L\'attestation coutumière est obligatoire');
                    attestation.closest('.file-upload')?.classList.add('error');
                    isValid = false;
                }

                if (!description.value.trim()) {
                    showFieldError('error-description-famille', 'La description du savoir détenu est obligatoire');
                    description.classList.add('error');
                    isValid = false;
                }

                if (!photoGroupe.files || photoGroupe.files.length === 0) {
                    showFieldError('error-photo-groupe-famille', 'La photo de groupe familial est obligatoire');
                    photoGroupe.closest('.file-upload')?.classList.add('error');
                    isValid = false;
                }
            } else if (selectedType === 'communaute') {
                const recepisse = document.getElementById('recepisse-communaute');
                const cnib = document.getElementById('cnib-communaute');
                const description = document.getElementById('description-element-communaute');

                if (!recepisse.files || recepisse.files.length === 0) {
                    showFieldError('error-recepisse-communaute', 'Le récépissé ou les statuts sont obligatoires');
                    recepisse.closest('.file-upload')?.classList.add('error');
                    isValid = false;
                }

                if (!cnib.files || cnib.files.length === 0) {
                    showFieldError('error-cnib-communaute', 'La CNIB du président est obligatoire');
                    cnib.closest('.file-upload')?.classList.add('error');
                    isValid = false;
                }

                if (!description.value.trim()) {
                    showFieldError('error-description-communaute', 'La description de l\'élément culturel collectif est obligatoire');
                    description.classList.add('error');
                    isValid = false;
                }
            }
        }

        // Validation pour l'étape 3 (déclaration)
        if (step === 3) {
            const declaration = document.querySelector('input[name="declaration"]');
            const signature = document.querySelector('input[name="signature"]');

            if (!declaration.checked) {
                isValid = false;
                alert('Vous devez accepter la déclaration sur l\'honneur');
            }
            if (!signature.value.trim()) {
                signature.classList.add('error');
                isValid = false;
                alert('La signature est obligatoire');
            }
        }

        return isValid;
    }

    // Fonction pour afficher les erreurs de champ
    function showFieldError(errorId, message) {
        const errorDiv = document.getElementById(errorId);
        if (errorDiv) {
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
        }
    }

    // Fonction pour masquer les erreurs de champ
    function hideFieldError(errorId) {
        const errorDiv = document.getElementById(errorId);
        if (errorDiv) {
            errorDiv.style.display = 'none';
            errorDiv.textContent = '';
        }
    }

    // Événements
    radioButtons.forEach(radio => {
        radio.addEventListener('change', function(e) {
            console.log('Bouton radio changé:', e.target.value);
            showSelectedForm();
            // Mettre à jour les documents si on est sur l'étape 4
            if (currentStep === 4) {
                showDocumentsForType(e.target.value);
            }
        });
    });

    nextBtn.addEventListener('click', function() {
        if (validateStep(currentStep)) {
            showStep(currentStep + 1);
        }
    });

    prevBtn.addEventListener('click', function() {
        showStep(currentStep - 1);
    });

    // Validation en temps réel
    document.querySelectorAll('.form-control').forEach(field => {
        field.addEventListener('blur', function() {
            this.classList.remove('error');
        });

        field.addEventListener('input', function() {
            this.classList.remove('error');
        });

        field.addEventListener('change', function() {
            this.classList.remove('error');
        });
    });

    // Données des provinces et communes
    const provincesCommunes = {
        "Koosin (Kossi)": ["Nouna"],
        "Nayala": ["Toma"],
        "Sourou": ["Tougan"],
        "Mouhoun": ["Dédougou"],
        "Balé": ["Boromo"],
        "Barnwa": ["Solenzo"],
        "Houet": ["Bobo-Dioulasso"],
        "Kénédougou": ["Orodara"],
        "Tuy": ["Houndé"],
        "Comoé": ["Banfora"],
        "Léraba": ["Sindou"],
        "Bougouriba": ["Diébougou"],
        "Ioba": ["Dano"],
        "Noumbiel": ["Batté"],
        "Poni": ["Gaoua"],
        "Loroum": ["Titao"],
        "Passoré": ["Yako"],
        "Yatenga": ["Ouahigouya"],
        "Zondoma": ["Gourcy"],
        "Boukklemde": ["Koudougou"],
        "Sanguié": ["Réo"],
        "Sissili": ["Léo"],
        "Ziro": ["Sapouy"],
        "Djelgodji": ["Djibo"],
        "Karo-Peli": ["Arbinda"],
        "Bam": ["Kongoussi"],
        "Namentenga": ["Boussa"],
        "Sandbondtenga": ["Kaya"],
        "Bassitenga": ["Ziniaré"],
        "Ganzourgou": ["Zorgho"],
        "Kourwéogo": ["Bousse"],
        "Kadlogo": ["Quagadougou"],
        "Bazèga": ["Kombissiri"],
        "Nahouri": ["Pô"],
        "Zoundwéogo": ["Manga"],
        "Boulgou": ["Tenkodogo"],
        "Koulpelogo": ["Ouargaye"],
        "Kourittenga": ["Koupèla"],
        "Oudalan": ["Gorm-Gorom"],
        "Séno": ["Dori"],
        "Yagha": ["Sebba"],
        "Gourma": ["Fada N'Gourma"],
        "Kompienga": ["Pama"],
        "Dyamongou": ["Kantchari"],
        "Gobnangou": ["Diapaga"],
        "Gnagna": ["Bogandé"],
        "Komondjari": ["Gayéri"]
    };

    // Gestion de la dépendance Province → Commune
    const provinceSelect = document.getElementById('province');
    const communeSelect = document.getElementById('commune');

    function updateCommunes() {
        const selectedProvince = provinceSelect.value;

        // Vider et désactiver la commune
        communeSelect.innerHTML = '<option value="">Sélectionnez d\'abord une province</option>';
        communeSelect.disabled = true;
        communeSelect.removeAttribute('required');

        if (selectedProvince && provincesCommunes[selectedProvince]) {
            // Activer et remplir la commune
            communeSelect.disabled = false;
            communeSelect.setAttribute('required', 'required');

            // Ajouter les communes de la province sélectionnée
            provincesCommunes[selectedProvince].forEach(commune => {
                const option = document.createElement('option');
                option.value = commune;
                option.textContent = commune;
                // Restaurer la sélection si c'est une erreur de validation
                if (commune === '{{ old("commune") }}') {
                    option.selected = true;
                }
                communeSelect.appendChild(option);
            });
        }
    }

    // Événement sur le changement de province
    provinceSelect.addEventListener('change', updateCommunes);

    // Fonction de diagnostic
    function diagnosticForms() {
        console.log('=== DIAGNOSTIC FORMULAIRES ===');
        console.log('Forms individu:', forms.individu ? 'Trouvé' : 'Non trouvé');
        console.log('Forms famille:', forms.famille ? 'Trouvé' : 'Non trouvé');
        console.log('Forms communaute:', forms.communaute ? 'Trouvé' : 'Non trouvé');
        console.log('autreField:', autreField ? 'Trouvé' : 'Non trouvé');

        const radioButtons = document.querySelectorAll('input[name="type_detenteur"]');
        console.log('Boutons radio:', radioButtons.length);
        radioButtons.forEach(radio => {
            console.log(`  - ${radio.value}: ${radio.checked ? 'coché' : 'non coché'}`);
        });
    }

    // Initialisation avec un petit délai pour être sûr que le DOM est prêt
    setTimeout(() => {
        console.log('Initialisation des formulaires');
        diagnosticForms();

        // Cocher par défaut "individu" si aucun n'est déjà coché
        const checkedType = document.querySelector('input[name="type_detenteur"]:checked');
        console.log('Type coché au chargement:', checkedType ? checkedType.value : 'Aucun');

        if (!checkedType) {
            document.getElementById('type_individu').checked = true;
            console.log('Cochage automatique de "individu"');
        }

        // Forcer l'affichage immédiat du formulaire individu
        const currentChecked = document.querySelector('input[name="type_detenteur"]:checked');
        if (forms.individu && currentChecked && currentChecked.value === 'individu') {
            console.log('Affichage forcé du formulaire individu');
            forms.individu.classList.remove('hidden');
            forms.individu.style.display = 'block';
            forms.individu.style.visibility = 'visible';
            forms.individu.style.opacity = '1';
        } else if (forms.individu && !currentChecked) {
            // Si aucun n'est coché, c'est individu par défaut
            console.log('Affichage du formulaire individu par défaut');
            forms.individu.classList.remove('hidden');
            forms.individu.style.display = 'block';
            forms.individu.style.visibility = 'visible';
            forms.individu.style.opacity = '1';
        }

        showSelectedForm();
        showStep(1);
        
        // Initialiser les gestionnaires de fichiers
        setupFileInputs();
        
        // Afficher les documents selon le type sélectionné
        const initialType = document.querySelector('input[name="type_detenteur"]:checked')?.value || 'individu';
        showDocumentsForType(initialType);

        console.log('=== FIN INITIALISATION ===');
    }, 100);

    // Initialiser les communes si une province est déjà sélectionnée (erreur de validation)
    if (provinceSelect.value) {
        updateCommunes();
    }

    // Empêcher la touche Enter de soumettre le formulaire avant la dernière étape
    formEl.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            const isTextArea = e.target && e.target.tagName === 'TEXTAREA';
            if (!isTextArea && currentStep < totalSteps) {
                e.preventDefault();
                if (validateStep(currentStep)) {
                    showStep(currentStep + 1);
                }
            }
        }
    });
});

// Activation de tous les champs avant soumission (pour les champs désactivés)
function enableAllFields() {
    console.log('=== ACTIVATION DE TOUS LES CHAMPS ===');
    let count = 0;

    // Première passe: afficher tous les formulaires
    Object.values(forms).forEach(form => {
        if (form) {
            form.classList.remove('hidden');
            form.style.display = 'block';
            form.style.visibility = 'visible';
            form.style.opacity = '1';
            console.log('Formulaire affiché:', form.id);
        }
    });
    if (autreField) {
        autreField.classList.remove('hidden');
        autreField.style.display = 'block';
    }

    // Deuxième passe: activer tous les champs désactivés
    document.querySelectorAll('input[disabled], select[disabled], textarea[disabled]').forEach(field => {
        field.removeAttribute('disabled');
        count++;
        console.log('Champ activé:', field.name || field.id);
    });
    console.log(`Total champs activés: ${count}`);

    // Vérification: afficher les valeurs des champs importants
    const nomStructure = document.querySelector('input[name="nom_structure"]');
    const typeStructure = document.querySelector('select[name="type_structure"]');
    console.log('nom_structure valeur:', nomStructure ? nomStructure.value : 'NULL');
    console.log('type_structure valeur:', typeStructure ? typeStructure.value : 'NULL');
}

// Validation finale avant soumission
document.getElementById('mainForm').addEventListener('submit', function(e) {
    console.log('=== SOUMISSION DU FORMULAIRE ===');

    // Activer tous les champs désactivés pour qu'ils soient envoyés dans le formulaire
    enableAllFields();

    const typeDetenteur = document.querySelector('input[name="type_detenteur"]:checked')?.value;
    console.log('Type de détenteur:', typeDetenteur);

    // Validation des éléments patrimoniaux
    const anyChecked = !!document.querySelector('input[name="elements_patrimoine[]"]:checked');
    if (!anyChecked) {
        e.preventDefault();
        alert('Veuillez sélectionner au moins un élément patrimonial');
        return false;
    }

    // Validation spécifique selon le type de détenteur
    if (typeDetenteur === 'famille' || typeDetenteur === 'communaute') {
        const container = typeDetenteur === 'famille' ? document.getElementById('form-famille') : document.getElementById('form-communaute');
        const nomStructure = container ? container.querySelector('input[name="nom_structure"]') : null;
        const typeStructure = container ? container.querySelector('select[name="type_structure"]') : null;

        if (!nomStructure.value.trim()) {
            e.preventDefault();
            alert('Le nom de la famille/communauté est obligatoire');
            nomStructure.focus();
            return false;
        }

        if (!typeStructure.value) {
            e.preventDefault();
            alert('Le type de famille/communauté est obligatoire');
            typeStructure.focus();
            return false;
        }
    }

    if (typeDetenteur === 'individu') {
        const nom = document.querySelector('input[name="nom"]');
        const prenom = document.querySelector('input[name="prenom"]');
        const sexe = document.querySelector('select[name="sexe"]');

        if (!nom.value.trim()) {
            e.preventDefault();
            alert('Le nom est obligatoire pour un individu');
            nom.focus();
            return false;
        }

        if (!prenom.value.trim()) {
            e.preventDefault();
            alert('Le prénom est obligatoire pour un individu');
            prenom.focus();
            return false;
        }

        if (!sexe.value) {
            e.preventDefault();
            alert('Le sexe est obligatoire pour un individu');
            sexe.focus();
            return false;
        }
    }

    // Validation des documents obligatoires selon le type de détenteur
    let documentsValid = true;
    let errorMessage = '';

    if (typeDetenteur === 'individu') {
        const cnib = document.getElementById('cnib-individu');
        const photoIdentite = document.getElementById('photo-identite-individu');
        const description = document.getElementById('description-element-individu');

        if (!cnib.files || cnib.files.length === 0) {
            documentsValid = false;
            errorMessage = 'La copie CNIB/Passeport est obligatoire pour un individu.';
            cnib.closest('.file-upload')?.classList.add('error');
            showFieldError('error-cnib-individu', errorMessage);
        }
        if (!photoIdentite.files || photoIdentite.files.length === 0) {
            documentsValid = false;
            errorMessage = 'La photo d\'identité est obligatoire pour un individu.';
            photoIdentite.closest('.file-upload')?.classList.add('error');
            showFieldError('error-photo-identite-individu', errorMessage);
        }
        if (!description.value.trim()) {
            documentsValid = false;
            errorMessage = 'La description de l\'élément culturel est obligatoire pour un individu.';
            description.classList.add('error');
            showFieldError('error-description-individu', errorMessage);
        }
    } else if (typeDetenteur === 'famille') {
        const cnib = document.getElementById('cnib-famille');
        const attestation = document.getElementById('attestation-famille');
        const description = document.getElementById('description-savoir-famille');
        const photoGroupe = document.getElementById('photo-groupe-famille');

        if (!cnib.files || cnib.files.length === 0) {
            documentsValid = false;
            errorMessage = 'La CNIB du représentant de la famille est obligatoire.';
            cnib.closest('.file-upload')?.classList.add('error');
            showFieldError('error-cnib-famille', errorMessage);
        }
        if (!attestation.files || attestation.files.length === 0) {
            documentsValid = false;
            errorMessage = 'L\'attestation coutumière/chefferie est obligatoire pour une famille.';
            attestation.closest('.file-upload')?.classList.add('error');
            showFieldError('error-attestation-famille', errorMessage);
        }
        if (!description.value.trim()) {
            documentsValid = false;
            errorMessage = 'La description du savoir détenu est obligatoire pour une famille.';
            description.classList.add('error');
            showFieldError('error-description-famille', errorMessage);
        }
        if (!photoGroupe.files || photoGroupe.files.length === 0) {
            documentsValid = false;
            errorMessage = 'La photo de groupe familial est obligatoire pour une famille.';
            photoGroupe.closest('.file-upload')?.classList.add('error');
            showFieldError('error-photo-groupe-famille', errorMessage);
        }
    } else if (typeDetenteur === 'communaute') {
        const recepisse = document.getElementById('recepisse-communaute');
        const cnib = document.getElementById('cnib-communaute');
        const description = document.getElementById('description-element-communaute');

        if (!recepisse.files || recepisse.files.length === 0) {
            documentsValid = false;
            errorMessage = 'Le récépissé ou les statuts de l\'association sont obligatoires pour une communauté.';
            recepisse.closest('.file-upload')?.classList.add('error');
            showFieldError('error-recepisse-communaute', errorMessage);
        }
        if (!cnib.files || cnib.files.length === 0) {
            documentsValid = false;
            errorMessage = 'La CNIB du président ou représentant légal est obligatoire pour une communauté.';
            cnib.closest('.file-upload')?.classList.add('error');
            showFieldError('error-cnib-communaute', errorMessage);
        }
        if (!description.value.trim()) {
            documentsValid = false;
            errorMessage = 'La description de l\'élément culturel collectif est obligatoire pour une communauté.';
            description.classList.add('error');
            showFieldError('error-description-communaute', errorMessage);
        }
    }

    if (!documentsValid) {
        e.preventDefault();
        // Aller à l'étape 4 pour voir les erreurs
        showStep(4);
        alert('Veuillez remplir tous les documents obligatoires dans l\'étape 4.');
        return false;
    }

    // Vérifier la déclaration
    const declaration = document.querySelector('input[name="declaration"]');
    if (!declaration.checked) {
        e.preventDefault();
        alert('Veuillez accepter la déclaration sur l\'honneur');
        return false;
    }

    // Vérifier le téléphone (obligatoire pour tous)
    const telephone = document.querySelector('input[name="telephone"]');
    if (!telephone.value.trim()) {
        e.preventDefault();
        alert('Le téléphone est obligatoire');
        telephone.focus();
        return false;
    }

    // Vérifier la province et commune (obligatoires pour tous)
    const province = document.querySelector('select[name="province"]');
    const commune = document.querySelector('select[name="commune"]');
    if (!province.value) {
        e.preventDefault();
        alert('La province est obligatoire');
        province.focus();
        return false;
    }
    if (!commune.value) {
        e.preventDefault();
        alert('La commune est obligatoire');
        commune.focus();
        return false;
    }

    // Vérifier la signature
    const signature = document.querySelector('input[name="signature"]');
    if (!signature.value.trim()) {
        e.preventDefault();
        alert('La signature est obligatoire');
        signature.focus();
        return false;
    }
    if (signature.value.trim().length < 3) {
        e.preventDefault();
        alert('La signature doit contenir au moins 3 caractères');
        signature.focus();
        return false;
    }
});
</script>
@endsection
