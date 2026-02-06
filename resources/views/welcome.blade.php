@extends('layouts.app')

@section('title', 'Accueil | Gestion de Tâches')

@section('content')
<div class="container-fluid px-4">
    <div class="hero-section">
        <h1 class="display-4 fw-bold mb-3">Gestion de Tâches Professionnelle</h1>
        <p class="lead">Organisez, planifiez et suivez vos tâches avec efficacité</p>
        <a href="{{ route('tasks.index') }}" class="btn btn-light btn-lg mt-3">Commencer maintenant</a>
    </div>

    <div class="row mb-5">
        <div class="col-md-4 mb-4">
            <div class="card feature-card h-100">
                <div class="card-body text-center">
                    <div class="feature-icon">
                        <i class="fas fa-list-check"></i>
                    </div>
                    <h5 class="card-title">Gestion des Tâches</h5>
                    <p class="card-text">Créez, organisez et suivez toutes vos tâches avec des dates, heures et priorités.</p>
                    <a href="{{ route('tasks.index') }}" class="btn btn-primary">Voir les tâches</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card feature-card h-100">
                <div class="card-body text-center">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h5 class="card-title">Tableau de Bord</h5>
                    <p class="card-text">Visualisez vos statistiques et tâches récentes pour une meilleure productivité.</p>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Accéder au tableau de bord</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card feature-card h-100">
                <div class="card-body text-center">
                    <div class="feature-icon">
                        <i class="fas fa-tags"></i>
                    </div>
                    <h5 class="card-title">Catégorisation</h5>
                    <p class="card-text">Organisez vos tâches par catégories personnalisées pour une meilleure gestion.</p>
                    <a href="{{ route('categories.index') }}" class="btn btn-primary">Gérer les catégories</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="section-title">Fonctionnalités Avancées</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i> Création et gestion des tâches</li>
                                <li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i> Affectation de priorités</li>
                                <li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i> Dates d'échéance</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i> Organisation par catégories</li>
                                <li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i> Suivi des tâches terminées</li>
                                <li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i> Identification des tâches en retard</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="section-title">Statistiques</h5>
                    <div class="row text-center">
                        <div class="col-md-3 col-6 mb-4">
                            <div class="stats-number text-primary">{{ \App\Models\Task::where('user_id', auth()->id() ?? 1)->count() }}</div>
                            <p class="text-muted">Tâches</p>
                        </div>
                        <div class="col-md-3 col-6 mb-4">
                            <div class="stats-number text-success">{{ \App\Models\Task::where('user_id', auth()->id() ?? 1)->where('completed', true)->count() }}</div>
                            <p class="text-muted">Terminées</p>
                        </div>
                        <div class="col-md-3 col-6 mb-4">
                            <div class="stats-number text-warning">{{ \App\Models\Task::where('user_id', auth()->id() ?? 1)->where('completed', false)->count() }}</div>
                            <p class="text-muted">En cours</p>
                        </div>
                        <div class="col-md-3 col-6 mb-4">
                            <div class="stats-number text-danger">{{ \App\Models\Task::where('user_id', auth()->id() ?? 1)->overdue()->count() }}</div>
                            <p class="text-muted">En retard</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mt-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5>Optimisez votre productivité dès aujourd'hui</h5>
                    <p class="mb-0">Notre plateforme de gestion de tâches est conçue pour vous aider à atteindre vos objectifs plus efficacement.</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="{{ route('tasks.index') }}" class="btn btn-primary btn-lg">Créer une tâche</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
