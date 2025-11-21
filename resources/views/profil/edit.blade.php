@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    :root { --primary:#FF0000; --secondary:#00AA00; --success:#10b981; --warning:#f59e0b; --dark:#0f172a; --light:#f8fafc; --gray:#64748b; }
    .page-bg { background: linear-gradient(135deg, #FFE5E5 0%, #E5FFE5 100%); min-height:100vh; padding:2rem 1rem; }
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
    @media (max-width:768px){ .page-bg{ padding:1rem .5rem; } .form-header{ padding:2rem 1.5rem; } .form-header h1{ font-size:1.5rem; } .form-body{ padding:2rem 1.5rem; } .radio-cards{ grid-template-columns:1fr; } .form-grid{ grid-template-columns:1fr; } .form-actions{ flex-direction:column; } .btn{ width:100%; justify-content:center; } .progress-container{ padding:1.5rem 1rem; } .step-label{ font-size:.75rem; } .step-circle{ width:35px; height:35px; font-size:.875rem; } }
</style>
@endpush

@section('content')
<div class="page-bg">
    <div class="container-max">
        <div class="form-header">
            <h1><i class="fas fa-edit"></i> Modifier votre demande</h1>
            <p>Demande #{{ $demande->id_demande }}</p>
        </div>

        <form class="form-body" id="mainForm" action="{{ route('profil.update', $demande->id_demande) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Informations existantes -->
            <div class="info-box">
                <p><i class="fas fa-info-circle"></i> Vous pouvez modifier uniquement les demandes en attente. Les champs pré-remplis correspondent à votre demande actuelle.</p>
            </div>

            <!-- Type de détenteur -->
            <div class="form-section">
                <div class="section-header">
                    <div class="section-icon"><i class="fas fa-user"></i></div>
                    <h3>Type de détenteur</h3>
                </div>

                <input type="hidden" name="type_detenteur" value="{{ $demandeur->type_detenteur }}">

                @if($demandeur->type_detenteur === 'individu')
                    <div class="info-box">
                        <p><i class="fas fa-user"></i> Individu</p>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Nom <span class="required">*</span></label>
                            <input type="text" name="nom" class="form-control" value="{{ old('nom', $demandeur->nom) }}" required>
                            @error('nom')<div class="error-message">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label>Prénom <span class="required">*</span></label>
                            <input type="text" name="prenom" class="form-control" value="{{ old('prenom', $demandeur->prenom) }}" required>
                            @error('prenom')<div class="error-message">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label>Date de naissance</label>
                            <input type="date" name="date_naiss" class="form-control" value="{{ old('date_naiss', $demandeur->date_naiss?->format('Y-m-d')) }}">
                        </div>
                        <div class="form-group">
                            <label>Sexe <span class="required">*</span></label>
                            <select name="sexe" class="form-control" required>
                                <option value="">Sélectionnez</option>
                                <option value="M" {{ $demandeur->sexe === 'M' ? 'selected' : '' }}>Masculin</option>
                                <option value="F" {{ $demandeur->sexe === 'F' ? 'selected' : '' }}>Féminin</option>
                                <option value="Autre" {{ $demandeur->sexe === 'Autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                        </div>
                    </div>
                @elseif(in_array($demandeur->type_detenteur, ['famille', 'communaute']))
                    <div class="info-box">
                        <p><i class="fas fa-users"></i> {{ ucfirst($demandeur->type_detenteur) }}</p>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Nom <span class="required">*</span></label>
                            <input type="text" name="nom_structure" class="form-control" value="{{ old('nom_structure', $demandeur->nom_structure) }}" required>
                            @error('nom_structure')<div class="error-message">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label>Type <span class="required">*</span></label>
                            <input type="text" name="type_structure" class="form-control" value="{{ old('type_structure', $demandeur->type_structure) }}" required>
                            @error('type_structure')<div class="error-message">{{ $message }}</div>@enderror
                        </div>
                    </div>
                @endif

                <div class="form-grid" style="margin-top:2rem;">
                    <div class="form-group">
                        <label>Téléphone <span class="required">*</span></label>
                        <input type="text" name="telephone" class="form-control" value="{{ old('telephone', $demandeur->telephone) }}" required>
                        @error('telephone')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $demandeur->email) }}">
                        @error('email')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Province</label>
                        <input type="text" name="province" class="form-control" value="{{ old('province', $demandeur->province) }}">
                    </div>
                    <div class="form-group">
                        <label>Commune</label>
                        <input type="text" name="commune" class="form-control" value="{{ old('commune', $demandeur->commune) }}">
                    </div>
                    <div class="form-group">
                        <label>Profession</label>
                        <input type="text" name="profession" class="form-control" value="{{ old('profession', $demandeur->profession) }}">
                    </div>
                </div>
            </div>

            <!-- Patrimoines -->
            <div class="form-section">
                <div class="section-header">
                    <div class="section-icon"><i class="fas fa-landmark"></i></div>
                    <h3>Éléments du Patrimoine</h3>
                </div>
                <div class="info-box">
                    <p><i class="fas fa-info-circle"></i> Sélectionnez un ou plusieurs éléments patrimoniaux dont vous êtes détenteur.</p>
                </div>

                <div class="multi-select">
                    @foreach($domaines as $titre => $patrimoines)
                        <div class="multi-select-group">
                            <div class="multi-select-group-title">{{ $titre }}</div>
                            @foreach($patrimoines as $p)
                                <div class="multi-select-item">
                                    <input type="checkbox" id="pat_{{ $p->id_element }}" name="elements_patrimoine[]" value="{{ $p->id_element }}" {{ in_array($p->id_element, old('elements_patrimoine', $demande->patrimoines->pluck('id_element')->toArray())) ? 'checked' : '' }}>
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

            <!-- Déclaration -->
            <div class="form-section">
                <div class="section-header">
                    <div class="section-icon"><i class="fas fa-clipboard-check"></i></div>
                    <h3>Déclaration sur l'honneur</h3>
                </div>
                <div class="declaration-box">
                    <div class="declaration-checkbox">
                        <input type="checkbox" name="declaration" value="1" id="decl" required {{ old('declaration', true) ? 'checked' : '' }}>
                        <div class="declaration-text">
                            <strong>Je déclare sur l'honneur que toutes les informations fournies sont exactes et complètes.</strong>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Signature (nom complet) <span class="required">*</span></label>
                        <input type="text" name="signature" placeholder="Votre nom complet" class="form-control" required value="{{ old('signature', $demande->signature) }}">
                        @error('signature')
                            <div class="mt-1" style="color:#dc2626; font-size:.9rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('profil.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Annuler</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Enregistrer les modifications</button>
            </div>
        </form>
    </div>
</div>
@endsection

