@extends('layouts.app')

@section('title', 'Catégories | Gestion de Tâches')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Mes Catégories</h1>
        <div class="btn-toolbar">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="fas fa-plus me-2"></i> Nouvelle Catégorie
            </button>
        </div>
    </div>

    <!-- Modal pour créer une nouvelle catégorie -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary-gradient text-white">
                    <h5 class="modal-title" id="addCategoryModalLabel">Créer une nouvelle catégorie</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom de la catégorie *</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description (optionnelle)</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Créer la catégorie</button>
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

    <div class="row">
        @forelse($categories as $category)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title">{{ $category->name }}</h5>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}"><i class="fas fa-edit me-2"></i>Modifier</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ? Elle doit être vide pour être supprimée.')">
                                                <i class="fas fa-trash me-2"></i>Supprimer
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <p class="card-text flex-grow-1">{{ $category->description ?? 'Aucune description' }}</p>
                        
                        <div class="mt-auto pt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">
                                    <i class="fas fa-tasks me-1"></i>{{ $category->tasks->count() }} tâche(s)
                                </span>
                                <a href="{{ route('tasks.index') }}?category={{ $category->id }}" class="btn btn-sm btn-outline-primary">
                                    Voir les tâches
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal de modification de catégorie -->
            <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" aria-labelledby="editCategoryModalLabel{{ $category->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-primary-gradient text-white">
                            <h5 class="modal-title" id="editCategoryModalLabel{{ $category->id }}">Modifier la catégorie</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('categories.update', $category->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="edit_name_{{ $category->id }}" class="form-label">Nom de la catégorie *</label>
                                    <input type="text" class="form-control" id="edit_name_{{ $category->id }}" name="name" value="{{ $category->name }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_description_{{ $category->id }}" class="form-label">Description (optionnelle)</label>
                                    <textarea class="form-control" id="edit_description_{{ $category->id }}" name="description" rows="3">{{ $category->description }}</textarea>
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
                    <i class="fas fa-folder-open fa-5x text-muted mb-4"></i>
                    <h4 class="text-muted">Aucune catégorie trouvée</h4>
                    <p class="text-muted">Créez votre première catégorie pour organiser vos tâches</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                        <i class="fas fa-plus me-2"></i> Créer une catégorie
                    </button>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection