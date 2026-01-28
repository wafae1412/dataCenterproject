@extends('layouts.app')

@push('styles')
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
@endpush

@section('content')

    {{-- Hero Section --}}
    <section class="hero">
        <div class="container hero-content">
            <h1>Système de Gestion de<br>Centre de Données</h1>
            <p>Optimisez les opérations de votre centre de données avec notre solution de gestion complète. Contrôlez les ressources, gérez les utilisateurs et traitez les réservations efficacement.</p>
            <div class="hero-buttons">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-primary">Se connecter</a>
                    <a href="{{ route('register') }}" class="btn btn-outline">S'inscrire</a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Aller au tableau de bord</a>
                @endguest
            </div>
        </div>
    </section>

    {{-- About Section --}}
    <section class="about">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <h2 class="section-title" style="text-align: left;">Efficace & Fiable</h2>
                    <p>Notre plateforme est conçue pour moderniser l'administration des centres de données. Elle comble le fossé entre l'infrastructure physique et la gestion numérique, offrant une interface unifiée pour les administrateurs, les techniciens et les utilisateurs.</p>
                    <p style="margin-top: 1rem;">Que vous gériez des serveurs, planifiiez la maintenance ou traitiez les accès utilisateurs, notre système fournit les outils nécessaires pour maintenir l'excellence opérationnelle.</p>
                </div>
                <div class="about-image">
                    <i class="fas fa-network-wired"></i>
                </div>
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="features">
        <div class="container">
            <h2 class="section-title">Fonctionnalités Clés</h2>
            <p class="section-subtitle">Tout ce dont vous avez besoin pour gérer votre centre de données efficacement.</p>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-users"></i></div>
                    <h3>Gestion des Utilisateurs</h3>
                    <p>Administration complète des utilisateurs avec profils détaillés et suivi d'activité.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-server"></i></div>
                    <h3>Contrôle des Ressources</h3>
                    <p>Suivez l'état des serveurs, des équipements et de l'infrastructure en temps réel.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-calendar-check"></i></div>
                    <h3>Réservations</h3>
                    <p>Système de réservation fluide pour les ressources avec flux d'approbation.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-tools"></i></div>
                    <h3>Maintenance</h3>
                    <p>Planifiez et suivez les tâches de maintenance pour assurer la disponibilité.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-bell"></i></div>
                    <h3>Notifications</h3>
                    <p>Alertes instantanées pour les changements d'état, les approbations et les mises à jour système.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-user-shield"></i></div>
                    <h3>Accès Basé sur les Rôles</h3>
                    <p>Contrôle d'accès sécurisé avec des permissions définies pour chaque rôle.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section class="how-it-works">
        <div class="container">
            <h2 class="section-title">Comment ça Marche</h2>
            <p class="section-subtitle">Étapes simples pour démarrer avec la plateforme.</p>

            <div class="steps-container">
                <div class="step-item">
                    <div class="step-number">1</div>
                    <h3 class="step-title">Créer un Compte</h3>
                    <p class="step-desc">Inscrivez-vous pour obtenir l'accès au système.</p>
                </div>
                <div class="step-item">
                    <div class="step-number">2</div>
                    <h3 class="step-title">Connexion & Vérification</h3>
                    <p class="step-desc">Connectez-vous en toute sécurité et vérifiez vos informations de rôle.</p>
                </div>
                <div class="step-item">
                    <div class="step-number">3</div>
                    <h3 class="step-title">Accéder au Tableau de Bord</h3>
                    <p class="step-desc">Entrez dans votre tableau de bord personnalisé en fonction de votre rôle.</p>
                </div>
                <div class="step-item">
                    <div class="step-number">4</div>
                    <h3 class="step-title">Gérer</h3>
                    <p class="step-desc">Commencez à gérer les ressources, les réservations et les tâches.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Roles Section --}}
    <section class="roles">
        <div class="container">
            <h2 class="section-title">Rôles Utilisateurs</h2>
            <p class="section-subtitle">Expériences adaptées pour chaque intervenant.</p>

            <div class="roles-grid">
                <div class="role-card">
                    <div class="role-icon"><i class="fas fa-user-cog"></i></div>
                    <h3 class="role-title">Admin</h3>
                    <p class="role-desc">Contrôle total du système. Gérez les utilisateurs, attribuez des rôles et supervisez toute la configuration de l'infrastructure.</p>
                </div>
                <div class="role-card">
                    <div class="role-icon"><i class="fas fa-user-tie"></i></div>
                    <h3 class="role-title">Responsable</h3>
                    <p class="role-desc">Gérez des groupes de ressources spécifiques, approuvez les réservations et supervisez les plannings de maintenance.</p>
                </div>
                <div class="role-card">
                    <div class="role-icon"><i class="fas fa-user"></i></div>
                    <h3 class="role-title">Utilisateur</h3>
                    <p class="role-desc">Consultez les ressources disponibles, effectuez des réservations et signalez des problèmes ou besoins de maintenance.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Call To Action --}}
    <section class="cta">
        <div class="container">
            <h2>Prêt à Commencer ?</h2>
            <p>Rejoignez la plateforme aujourd'hui et découvrez une gestion simplifiée du centre de données.</p>
            @guest
                <a href="{{ route('register') }}" class="btn btn-light">S'inscrire Maintenant</a>
            @else
                <a href="{{ route('dashboard') }}" class="btn btn-light">Aller au tableau de bord</a>
            @endguest
        </div>
    </section>

    {{-- Footer --}}
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <h3><i class="fas fa-server"></i> DataCenter Hub</h3>
                    <p>Système professionnel de gestion de centre de données conçu pour l'efficacité, la sécurité et l'évolutivité.</p>
                </div>
                <div class="footer-links">
                    <h4>Liens Rapides</h4>
                    <ul>
                        <li><a href="#">Accueil</a></li>
                        <li><a href="#">À Propos</a></li>
                        <li><a href="#">Fonctionnalités</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-links">
                    <h4>Légal</h4>
                    <ul>
                        <li><a href="#">Politique de Confidentialité</a></li>
                        <li><a href="#">Conditions d'Utilisation</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} DataCenter Hub. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

@endsection
