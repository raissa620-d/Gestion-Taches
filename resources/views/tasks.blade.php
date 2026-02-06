@extends('layouts.app')

@section('title', 'Mes Tâches | Gestion de Tâches')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Mes Tâches</h1>
        <div class="btn-toolbar">
            <button type="button" class="btn btn-sm btn-outline-secondary me-2" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                <i class="fas fa-plus me-1"></i> Nouvelle tâche
            </button>
            <div class="btn-group me-2">
                <a href="{{ route('tasks.index') }}" class="btn {{ request()->get('filter') === null ? 'btn-primary' : 'btn-outline-primary' }}">Toutes</a>
                <a href="{{ route('tasks.index') }}?filter=pending" class="btn {{ request()->get('filter') === 'pending' ? 'btn-primary' : 'btn-outline-primary' }}">En attente</a>
                <a href="{{ route('tasks.index') }}?filter=completed" class="btn {{ request()->get('filter') === 'completed' ? 'btn-primary' : 'btn-outline-primary' }}">Terminées</a>
                <a href="{{ route('tasks.index') }}?filter=overdue" class="btn {{ request()->get('filter') === 'overdue' ? 'btn-primary' : 'btn-outline-primary' }}">En retard</a>
            </div>
        </div>
    </div>

    <!-- Formulaire pour créer une nouvelle tâche (modal) -->
    <div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary-gradient text-white">
                    <h5 class="modal-title" id="addTaskModalLabel">Ajouter une nouvelle tâche</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('tasks.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Titre *</label>
                                    <input type="text" class="form-control" id="title" name="title" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Catégorie</label>
                                    <select class="form-select" id="category_id" name="category_id">
                                        <option value="">Aucune catégorie</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="scheduled_date" class="form-label">Date de planification *</label>
                                    <input type="date" class="form-control" id="scheduled_date" name="scheduled_date" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="scheduled_time" class="form-label">Heure de planification *</label>
                                    <input type="time" class="form-control" id="scheduled_time" name="scheduled_time" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="priority" class="form-label">Priorité</label>
                                    <select class="form-select" id="priority" name="priority">
                                        <option value="0">Basse</option>
                                        <option value="1">Moyenne</option>
                                        <option value="2">Haute</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="due_date" class="form-label">Date d'échéance (optionnelle)</label>
                                    <input type="date" class="form-control" id="due_date" name="due_date">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Créer la tâche</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Tâches -->
    <div class="row">
        @forelse($tasks as $task)
            <div class="col-lg-6 mb-4">
                <div class="card task-item h-100 {{ $task->priority == 2 ? 'priority-high' : ($task->priority == 1 ? 'priority-medium' : 'priority-low') }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title">
                                    {{ $task->title }}
                                    @if($task->completed)
                                        <span class="badge bg-success ms-2"><i class="fas fa-check me-1"></i> Terminée</span>
                                    @else
                                        <span class="badge bg-warning text-dark ms-2"><i class="fas fa-clock me-1"></i> En attente</span>
                                    @endif
                                    
                                    @if($task->due_date && $task->due_date->isPast() && !$task->completed)
                                        <span class="badge bg-danger ms-2"><i class="fas fa-exclamation-triangle me-1"></i> EN RETARD</span>
                                    @endif
                                </h5>

                                <p class="card-text">
                                    {{ $task->description ?? 'Aucune description' }}
                                </p>

                                <div class="row g-1">
                                    <div class="col-md-6">
                                        <p class="mb-1">
                                            <i class="fas fa-calendar-day me-2 text-primary"></i>
                                            <strong>{{ $task->scheduled_date ? $task->scheduled_date->format('d/m/Y') : '' }}</strong>
                                        </p>
                                        <p class="mb-1">
                                            <i class="fas fa-clock me-2 text-primary"></i>
                                            <strong>{{ $task->scheduled_time }}</strong>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        @if($task->due_date)
                                            <p class="mb-1">
                                                <i class="fas fa-flag me-2 text-primary"></i>
                                                <span class="text-muted">Échéance: <strong>{{ $task->due_date->format('d/m/Y') }}</strong></span>
                                            </p>
                                        @endif
                                        
                                        @if($task->category)
                                            <span class="badge bg-info me-1 mb-1">
                                                <i class="fas fa-tag me-1"></i>{{ $task->category->name }}
                                            </span>
                                        @endif
                                        
                                        <span class="badge 
                                            @if($task->priority == 2) bg-danger 
                                            @elseif($task->priority == 1) bg-warning text-dark 
                                            @else bg-success @endif">
                                            @if($task->priority == 2) <i class="fas fa-arrow-up me-1"></i>Haute priorité
                                            @elseif($task->priority == 1) <i class="fas fa-arrows-h me-1"></i>Moyenne priorité
                                            @else <i class="fas fa-arrow-down me-1"></i>Basse priorité @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editTaskModal{{ $task->id }}">
                                            <i class="fas fa-edit me-2"></i>Modifier
                                        </a>
                                    </li>
                                    @if(!$task->completed)
                                        <li>
                                            <form action="{{ route('tasks.toggle', $task->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="dropdown-item">
                                                    <i class="fas fa-check-circle me-2 text-success"></i>Marquer comme terminée
                                                </button>
                                            </form>
                                        </li>
                                    @else
                                        <li>
                                            <form action="{{ route('tasks.toggle', $task->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="dropdown-item">
                                                    <i class="fas fa-undo me-2 text-warning"></i>Réactiver
                                                </button>
                                            </form>
                                        </li>
                                    @endif
                                    @if($task->completed)
                                        <li>
                                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?')">
                                                    <i class="fas fa-trash me-2"></i>Supprimer
                                                </button>
                                            </form>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                        
                        @if(!$task->completed)
                            <div class="mt-3 pt-3 border-top">
                                <form action="{{ route('tasks.update-time', $task->id) }}" method="POST" class="d-flex">
                                    @csrf
                                    @method('PUT')
                                    <div class="input-group">
                                        <input type="time" name="scheduled_time" class="form-control" value="{{ $task->scheduled_time }}" required>
                                        <button class="btn btn-outline-primary" type="submit">Modifier l'heure</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Modal de modification de tâche -->
            <div class="modal fade" id="editTaskModal{{ $task->id }}" tabindex="-1" aria-labelledby="editTaskModalLabel{{ $task->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-primary-gradient text-white">
                            <h5 class="modal-title" id="editTaskModalLabel{{ $task->id }}">Modifier la tâche</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="edit_title_{{ $task->id }}" class="form-label">Titre</label>
                                    <input type="text" class="form-control" id="edit_title_{{ $task->id }}" name="title" value="{{ $task->title }}" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="edit_description_{{ $task->id }}" class="form-label">Description</label>
                                    <textarea class="form-control" id="edit_description_{{ $task->id }}" name="description" rows="3">{{ $task->description }}</textarea>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit_scheduled_date_{{ $task->id }}" class="form-label">Date</label>
                                            <input type="date" class="form-control" id="edit_scheduled_date_{{ $task->id }}" name="scheduled_date" value="{{ $task->scheduled_date->format('Y-m-d') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit_scheduled_time_{{ $task->id }}" class="form-label">Heure</label>
                                            <input type="time" class="form-control" id="edit_scheduled_time_{{ $task->id }}" name="scheduled_time" value="{{ $task->scheduled_time }}" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit_priority_{{ $task->id }}" class="form-label">Priorité</label>
                                            <select class="form-select" id="edit_priority_{{ $task->id }}" name="priority">
                                                <option value="0" {{ $task->priority == 0 ? 'selected' : '' }}>Basse</option>
                                                <option value="1" {{ $task->priority == 1 ? 'selected' : '' }}>Moyenne</option>
                                                <option value="2" {{ $task->priority == 2 ? 'selected' : '' }}>Haute</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit_category_{{ $task->id }}" class="form-label">Catégorie</label>
                                            <select class="form-select" id="edit_category_{{ $task->id }}" name="category_id">
                                                <option value="">Aucune catégorie</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ $task->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="edit_due_date_{{ $task->id }}" class="form-label">Date d'échéance</label>
                                    <input type="date" class="form-control" id="edit_due_date_{{ $task->id }}" name="due_date" value="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-5x text-muted mb-4"></i>
                    <h4 class="text-muted">Aucune tâche trouvée</h4>
                    <p class="text-muted">Commencez par créer votre première tâche en cliquant sur le bouton "Nouvelle tâche"</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                        <i class="fas fa-plus me-2"></i> Créer une tâche
                    </button>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection