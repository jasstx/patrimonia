@extends('layouts.app')

@section('title', 'Inscription - Détenteur de Patrimoine')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-lg">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-user-plus"></i> Inscription - Détenteur de Patrimoine</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">
                        Créez un compte pour soumettre vos demandes de reconnaissance de patrimoine culturel.
                    </p>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.post') }}">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Nom complet <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}" required autofocus placeholder="Votre nom complet">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" required placeholder="exemple@email.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Téléphone</label>
                                <input type="text" name="telephone" class="form-control @error('telephone') is-invalid @enderror"
                                       value="{{ old('telephone') }}" placeholder="+226 XX XX XX XX">
                                @error('telephone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Mot de passe <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                       required placeholder="Minimum 8 caractères">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Minimum 8 caractères</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirmer le mot de passe <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input @error('accept_terms') is-invalid @enderror"
                                   type="checkbox" name="accept_terms" id="accept_terms"
                                   required {{ old('accept_terms') ? 'checked' : '' }}>
                            <label class="form-check-label" for="accept_terms">
                                J'accepte les <a href="#" target="_blank">conditions d'utilisation</a> et la
                                <a href="#" target="_blank">politique de confidentialité</a> <span class="text-danger">*</span>
                            </label>
                            @error('accept_terms')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg flex-fill">
                                <i class="fas fa-user-plus"></i> S'inscrire
                            </button>
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-sign-in-alt"></i> J'ai déjà un compte
                            </a>
                        </div>

                        <div class="text-center mt-3">
                            <p class="mb-0">
                                <a href="{{ route('home') }}" class="text-decoration-none">
                                    <i class="fas fa-home"></i> Retour à l'accueil
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Informations complémentaires -->
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="card-title"><i class="fas fa-info-circle"></i> Pourquoi s'inscrire ?</h6>
                    <ul class="mb-0 small">
                        <li><i class="fas fa-check-circle text-success"></i> Soumettre vos demandes de reconnaissance de patrimoine</li>
                        <li><i class="fas fa-check-circle text-success"></i> Suivre l'état de vos demandes</li>
                        <li><i class="fas fa-check-circle text-success"></i> Gérer votre profil et informations</li>
                        <li><i class="fas fa-check-circle text-success"></i> Être notifié des mises à jour importantes</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, var(--brand-green), #0a7a55);
    }
    .card {
        border: none;
        border-radius: 16px;
    }
    .card-header {
        border-radius: 16px 16px 0 0 !important;
        padding: 1.5rem;
    }
    .form-control:focus {
        border-color: var(--brand-green);
        box-shadow: 0 0 0 0.25rem rgba(14, 159, 110, 0.25);
    }
    .btn-primary {
        background-color: var(--brand-green);
        border-color: var(--brand-green);
    }
    .btn-primary:hover {
        background-color: #0a7a55;
        border-color: #0a7a55;
    }
</style>
@endsection

