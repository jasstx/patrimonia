@extends('layouts.app')

@push('styles')
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
  corePlugins: { preflight: false },
  theme: {
    extend: {
      colors: {
        'burkina-red': '#EF2B2D',
        'burkina-green': '#009639',
        'burkina-yellow': '#FCD116',
        'burkina-red-light': '#FEF2F2',
        'burkina-green-light': '#F0FDF4',
        'burkina-yellow-light': '#FFFBEB',
      },
      fontFamily: {
        'sans': ['Inter', 'system-ui', 'sans-serif'],
      },
      animation: {
        'fade-in': 'fadeIn 0.6s ease-in-out',
        'slide-up': 'slideUp 0.8s ease-out',
        'float': 'float 6s ease-in-out infinite',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        slideUp: {
          '0%': { transform: 'translateY(30px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
        float: {
          '0%, 100%': { transform: 'translateY(0px)' },
          '50%': { transform: 'translateY(-10px)' },
        }
      }
    }
  }
}
</script>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

.gradient-text {
  background: linear-gradient(135deg, #FF0000, #00FF00);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.gradient-bg {
  background: linear-gradient(135deg, #FFE5E5 0%, #E5FFE5 100%);
}

.card-hover {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.card-hover:hover {
  transform: translateY(-8px);
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.btn-glow {
  position: relative;
  overflow: hidden;
}

.btn-glow::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
  transition: left 0.5s;
}

.btn-glow:hover::before {
  left: 100%;
}

.pattern-dots {
  background-image: radial-gradient(circle, #EF2B2D 1px, transparent 1px);
  background-size: 20px 20px;
  opacity: 0.1;
}

</style>
@endpush

@section('title', 'Accueil - Patrimonia')
@section('content')
<!-- Hero Section -->
<div class="gradient-bg min-h-[60vh] lg:min-h-[70vh] flex items-center relative overflow-hidden">
  <!-- Pattern overlay -->
  <div class="absolute inset-0 pattern-dots"></div>

  <div class="container mx-auto px-4 relative z-10">
    <div class="grid lg:grid-cols-2 gap-12 items-center">
      <!-- Left Content -->
      <div class="animate-fade-in">
        <div class="inline-flex items-center px-4 py-2 rounded-full bg-white/80 backdrop-blur-sm border border-burkina-red/20 mb-6">
          <div class="w-2 h-2 bg-burkina-red rounded-full mr-3"></div>
          <span class="text-sm font-medium text-gray-700">Plateforme Officielle</span>
        </div>

        <h1 class="text-5xl lg:text-7xl font-black mb-6 leading-tight">
          <span class="gradient-text">Patrimonia</span>
        </h1>

        <p class="text-xl lg:text-2xl text-gray-600 mb-8 leading-relaxed">
          Répertoire national des détenteurs d'éléments du patrimoine du
          <span class="font-semibold text-burkina-red">Burkina Faso</span>
        </p>

        <div class="flex flex-col sm:flex-row gap-4">
          <a href="{{ route('detenteurs.index') }}"
             class="btn-glow inline-flex items-center justify-center px-8 py-4 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105"
             style="background: linear-gradient(135deg, #FF0000, #00AA00);">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
            Voir les détenteurs
          </a>

          @guest
            <div class="flex gap-3">
              <a href="{{ route('register') }}"
                 class="btn-glow inline-flex items-center justify-center px-6 py-3 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105"
                 style="background: linear-gradient(135deg, #FF0000, #00AA00);">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
                S'inscrire
              </a>
              <a href="{{ route('login') }}"
                 class="btn-glow inline-flex items-center justify-center px-6 py-3 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105"
                 style="background: linear-gradient(135deg, #FF0000, #00AA00);">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                Se connecter
              </a>
            </div>
          @else
            <a href="{{ route('demande.create') }}"
               class="btn-glow inline-flex items-center justify-center px-8 py-4 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105"
               style="background: linear-gradient(135deg, #FF0000, #00AA00);">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
              Créer une demande
            </a>
          @endguest
        </div>
      </div>

      <!-- Right Content: Cultural Gallery -->
      <div class="animate-slide-up">
        <div class="grid grid-cols-2 gap-4 relative">
          <!-- Large image -->
          <div class="col-span-2 h-72 rounded-2xl overflow-hidden shadow-xl relative group">
            <img src="{{ asset('images/mosquee.jpg') }}" alt="Mosquée de Bobo-Dioulasso" loading="lazy" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
            <div class="absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-slate-900/80 to-transparent text-white translate-y-full group-hover:translate-y-0 transition duration-300">
              <h3 class="font-semibold">Architecture Soudano‑Sahélienne</h3>
              <p class="text-sm text-white/90">Mosquée de Bobo‑Dioulasso</p>
            </div>
          </div>
          <!-- Mask -->
          <div class="h-56 rounded-2xl overflow-hidden shadow-xl relative group">
            <img src="{{ asset('images/masque.jpg') }}" alt="Masque traditionnel" loading="lazy" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
            <div class="absolute inset-x-0 bottom-0 p-3 bg-gradient-to-t from-slate-900/80 to-transparent text-white translate-y-full group-hover:translate-y-0 transition duration-300">
              <h3 class="text-sm font-semibold">Masques Traditionnels</h3>
              <p class="text-xs text-white/90">Art ancestral burkinabé</p>
            </div>
          </div>
          <!-- Sankara portrait -->
          <div class="h-56 rounded-2xl overflow-hidden shadow-xl relative group">
            <img src="{{ asset('images/sankara.jpg') }}" alt="Thomas Sankara" loading="lazy" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
            <div class="absolute inset-x-0 bottom-0 p-3 bg-gradient-to-t from-slate-900/80 to-transparent text-white translate-y-full group-hover:translate-y-0 transition duration-300">
              <h3 class="text-sm font-semibold">Culture Vivante</h3>
              <p class="text-xs text-white/90">Transmission du savoir‑faire</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Sankara Quote Section -->
<div class="bg-slate-900 py-14 relative overflow-hidden">
  <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, #FCD116 2px, transparent 2px); background-size: 60px 60px;"></div>
  <div class="container mx-auto px-4 relative z-10 grid md:grid-cols-[200px,1fr] gap-8 items-center">
    <div class="w-[200px] h-[260px] rounded-xl overflow-hidden border-4 border-[#FCD116] shadow-[0_10px_40px_rgba(252,209,22,.3)] mx-auto md:mx-0">
      <img src="{{ asset('images/sankara.jpg') }}" alt="Thomas Sankara" loading="lazy" class="w-full h-full object-cover">
    </div>
    <div class="text-white">
      <h3 class="text-2xl font-extrabold mb-4 uppercase tracking-widest text-[#FCD116]">Thomas Sankara</h3>
      <blockquote class="text-lg md:text-xl leading-relaxed italic border-l-4 border-[#FCD116] pl-6">
      « La culture, c’est ce qui reste dans l’homme quand il a tout oublié. »
      </blockquote>
    </div>
  </div>
</div>

<!-- Stats Section -->
<div class="py-20 bg-white">
  <div class="container mx-auto px-4">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
      <div class="text-center">
        <div class="text-4xl lg:text-5xl font-black text-burkina-red mb-2">191</div>
        <div class="text-gray-600 font-medium">Éléments Patrimoniaux</div>
      </div>
      <div class="text-center">
        <div class="text-4xl lg:text-5xl font-black text-burkina-green mb-2">50+</div>
        <div class="text-gray-600 font-medium">Détenteurs Actifs</div>
      </div>
      <div class="text-center">
        <div class="text-4xl lg:text-5xl font-black text-burkina-yellow mb-2">5</div>
        <div class="text-gray-600 font-medium">Domaines</div>
      </div>
      <div class="text-center">
        <div class="text-4xl lg:text-5xl font-black gradient-text mb-2">100%</div>
        <div class="text-gray-600 font-medium">Gratuit</div>
      </div>
    </div>
  </div>
</div>

<!-- Features Section -->
<div class="py-20 bg-gray-50">
  <div class="container mx-auto px-4">
    <div class="text-center mb-16">
      <h2 class="text-4xl lg:text-5xl font-black gradient-text mb-6">Bienvenue sur Patrimonia</h2>
      <p class="text-xl text-gray-600 max-w-3xl mx-auto">
        Plateforme officielle de recensement et de gestion des détenteurs d'éléments
        inscrits sur la liste du patrimoine national du Burkina Faso.
      </p>
    </div>

    <div class="grid md:grid-cols-3 gap-8">
      <!-- Feature 1 -->
      <div class="card-hover bg-white rounded-2xl p-8 shadow-lg">
        <div class="w-16 h-16 bg-burkina-red-light rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-burkina-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
          </svg>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-4">Détenteurs</h3>
        <p class="text-gray-600">Recensement des gardiens du patrimoine national</p>
      </div>

      <!-- Feature 2 -->
      <div class="card-hover bg-white rounded-2xl p-8 shadow-lg">
        <div class="w-16 h-16 bg-burkina-green-light rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-burkina-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
          </svg>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-4">Éléments</h3>
        <p class="text-gray-600">191 éléments patrimoniaux répertoriés et classés</p>
      </div>

      <!-- Feature 3 -->
      <div class="card-hover bg-white rounded-2xl p-8 shadow-lg">
        <div class="w-16 h-16 bg-burkina-yellow-light rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-8 h-8 text-burkina-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-4">Demandes</h3>
        <p class="text-gray-600">Formulaire d'inscription en ligne sécurisé</p>
      </div>
    </div>
  </div>
</div>

<!-- Actions Section -->
<div class="py-20 bg-white">
  <div class="container mx-auto px-4">
    <div class="grid lg:grid-cols-2 gap-12">
      <!-- Action 1 -->
      <div class="card-hover bg-gradient-to-br from-burkina-red-light to-burkina-red/5 rounded-3xl p-8">
        <div class="w-20 h-20 bg-burkina-red rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
        </div>
        <h3 class="text-3xl font-bold text-gray-900 mb-4">Consulter le Répertoire</h3>
        <p class="text-gray-600 mb-8 text-lg">Accédez à la liste complète des détenteurs et éléments patrimoniaux du Burkina Faso</p>
        <a href="{{ route('detenteurs.index') }}"
           class="btn-glow inline-flex items-center px-6 py-3 text-white font-semibold rounded-xl transition-all duration-300"
           style="background: linear-gradient(135deg, #FF0000, #00AA00);">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
          </svg>
          Voir les détenteurs
        </a>
      </div>

      <!-- Action 2 -->
      <div class="card-hover bg-gradient-to-br from-burkina-green-light to-burkina-green/5 rounded-3xl p-8">
        <div class="w-20 h-20 bg-burkina-green rounded-2xl flex items-center justify-center mb-6">
          <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
          </svg>
        </div>
        <h3 class="text-3xl font-bold text-gray-900 mb-4">Soumettre une Demande</h3>
        <p class="text-gray-600 mb-8 text-lg">Formulaire d'inscription comme détenteur de patrimoine national</p>
        @guest
          <a href="{{ route('login') }}"
             class="btn-glow inline-flex items-center px-6 py-3 text-white font-semibold rounded-xl transition-all duration-300"
             style="background: linear-gradient(135deg, #FF0000, #00AA00);">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
            Se connecter
          </a>
        @else
          <a href="{{ route('demande.create') }}"
             class="btn-glow inline-flex items-center px-6 py-3 text-white font-semibold rounded-xl transition-all duration-300"
             style="background: linear-gradient(135deg, #FF0000, #00AA00);">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Créer une demande
          </a>
        @endguest
      </div>
    </div>
  </div>
</div>

<!-- Minister Section -->
<div class="py-16 bg-white">
  <div class="container mx-auto px-4">
    <div class="max-w-6xl mx-auto">
      <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="md:flex">
          <!-- Minister Image -->
          <div class="md:w-1/3 relative">
            <img src="{{ asset('images/ministre.jpg') }}" alt="Ministre Pingdwendé Gilbert OUÉDRAOGO" class="w-full h-full object-cover">
            <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/70 to-transparent">
              <h3 class="text-xl font-bold text-white">Pingdwendé Gilbert OUÉDRAOGO</h3>
              <p class="text-white/90">Ministre de la Communication, de la Culture, des Arts et du Tourisme</p>
            </div>
          </div>

          <!-- Minister Bio -->
          <div class="md:w-2/3 p-8 flex flex-col justify-center">
            <div class="flex items-center mb-6">
              <div class="w-12 h-1 bg-burkina-red mr-4"></div>
              <h2 class="text-2xl font-bold text-gray-900">Le Ministre</h2>
            </div>

            <p class="text-gray-600 mb-6 leading-relaxed">
              Nommé le 08 décembre 2024 aux fonctions de Ministre de la Communication, de la Culture, des Arts et du Tourisme, Monsieur Pingdwendé Gilbert OUÉDRAOGO a été installé le 10 décembre 2024.
            </p>

            <div class="mt-6 pt-6 border-t border-gray-100">
              <div class="flex items-center">
                <div class="text-burkina-red mr-4">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                  </svg>
                </div>
                <div>
                  <h4 class="font-semibold text-gray-900">Ministère de la Communication, de la Culture, des Arts et du Tourisme</h4>
                  <p class="text-sm text-gray-600">Ouagadougou, Burkina Faso</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Info Section -->
<div class="py-20 bg-gradient-to-r from-burkina-red to-burkina-green">
  <div class="container mx-auto px-4">
    <div class="max-w-4xl mx-auto text-center">
      <div class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm rounded-full mb-8">
        <svg class="w-6 h-6 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="text-white font-semibold">Informations importantes</span>
      </div>

      <p class="text-xl text-white/90 leading-relaxed">
        Cette plateforme est sous la supervision du
        <span class="font-bold text-white">Ministère de la Communication, de la Culture, des Arts et du Tourisme</span>
        du Burkina Faso.
      </p>
    </div>
  </div>
</div>
@endsection
