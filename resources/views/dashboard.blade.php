@extends('layouts.app')

@section('title', 'Tableau de bord | Gestion de Tâches')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tableau de bord</h1>
        <div class="btn-toolbar">
            <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nouvelle tâche
            </a>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card bg-primary-gradient text-white">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total des tâches</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $totalTasks }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tasks fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card bg-success-gradient text-white">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Tâches terminées</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $completedTasks }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card bg-warning-gradient text-white">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Tâches en cours</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $pendingTasks }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card bg-danger-gradient text-white">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Tâches en retard</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $overdueTasks }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sections principales -->
    <div class="row">
        <!-- Tâches en retard -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card">
                <div class="card-header bg-danger-gradient text-white">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Tâches en retard</h5>
                </div>
                <div class="card-body">
                    @if($overdueTasksList->count() > 0)
                        @foreach($overdueTasksList as $task)
                            <div class="task-item border-start border-danger ps-3 py-2 mb-3">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-0">{{ $task->title }}</h6>
                                        <small class="text-muted">Échéance: {{ $task->due_date->format('d/m/Y') }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-danger">{{ $task->category ? $task->category->name : 'Non catégorisée' }}</span>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-outline-primary">Voir</a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <p class="text-muted">Aucune tâche en retard</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Prochaines tâches de la semaine -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card">
                <div class="card-header bg-info-gradient text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar-week me-2"></i>Tâches cette semaine</h5>
                </div>
                <div class="card-body">
                    @if($upcomingTasks->count() > 0)
                        @foreach($upcomingTasks as $task)
                            <div class="task-item border-start border-info ps-3 py-2 mb-3">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-0">{{ $task->title }}</h6>
                                        <small class="text-muted">
                                            {{ $task->scheduled_date->format('l d/m/Y') }} à {{ $task->scheduled_time }}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-info">{{ $task->category ? $task->category->name : 'Non catégorisée' }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucune tâche prévue cette semaine</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Tâches récentes -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card">
                <div class="card-header bg-secondary-gradient text-white">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Tâches récentes</h5>
                </div>
                <div class="card-body">
                    @if($recentTasks->count() > 0)
                        @foreach($recentTasks as $task)
                            <div class="task-item border-start {{ $task->completed ? 'border-success' : 'border-primary' }} ps-3 py-2 mb-3">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-0">{{ $task->title }}</h6>
                                        <small class="text-muted">{{ $task->created_at->diffForHumans() }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge {{ $task->completed ? 'bg-success' : 'bg-warning text-dark' }}">
                                            {{ $task->completed ? 'Terminée' : 'En cours' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-1">
                                    @switch($task->priority)
                                        @case(0)
                                            <span class="badge bg-success">Basse</span>
                                            @break
                                        @case(1)
                                            <span class="badge bg-warning text-dark">Moyenne</span>
                                            @break
                                        @case(2)
                                            <span class="badge bg-danger">Haute</span>
                                            @break
                                    @endswitch
                                    <span class="badge bg-info">{{ $task->category ? $task->category->name : 'Non catégorisée' }}</span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucune tâche récente</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Résumé des catégories -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary-gradient text-white">
                    <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Catégories</h5>
                </div>
                <div class="card-body text-center">
                    <div class="display-4 text-primary mb-3">{{ $totalCategories }}</div>
                    <p class="fs-5 mb-4">Catégories créées</p>
                    <a href="{{ route('categories.index') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-tag me-2"></i>Gérer les catégories
                    </a>
                </div>
            </div>
            
            <!-- Actions rapides -->
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Actions rapides</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <a href="{{ route('tasks.index') }}" class="btn btn-primary w-100">
                                <i class="fas fa-list me-1"></i> Voir tâches
                            </a>
                        </div>
                        <div class="col-md-6 mb-2">
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-tags me-1"></i> Catégories
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection